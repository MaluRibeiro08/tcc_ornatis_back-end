<?php

class ControllerAdmFuncionario
{

    private $_method;

    private $_model_funcionario;

    private $_id_funcionario;
    private $_array_dias_trabalho;

    private $_dados_requisicao;

    private $_flag;
    private $_id_empresa;

    public function __construct($model_funcionario)
    {

        $this->_model_funcionario = $model_funcionario;

        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $this->_dados_requisicao->acao ?? $_POST["acao"] ?? null;
        $this->_id_empresa =  $_GET["id_empresa"] ?? $this->_dados_requisicao->id_empresa ?? $_POST["id_empresa"] ?? null;

        //ID_FUNCIONARIO
        $this->_id_funcionario = $_GET["id_funcionario"] ?? $this->_dados_requisicao->id_funcionario ?? $_POST["id_funcionario"] ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':
                if ($this->_flag == "listarFuncionarios") {

                    $dados_funcionario = $this->_model_funcionario->getFuncionariosEmpresa();
                    return $dados_funcionario;
                } elseif ($this->_flag == "listarDetalhesFuncionario") {

                    $dados_funcionario["dados_funcionario"] = $this->_model_funcionario->getInformacoesFuncionario();
                    $dados_funcionario["dados_dias_trabalho"] = $this->_model_funcionario->getDiaTrabalho();

                    return $dados_funcionario;
                }
                break;

            case 'POST':
                if ($this->_flag == "createFuncionario") {

                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];
            
                        if ($envio_form == "true") {
                            if (isset($_FILES["foto_perfil"])) {
                                if ($_FILES["foto_perfil"]["error"] == 4) {
                                    return ("chegou a req sem foto funcionario");
                                } else {
                                    // return ("chegou a req com foto do funcionario");
                                    //atualiza a img
                                   return $this->_model_funcionario->updateFuncionarioAdm($this->_id_empresa);
                                }
                            }
                        }
                        
                    }
                    else
                    {
                        // return "Req fetch";
                        return $this->_model_funcionario->createFuncionario();
                        $this->_id_funcionario = $this->_model_funcionario->createFuncionario();

                        $this->_array_dias_trabalho = $this->_dados_requisicao->dados_dia_trabalho;
                        $resultado = $this->_model_funcionario->createDiaTrabalhoFuncionario($this->_array_dias_trabalho, $this->_id_funcionario);

                        return $this->_id_funcionario;
                    }
                    
                    
                } elseif ($this->_flag == "updateFuncionario") {
                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];
            
                        if ($envio_form == "true") {
                            if (isset($_FILES["foto_perfil"])) {
                                if ($_FILES["foto_perfil"]["error"] == 4) {
                                    return ("chegou a req sem foto funcionario");
                                } else {
                                    // return ("chegou a req com foto do funcionario");
                                    //atualiza a img
                                   return $this->_model_funcionario->updateFuncionarioAdm($this->_id_empresa);
                                }
                            }
                        }
                        
                    }
                    else
                    {
                        $this->_model_funcionario->updateFuncionarioAdm();

                        $this->_model_funcionario->limparDiasTrabalho();
                        $this->_array_dias_trabalho = $this->_dados_requisicao->dados_dia_trabalho;
                        $this->_model_funcionario->createDiaTrabalhoFuncionario($this->_array_dias_trabalho, $this->_id_funcionario);
                    }
                }
                break;

            case 'DELETE':
                if ($this->_flag == "desabilitarFuncionario") {
                    return $this->_model_funcionario->desabilitarFuncionario($this->_id_funcionario);
                }
                break;

            default:
                # code...
                break;
        }
    }
}
