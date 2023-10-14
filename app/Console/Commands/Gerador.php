<?php

namespace App\Console\Commands;

use App\Console\Commands\Gerador\Migration;
use App\Console\Commands\Gerador\Model;
use App\Console\Commands\Gerador\Repository;
use App\Console\Commands\Gerador\Request;
use App\Console\Commands\Gerador\Service;
use App\Console\Commands\Gerador\Resource;
use App\Console\Commands\Gerador\Controller;
use App\Console\Commands\Gerador\Rota;
use Illuminate\Console\Command;

class Gerador extends Command
{

    protected $signature = 'my:generator';

    /**
     * Atrributes
     *
     * @array
     */
    protected $params = [];



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    private function executeCrud(){
        passthru('php artisan migrate && php artisan l5-swagger:generate && php artisan cache:clear && php artisan route:cache && php artisan octane:reload');
    }


    private function getConsole()
    {
        $name = $this->ask('Informe o nome do prefixo da classes ( Ex: TestController deve ser informado Test)');
        if(!$name){
            $this->error( "\n".'O prefixo da classe não foi informado'. "\n");exit();
        }else{
            $this->params['nome'] = $name;
        }

        $descricao = $this->ask('Informe o nome da Funcionalidade ( Ex: Teste )', $name);
        if(!$descricao){
            $this->error( "\n".'O nome da funcionalidade não foi informado'. "\n");exit();
        }else{
            $this->params['descricao'] = $descricao;
        }

        $rota = $this->ask('Informe a rota ( Ex: teste )', strtolower(str_replace(' ','', $name)));
        if(!$rota){
            $this->error( "\n".'A rota da funcionalidade não foi informada'. "\n");exit();
        }else{
            $this->params['rota'] = $rota;
        }

        $auth = $this->ask('A rota necessita de autenticação ? S - Sim  ou N - Não','S');
        if(!$auth){
            $this->error( "\n".'Não foi informada a necessidade de autenticação da rota'. "\n");exit();
        }else{
            $this->params['auth'] = trim($auth) == 'S'? true : false;
        }


        $tabela = $this->ask('Informe o nome da tabela do banco  ( Ex: testes )', $rota."s");
        if(!$tabela){
            $this->error( "\n".'O nome da tabela não foi informada'. "\n");exit();
        }else{
            $this->params['tabela'] = $tabela;
        }

        return true;
    }


    private function getCampos()
    {
        passthru('clear');
        $campo = $this->ask('Informe o nome da coluna');
        if(!$campo) {
            if( $this->params['campos'] > 0){
                $novo = $this->ask('Deseja incluir um novo campo S - Sim ou N - Não','S');
                return $novo == "S" ? true : false;
            }else{
               $this->error("\n" . "O nome da coluna não foi informado" . "\n");exit();
            }
        }


        $comentario = $this->ask('Informe um comentário para a coluna do banco', ucfirst($campo));
        if(!$comentario) {
            $this->error("\n" . "O comentário da coluna não foi informado" . "\n");exit();
        }

        $visivel = $this->ask('O campo é visivel aos usuários ?  S - Sim  e N - Não', 'S');
        if(!$visivel) {
            $this->error("\n" . "A visibilidade do campo não foi informada" . "\n");exit();
        }else{
            $visivel = (trim($visivel) == "S")? true : false;
        }

        if($visivel) {
            $form = $this->ask('Informe o nome do input do formulario', $campo);
            if (!$form) {
                $this->error("\n" . "O nome do formulário não foi informado" . "\n");
                exit();
            }

            $label = $this->ask('Informe o nome da label do formulário para (validação)', $comentario);
            if (!$label) {
                $this->error("\n" . "A label de validação não foi informada" . "\n");
                exit();
            }

        }else{
            $form = $campo;
        }

        $required = $this->ask('O campo é obrigátorio ?  S - Sim  e N - Não','S');
        if(!$required) {
            $this->error("\n" . "A obrigátoriedade do campo não foi informada" . "\n");exit();
        }else{
            $required = (trim($required) == "S")? true : false;
        }


        $type = $this->ask('Informe o tipo da coluna ( S - String, I - Integer, N - Numeric/Double/Number,  B - Boolean, D - Date/DateTime, T - text)','S');
        if(!$type){
            $this->error( "\n"."O tipo da coluna {$campo} não foi informada". "\n");exit();
        }


        if($type == 'S' || $type == 'T'){
            $type = ($type == "S")? "string" : "text";
            $typeForm = "string";
        }elseif($type == 'D'){
            $type = "timestamp";
            $typeForm = "string";
        }elseif ($type == "I"){
            $type = "integer";
            $typeForm = "integer";
        }elseif ($type == "N"){
            $type = "double";
            $typeForm = "number";
        }elseif ($type == "B"){
            $type = "boolean";
            $typeForm = "boolean";
        }

        $typeForm = $this->ask('Informe o tipo do campo no formulario ( string, integer, number, boolean, array, object)',$typeForm);
        if(!$typeForm){
            $this->error( "\n"."O tipo do formulario não foi informada". "\n");exit();
        }


        //Padrao
        $arColuna = ['nome' => $campo, 'form' => $form, 'type' => $type, 'typeForm' => $typeForm,'required' => $required, 'visivel' => $visivel, 'comentario' => $comentario, 'label' => isset($label)? $label : ''];
        //

        if($type == 'string' || $type == 'text'){

            $max = $this->ask('Deseja informar um tamanho máximo de caracteres ? Caso não tecle ENTER');
            if($max){
                $arColuna['atributos']['max'] = $max;
            }
            $min = $this->ask('Deseja informar um tamanho minimo de caracteres ? Caso não tecle ENTER');
            if($min){
                $arColuna['atributos']['min'] = $min;
            }

            $unique = $this->ask('Existe a necessidade de ser único no banco? S -  Sim,  N - Não', 'N');
            if($unique == 'S'){
                $arColuna['unique'] = true;
            }

            $uniqueForm = $this->ask('Existe a necessidade de ser único no formulario? S -  Sim,  N - Não', 'N');
            if($uniqueForm == 'S'){
                $arColuna['atributos']['uniqueform'] = true;
            }

        }elseif($type == 'integer'){
            $fk = $this->ask('Trata-se de uma FK ? S - Sim, N - Não','S');
            if($fk){
                $arColuna['fk'] = true;
                $tabelaFk = $this->ask('Qual o nome da tabela da FK no banco de dados ? Ex: testes');
                $colunaFk = $this->ask("Qual o nome da coluna em {$tabelaFk} ? Ex: id", 'id');
                $classFk = $this->ask("Informe o nome da model do relacionamento ? Ex: Test");
                $tipoFk = $this->ask("Tipo de relacionamento ? 1 - hasOne , 2 - hasMany",1);

                $arColuna['atributos']['fk']['tabela'] =  $tabelaFk;
                $arColuna['atributos']['fk']['coluna'] =  $colunaFk;
                $arColuna['atributos']['fk']['classe'] =  $classFk;
                $arColuna['atributos']['fk']['tipo'] =  $tipoFk;
                $arColuna['atributos']['exists'] =  "exists:{$tabelaFk},{$colunaFk}";
            }
        }elseif($type == 'timestamp'){
            $dateTime = $this->ask('O campo é D - Date ou DT - DateTime','DT');
            if($dateTime == "D"){
                $type = "date";
            }
            $arColuna['type'] = $type;
        }

        $this->params['campos'][] = $arColuna;
        $this->warn("===================================================================");
        $novo = $this->ask('Deseja incluir um novo campo S - Sim ou N - Não','S');
        $this->warn("===================================================================");

        return $novo == "S" ? true : false;
    }

    public function migrate()
    {
        $this->error("\n".'Iniciando a migration....... ' . "\n");

        $primary = $this->ask('Informe o nome da chave primária','id');
        if(!$primary){
            $this->error("\n" . "Chave primária não informada" . "\n");exit();
        }
        $this->params['id'] = $primary;

        $softDelete = $this->ask('Deseja incluir as colunas de SoftDelete ? ','S');
        if($softDelete == 'S'){
            $this->params['softdelete'] = true;
        }

        $newCampos = true;
        while($newCampos){
            $this->error("\n".'Adicionando as colunas da tabela....... ' . "\n");
            $newCampos = $this->getCampos();
        }

        Migration::make($this->params);

    }

    public function model()
    {
        Model::make($this->params);
    }

    public function repositorio()
    {
        Repository::make($this->params);
    }

    public function request()
    {
        Request::make($this->params);
    }

    public function resource()
    {
        Resource::make($this->params);
    }

    public function service()
    {
        Service::make($this->params);
    }

    public function controller()
    {
        Controller::make($this->params);
    }

    public function rota()
    {
        Rota::make($this->params);
    }


    public function sucessoNovo()
    {
        $this->warn("======================= SUCESSO ================================");
        $novo = $this->ask('Deseja criar um novo crud ?  S - Sim ou N - Não','N');
        $this->warn("===================================================================");
        if($novo == 'S'){
            passthru('clear');
            $this->handle();
        }
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $validaParams = $this->getConsole();
        if($validaParams){
           $this->migrate();
           dump($this->params);
           $this->model();
           $this->repositorio();
           $this->request();
           $this->resource();
           $this->service();
           $this->controller();
           $this->rota();
           $this->executeCrud();
           $this->SucessoNovo();
        }
    }
}
