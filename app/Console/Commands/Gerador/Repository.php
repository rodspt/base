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
        $s = "    ";
        $l = "
";
        $texto .= $s.'public function search($request)'.$l;
        $texto .= $s.'{'.$l.$l;
        $texto .= $s.$s.'$search = $request->get('.$search.');'.$l;
        $texto .= $s.$s.'$data = Model::query();'.$l.$l;
        $texto .= $s.$s.' if(!is_null($request->get('.$search.')))'.$l;
        $texto .= $s.$s.' {'.$l;

        $cont = 0;
        foreach($arParams['campos'] as $campo):
            $name = "'".$campo['nome']."'";
           if($campo['type'] == "string" || $campo['type'] == "text") {
              if($cont == 0){
                  $texto .= $s.$s.$s.'$data->where(function ($q) use ($search) {'.$l;
                  $texto .= $s.$s.$s.$s.'$q->where('.$name.', "ilike", "%" . trim($search) . "%")';
                  $cont++;
              }else{
                  $texto .= $s.$s.$s.'->orWhere('.$name.', "ilike", "%" . trim($search) . "%")';
              }
           }
        endforeach;
        if($cont > 0){
            $texto .= ";".$l.$s.$s.$s.'});'.$l;
        }

        $texto .= $s.$s.'}'.$l.$l;

        $texto .= $s.$s.'$perPage = $request->get('.$perPage.',  config('.$perPage.'));
        $data = $data->paginate( $perPage );

        return $data;';

        $texto .= $l.$s."}".$l."}";
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
