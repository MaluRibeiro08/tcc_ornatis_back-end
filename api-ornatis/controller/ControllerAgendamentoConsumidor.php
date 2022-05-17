<?php

class ControllerAgendamentoConsumidor
{

    private $_method;

    private $_flag;
    private $_dados_requisicao;

    private $_model_agendamento;
    private $_model_servico;
    private $_model_funcionario;

    private $_array_funcionarios;

    private $_id_servico;


    public function __construct($model_agendamento, $model_servico, $model_funcionario)
    {
        $this->_model_agendamento = $model_agendamento;
        $this->_model_servico = $model_servico;
        $this->_model_funcionario = $model_funcionario;

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
                
                if ($this->_flag == "listarFuncionariosPorServico") {

                    $this->_array_funcionarios = $this->_model_funcionario->getFuncionariosPorServico($this->_id_servico);
                    $dados_funcionario["dias_trabalho"] = $this->_model_funcionario->getFuncionariosDiaTrabalho($this->_array_funcionarios);
                    $dados_funcionario["agendamentos"] = $this->_model_funcionario->getAgendamentosFuncionario($this->_array_funcionarios);

                    return $dados_funcionario;

                } elseif ($this->_flag == "listar") {
                    # code...
                }

                break;
            
            case 'POST':
                # code...
                break;

            default:
                # code...
                break;
        }

    }

}

?>