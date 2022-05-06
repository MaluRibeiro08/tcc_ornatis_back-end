<?php

class ControllerConsumidor
{

    private $_method;

    private $_model_consumidor;

    private $_id_consumidor;

    public function __construct($model_consumidor)
    {
        $this->_model_consumidor = $model_consumidor;

        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $this->_dados_requisicao->acao ?? $_POST["acao"] ?? null;
        $this->_id_consumidor =  $_GET["id_consumidor"] ?? $this->_dados_requisicao->id_consumidor ?? $_POST["id_consumidor"] ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':
                # code...
                break;
            
            case 'POST':
                # code...
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