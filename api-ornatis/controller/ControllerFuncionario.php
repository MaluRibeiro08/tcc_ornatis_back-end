<?php

class ControllerFuncionario
{

    private $_method;

    private $_flag;
    private $_dados_requisicao;

    private $_model_funcionario;

    private $_id_funcionario;

    public function __construct($model_funcionario)
    {
        $this->_model_funcionario = $model_funcionario;

        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $this->_dados_requisicao->acao ?? $_POST["acao"] ?? null;
        
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':
                if ($this->_flag == "carregarCalendario") {
                    return $this->_model_funcionario->getDiaTrabalho();
                }
                break;

            case 'POST':
                if ($this->_flag == "loginFuncionario") {
                    return $this->_model_funcionario->login();
                }
                break;
            
            default:
                # code...
                break;
        }

    }


}

?>