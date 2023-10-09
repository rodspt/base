<?php

namespace App\Console\Commands\Gerador;

class Model
{

    public static function make($arParams)
    {
        $texto = Model::namespace();
        $texto = Model::classe($arParams['nome'], $texto);
        $texto = Model::campos($arParams, $texto);
        Model::save($arParams['nome'],$texto);
    }

    public static function namespace()
    {
        return "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

";
    }


    public static function classe($nome, $texto)
    {
        return $texto."class {$nome} extends Model
{
    use HasFactory, SoftDeletes;
";
    }

    public static function campos($arParams, $texto)
    {
        $c = "'created_at',";
        $u = "'updated_at',";
        $d = "'deleted_at',";
        $s = "    ";
        $l = "
";
        //Tabela e primary
        $tabela = "'".$arParams['tabela']."';";
        $id = "'".$arParams['id']."';";
        $texto .= $s.'protected $table = '.$tabela.$l;
        $texto .= $s.'protected $primaryKey = '.$id.$l;

        //Fillable
        $texto .= $s.'protected $fillable = ['.$l;
        foreach($arParams['campos'] as $campo):
            if($campo['visivel']){
                $nome = "'".$campo['nome']."',";
                $texto .= $s.$s.$nome.$l;
            }
        endforeach;
        $texto .=$s."];".$l.$l;

        //Hidden
        $texto .= $s.'protected $hidden = ['.$l;
        foreach($arParams['campos'] as $campo):
            if($campo['visivel'] === false){
                $nome = "'".$campo['nome']."',";
                $texto .= $s.$s.$nome.$l;
            }
        endforeach;
        if($arParams['softdelete']){
            $texto .= $s.$s.$c.$l;
            $texto .= $s.$s.$u.$l;
            $texto .= $s.$s.$d.$l;
        }
        $texto .=$s."];".$l.$l;


        //Dates
        $texto .= $s.'protected $dates = ['.$l;
        foreach($arParams['campos'] as $campo):
            if($campo['type'] == "date" || $campo['type'] == "timestamp"){
                $nome = "'".$campo['nome']."',";
                $texto .= $s.$s.$nome.$l;
            }
        endforeach;
        if($arParams['softdelete']){
            $texto .= $s.$s.$c.$l;
            $texto .= $s.$s.$u.$l;
            $texto .= $s.$s.$d.$l;
        }
        $texto .=$s."];".$l;

        //Relacionamento
        foreach($arParams['campos'] as $campo):
            if(isset($campo['fk'])) {
                $nome = "'".$campo['nome']."'";
                $chave = "'".$campo['atributos']['fk']['coluna']."'";
                $classe =  $campo['atributos']['fk']['classe'];
                $tipo =  $campo['atributos']['fk']['tipo'] == 1? 'hasOne' : 'hasMany';

                $texto .= $l.$s."public function $classe()".$l;
                $texto .= $s."{".$l;
                $texto .= $s.$s.'return $this->'.$tipo.'('.$classe.'::class,'.$chave.','.$nome.');'.$l;
                $texto .=$s."}".$l;
            }
        endforeach;


$texto .= $l."}";
        return $texto;
    }


    public static function save($nome,$texto)
    {

        $nameFile = "/app/Models/".$nome.".php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
