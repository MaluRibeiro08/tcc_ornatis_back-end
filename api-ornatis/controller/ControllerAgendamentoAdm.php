<?php

class ControllerAgendamentoAdm
{

    private $_method;

    private $_flag;

    private $_model_agendamento;
    private $_model_empresa;

    private $_id_agendamento;

    public function __construct($model_agendamento, $model_empresa)
    {
        $this->_model_agendamento = $model_agendamento;
        $this->_model_empresa = $model_empresa;

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
                
                if ($this->_flag == "listarAgendamentos") {
                    return $this->_model_agendamento->getAgendamentoAdm();
                } elseif ($this->_flag == "listarAgendamentosCalendario") {
                    return $this->_model_agendamento->getAgendamentosCalendario();
                } elseif ($this->_flag == "listarAgendamentoPorId") {
                    return $this->_model_agendamento->getAgendamentoPorId();
                } elseif ($this->_flag == "funcionamentoSalao") {
                    return $this->_model_empresa->getFuncionamento();
                }

                break;
            
            case 'DELETE':
                
                if ($this->_flag == "cancelarAgendamento") {
                    return $this->_model_agendamento->cancelarAgendamento();
                }

                break;

            default:
                # code...
                break;
        }

    }

}

?>