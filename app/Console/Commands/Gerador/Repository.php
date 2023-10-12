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

use App\Models\%s as Model;
use Illuminate\Support\Facades\Cache;


",$nome);
    }


    public static function classe($nome, $texto)
    {
        return $texto."class {$nome}Repository
{

";
    }

    public static function search($arParams, $texto)
    {
        $search = "'search'";
        $perPage = "'perPage'";
        $page = "'page'";
        $app_per_page = "'per_page'";
        $s = "    ";
        $s2 = "                             ";
        $l = "
";
        $texto .= $s.'public function search($request)'.$l;
        $texto .= $s.'{'.$l.$l;

        $texto .= $s.$s.'$page = $request->get('.$page.',1);'.$l;
        $texto .= $s.$s.'$perPage = $request->get('.$perPage.',config('.$app_per_page.'));'.$l;
        $texto .= $s.$s.'$search = $request->get('.$search.');'.$l;
        $texto .= $s.$s.'$search2 = $search ? "_".strip_tags($search) : ""; '.$l.$l;

        $tabela = "redis_".$arParams['rota']."_search_".'".$page."_".$perPage.$search2;';
        $texto .= $s.$s.'$name = "'.$tabela.$l;

        $texto .= $s.$s.'return Cache::remember($name, 60, function () use($perPage, $page, $search) {
            $data = Model::query();

              if(!is_null($search))
               {
                    ';
                            $cont = 0;
                            foreach($arParams['campos'] as $campo):
                                $name = "'".$campo['nome']."'";
                               if($campo['type'] == "string" || $campo['type'] == "text") {
                                  if($cont == 0){
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
