<?php

namespace App\Console\Commands\Gerador;

class Controller
{
    public static function make($arParams)
    {
        $texto = Controller::namespace($arParams['nome']);
        $texto = Controller::classe($arParams['nome'], $texto);
        $texto = Controller::index($arParams, $texto);
        $texto = Controller::show($arParams, $texto);
        $texto = Controller::save($arParams, $texto);
        $texto = Controller::update($arParams, $texto);
        $texto = Controller::delete($arParams,$texto);
        Controller::exportar($arParams['nome'],$texto);
    }

    public static function namespace($nome)
    {
        return str_replace("%s",$nome,'<?php

namespace App\Http\Controllers;

use App\Services\%sService as Service;
use App\Http\Requests\%sRequest as FormRequest;


');
    }


    public static function classe($nome, $texto)
    {
        return   $texto.str_replace("%s",$nome,'
class %sController extends Controller
{

    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

');
    }


    public static function index($arParams, $texto)
    {
        $rota = $arParams['rota'];
        $nome = $arParams['nome'];
        $descricao = $arParams['descricao'];
        $s = "     ";
        $l = "
";
       $texto .= '
     /**
     * @OA\Get(
     * path="/'.$rota.'?search={search}",
     * operationId="listar'.$nome.'",
     * tags={"listar'.$nome.'"},
     * tags={"'.$descricao.'"},
     * summary="Listagem de '.$descricao.'",'.$l;
  if($arParams['auth']){
    $texto .= $s.'* security={{"apiAuth":{}}},'.$l;
  }
  $texto .= '*  @OA\Parameter(description="Descrição do filtro", allowEmptyValue = true, in="path", name="search", required=false,
     *       @OA\Schema(type="string", example="")),
     *  @OA\Response(response=201, description="Listagem realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Listagem realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function index(FormRequest $request)
    {
        return $this->service->search($request);
    }
       '.$l.$l;
       return $texto;
    }


    public static function show($arParams, $texto)
    {
        $rota = $arParams['rota'];
        $nome = $arParams['nome'];
        $descricao = $arParams['descricao'];
        $s = "     ";
        $l = "
";
 $texto .= '
    /**
     * @OA\Get(
     * path="/'.$rota.'/{'.$rota.'}",
     * operationId="buscar'.$nome.'",
     * tags={"buscar'.$nome.'"},
     * tags={"'.$descricao.'"},
     * summary="Buscar '.$descricao.'",'.$l;
     if($arParams['auth']){
         $texto .= $s.'* security={{"apiAuth":{}}},'.$l;
     }
   $texto .= $s.'*    @OA\Parameter(description="Id", in="path", name="'.$rota.'",@OA\Schema(format="int64", type="integer", example="1")),
     *  @OA\Response(response=201, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
     public function show($'.$rota.')
    {
        return $this->service->show($'.$rota.');
    }
     '.$l.$l;

     return $texto;
   }

    public static function save($arParams, $texto)
    {
        $rota = $arParams['rota'];
        $nome = $arParams['nome'];
        $descricao = $arParams['descricao'];
        $arRequired = [];
        $arCampos = [];
        $required = "";
        $s = "         ";
        $l = "
";
      $texto .= '
        /**
         * @OA\Post(
         * path="/'.$rota.'",
         * operationId="register'.$nome.'",
         * tags={"register'.$nome.'"},
         * tags={"'.$descricao.'"},
         * summary="Registrar '.$descricao.'",'.$l;
        if($arParams['auth']){
            $texto .= $s.'* security={{"apiAuth":{}}},'.$l;
        }

        $strCampos = "";
        foreach($arParams['campos'] as $campo):
           $form = '"' . $campo['form'] . '"';
           $example = '"' . $campo['form'] . '"';
            if($campo['visivel']){
                if($campo['required']) {
                    $arRequired[] = $form;
                }
                $arCampos[] = '@OA\Property(property="'.$campo['form'].'", type="'.$campo['typeForm'].'", example="'.$campo['label'].'")';
            }
        endforeach;
        if(count($arCampos) > 0){
             $strCampos = implode(",", $arCampos);
        }

        $strRequired = "";
        if(count($arRequired) > 0){
          $required = "required={".implode(",",$arRequired)."},";
          $strRequired = "required=true,";
        }

       $texto .= $s.'*      @OA\RequestBody('.$strRequired.$l;
       $texto .= $s.'*          @OA\JsonContent('.$required.$l;
       $texto .= $s.'*          '.$strCampos.$l;
       $texto .= $s.'*          )
         *      ),
         *  @OA\Response(response=201, description="'.$descricao.' cadastrado com sucesso", @OA\JsonContent()),
         *  @OA\Response(response=200, description="'.$descricao.' cadastrado com sucesso", @OA\JsonContent()),
         *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
         *  @OA\Response(response=400, description="Ocoreu um erro"),
         *  @OA\Response(response=404, description="Página não localizada"),
         * )
         */
    public function store(FormRequest $request)
    {
        return $this->service->save($request);
    }'.$l.$l;
        return $texto;
    }

    public static function update($arParams, $texto)
    {
        $rota = $arParams['rota'];
        $nome = $arParams['nome'];
        $descricao = $arParams['descricao'];
        $arRequired = [];
        $arCampos = [];
        $required = "";
        $s = "         ";
        $l = "
";
        $texto .= '
        /**
         * @OA\Put(
         * path="/'.$rota.'/{'.$rota.'}",
         * operationId="update'.$nome.'",
         * tags={"update'.$nome.'"},
         * tags={"'.$descricao.'"},
         * summary="Atualizar '.$descricao.'",'.$l;

        if($arParams['auth']){
            $texto .= $s.'* security={{"apiAuth":{}}},'.$l;
        }

        //GET
        $texto .= $s.'*   @OA\Parameter( description="ID", in="path", name="'.$rota.'",required=true,@OA\Schema(format="int64",type="integer",example=1)),'.$l;

        //POST
        $strCampos = "";
        foreach($arParams['campos'] as $campo):
           $form = '"' . $campo['form'] . '"';
           $example = '"' . $campo['form'] . '"';
            if($campo['visivel']){
                if($campo['required']) {
                    $arRequired[] = $form;
                }
                $arCampos[] = '@OA\Property(property="'.$campo['form'].'", type="'.$campo['typeForm'].'", example="'.$campo['label'].'")';
            }
        endforeach;
        if(count($arCampos) > 0){
             $strCampos = implode(",", $arCampos);
        }

        $strRequired = "";
        if(count($arRequired) > 0){
          $required = "required={".implode(",",$arRequired)."},";
          $strRequired = "required=true,";
        }

       $texto .= $s.'*      @OA\RequestBody('.$strRequired.$l;
       $texto .= $s.'*          @OA\JsonContent('.$required.$l;
       $texto .= $s.'*          '.$strCampos.$l;
       $texto .= $s.'*          )
        *      ),
         *  @OA\Response(response=201, description="'.$descricao.' atualizado com sucesso", @OA\JsonContent()),
         *  @OA\Response(response=200, description="'.$descricao.' atualizado com sucesso", @OA\JsonContent()),
         *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
         *  @OA\Response(response=400, description="Ocoreu um erro"),
         *  @OA\Response(response=404, description="Página não localizada"),
         * )
         */
        public function update(FormRequest $request, $teste)
        {
            return $this->service->save($request, $teste);
        }'.$l.$l;
        return $texto;
    }



    public static function delete($arParams, $texto)
    {
        $rota = $arParams['rota'];
        $nome = $arParams['nome'];
        $descricao = $arParams['descricao'];
        $s = "         ";
        $l = "
";
        $texto .= '
        /**
         * @OA\Delete(
         * path="/'.$rota.'/{'.$rota.'}",
         * operationId="excluir'.$nome.'",
         * tags={"excluir'.$nome.'"},
         * tags={"'.$descricao.'"},
         * summary="Deletar '.$descricao.'",'.$l;

        if($arParams['auth']){
            $texto .= $s.'* security={{"apiAuth":{}}},'.$l;
        }

        $texto .= $s.'*     @OA\Parameter( description="ID", in="path", name="'.$rota.'", required=true, @OA\Schema( format="int64", type="integer", example=1)),
         *  @OA\Response(response=201, description="'.$descricao.' excluido com sucesso", @OA\JsonContent()),
         *  @OA\Response(response=200, description="'.$descricao.' excluido com sucesso", @OA\JsonContent()),
         *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
         *  @OA\Response(response=400, description="Ocoreu um erro"),
         *  @OA\Response(response=404, description="Página não localizada"),
         * )
         */
        public function destroy($teste)
        {
             return $this->service->delete($teste);
         }'.$l.$l;
        $texto .= "}";
        return $texto;
    }


    public static function exportar($nome,$texto)
    {

        $nameFile = "/app/Http/Controllers/".$nome."Controller.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
