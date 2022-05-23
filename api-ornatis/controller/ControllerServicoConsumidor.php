<?php

class ControllerServicoConsumidor
{

    private $_method;

    private $_model_servico;

    private $_id_servico;

    private $_id_empresa;

    private $_dados_requisicao;

    public function __construct($model_servico)
    {
        $this->_model_servico = $model_servico;

        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $this->_dados_requisicao->acao ?? $_POST["acao"] ?? null;
        $this->_id_servico =  $_GET["id_servico"] ?? $this->_dados_requisicao->id_servico ?? $_POST["id_servico"] ?? null;

        $this->_id_empresa =  $_GET["id_empresa"] ?? $this->_dados_requisicao->id_empresa ?? $_POST["id_empresa"] ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':

                
                if ($this->_id_empresa != null && $this->_flag == "listarServicosPorEmpresa") {

                    return $this->_model_servico->getServicosPorEmpresa();
                } elseif ($this->_id_empresa != null && $this->_flag == "listarDetalhesServico") {

                    $dados_servico["dados_servico"] = $this->_model_servico->getDetalhesServico();
                    $dados_servico["dados_servico_especialidade"] = $this->_model_servico->getDetalhesServicoEspecialidade();
                    $dados_servico["dados_servico_funcionarios"] = $this->_model_servico->getDetalhesServicoFuncionarios();
                    $dados_servico["dados_servico_generos"] = $this->_model_servico->getDetalhesServicoGeneros();
                    $dados_servico["dados_servico_tipo_atendimento"] = $this->_model_servico->getDetalhesServicoTipoAtendimento();

                    return $dados_servico;
                } elseif ($this->_flag == "listarServicosPorEspecialidade") {
                    return $this->_model_servico->getServicoPorEspecialidade();
                } elseif ($this->_flag == "buscarServico") {
                    return $this->_model_servico->getServicoPorPesquisa();
                } 

                break;

            default:
                # code...
                break;
        }
    }
}
