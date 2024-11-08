<?php

namespace App\Console\Commands\Gerador;

class Repository
{

    public static function make($arParams)
    {
        $texto = Repository::namespace($arParams['nome']);
        $texto = Repository::classe($arParams['nome'], $texto);
        $texto = Repository::search($arParams, $texto);
        Repository::save($arParams['nome'],$texto);
    }

    public static function namespace($nome)
    {
        return sprintf("<?php

namespace App\Repositories;

use App\Models\%s;
use App\Traits\SearchTrait;
use Illuminate\Support\Facades\Cache;


",$nome);
    }


    public static function classe($nome, $texto)
    {
        return $texto."class {$nome}Repository
{

     use SearchTrait;

";
    }

    public static function search($arParams, $texto)
    {
        $search = "'search'";
        $perPage = "'perPage'";
        $nome = $arParams['nome'];
        $page = "'page'";
        $app_per_page = "'app.per_page'";
        $s = "    ";
        $s2 = "                             ";
        $l = "
";
        $texto .= $s.'public function search($request)'.$l;
        $texto .= $s.'{'.$l.$l;

        $texto .= $s.$s.'$page = $request->get('.$page.',1);'.$l;
        $texto .= $s.$s.'$perPage = $request->get('.$perPage.',config('.$app_per_page.'));'.$l;
        $texto .= $s.$s.'$search = $this->filtroSearch($request->get('.$search.'));'.$l;
        $texto .= $s.$s.'$nameCache = $this->cacheSearch('.$nome.'::class, $perPage, $page, $search);'.$l.$l;


        $texto .= $s.$s.'return Cache::remember($nameCache, 60, function () use($search, $request, $perPage,  $page) {
            $data = '.$nome.'::query();

              if(!is_null($search))
               {
                    ';
                            $cont = 0;
                            foreach($arParams['campos'] as $campo):
                                $name = "'".$campo['nome']."'";
                               if($campo['type'] === "string" || $campo['type'] === "text") {
                                  if($cont === 0){
                                      $texto .= '$data->where(function ($q) use ($search) {'.$l;
                                      $texto .= $l.$s2.'$q->where('.$name.', "ilike", "%" . trim($search) . "%")';
                                  }else{
                                      $texto .= $l.$s2.'->orWhere('.$name.', "ilike", "%" . trim($search) . "%")';
                                  }
                                   $cont++;
                               }
                            endforeach;

        if($cont > 0){
            $texto .= ";".$l.'                     });'.$l;
        }

        $texto .= '
                }

              return $data->paginate( $perPage );

         });

    }

}';

        return $texto;
    }


    public static function save($nome,$texto)
    {

        $nameFile = "/app/Repositories/".$nome."Repository.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
