<?php

namespace App\Console\Commands\Gerador;

class Migration
{

    public static function make($arParams)
    {
        $texto = Migration::namespace();
        $texto = Migration::up($arParams, $texto);
        $texto = Migration::down($arParams, $texto);
        Migration::save($arParams['tabela'],$texto);
    }

    public static function namespace()
    {
          return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{";
    }

    public static function up($arParams, $texto)
    {
      $tabela = "'".$arParams['tabela']."'";
      $id = "'".$arParams['id']."'";
      $primary = $arParams['id'] == 'id' ? '$table->id()' : '$table->id('.$id.')';
      $s = "                ";
      $l = "
";
    $texto .= '

    public function up(): void
    {

        Schema::create('.$tabela.', function (Blueprint $table) {
                '.$primary.'->comment("ID");'.$l;

        //COLUNAS
        foreach($arParams['campos'] as $campo):
            $name = "'".$campo['nome']."'";
            $null = $campo['required']? '' : '->nullable()';
            $unique = isset($campo['unique']) && $campo['unique'] === true? '->unique()' : '';
            $comentario = $campo['comentario']? "->comment('".$campo['comentario']."')" : "";
            $max = isset($campo['atributos']['max'])? ",".$campo['atributos']['max'] : "";
            $name = $name.$max;
            $texto .= $s.'$table->'.$campo['type'].'('.$name.')'.$null.$unique.$comentario.';'.$l;
        endforeach;

        if($arParams['softdelete']){
            $texto .= $s.'$table->softDeletes();'.$l;
            $texto .= $s.'$table->timestamps();'.$l;
        }

        $texto .= "        });".$l.$l;
        //FIM COLUNAS


        //FK
   $texto .= '        Schema::table('.$tabela.', function (Blueprint $table) {';

        foreach($arParams['campos'] as $campo):
            if(isset($campo['fk'])) {
                $tabelaFk =  $campo['atributos']['fk']['tabela'];
                $name = "'".$campo['nome']."'";
                $fk = "'".'fk_'.$arParams['tabela'].'_'.$tabelaFk."'";
                $idTabela = "'".$campo['atributos']['fk']['coluna']."'";
                $tabelaFk2 =  "'".$campo['atributos']['fk']['tabela']."'";
                $cascade = "'cascade'";
                $restrict = "'restrict'";


                $texto .= $l.$s.'$table->foreign(['.$name.'],'.$fk.')
                 ->references('.$idTabela.')
                 ->on('.$tabelaFk2.')
                 ->onUpdate('.$cascade.')
                 ->onDelete('.$restrict.');'.$l;
            }
        endforeach;
        //FIM FK
   $texto .= '});'.$l;



        $texto .= "    }".$l;
        return $texto;
    }

    public static function down($arParams, $texto)
    {
        $tabela = "'".$arParams['tabela']."'";
$texto .= "

    public function down(): void
    {
       Schema::dropIfExists($tabela);
    }

};";

        return $texto;
    }


    public static function save($tabela,$texto)
    {

        $nameFile = "/database/migrations/".date('Y_m_d_His')."_create_".$tabela."_table.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
