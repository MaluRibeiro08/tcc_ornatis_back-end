<?php

class ControllerAdmServico
{

    private $_method;

    private $_model_servico;

    private $_id_servico;

    public function __construct($model_servico)
    {
        $this->_model_servico = $model_servico;
     
        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $this->_dados_requisicao->acao ?? $_POST["acao"] ?? null;
        $this->_id_servico =  $_GET["id_servico"] ?? $this->_dados_requisicao->id_servico ?? $_POST["id_servico"] ?? null;


    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':
                # code...
                break;
            
            case 'POST':

                if ($this->_flag == "createServico") {
                    $dados_servico = $this->_model_servico->createServico();

                    return $dados_servico;
                }

                break;

            case 'DELETE':
                # code...
                break;
            default:
                # code...
                break;
        }

    }

}

?>