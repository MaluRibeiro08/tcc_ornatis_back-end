<?php

class ControllerAdmServico
{

    private $_method;

    private $_model_servico;

    private $_id_servico;

    private $_dados_requisicao;
    private $_array_funcionarios;
    private $_array_genero;
    private $_array_tipos_atendimento;

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

                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];

                        if ($envio_form == "true") {

                            if (isset($_FILES["imagem_servico"])) {
                                if ($_FILES["imagem_servico"]["error"] == 4) {
                                    return ("chegou a req sem imagem de serviço");
                                } else {
                                    return $this->_model_servico->updateServico($this->_id_servico);
                                }
                            }
                        }
                        
                    } else {

                        $this->_id_servico = $this->_model_servico->createServico();
                        $dados_servico["dados_servico"] = $this->_model_servico->addEspecialidadePartesCorpo($this->_id_servico);

                        $this->_array_funcionarios = $this->_dados_requisicao->funcionarios;
                        $dados_servico["dados_servico_funcionarios"] = $this->_model_servico->addFuncionariosServico($this->_array_funcionarios, $this->_id_servico);

                        $this->_array_genero = $this->_dados_requisicao->generos;
                        $dados_servico["dados_servico_genero"] = $this->_model_servico->addGeneroServico($this->_array_genero, $this->_id_servico);

                        $this->_array_tipos_atendimento = $this->_dados_requisicao->tipos_atendimento;
                        $dados_servico["dados_servico_tipo_atendimento"] = $this->_model_servico->addTipoAtendimentoServico($this->_array_tipos_atendimento, $this->_id_servico);

                        return $dados_servico;
                    }
                }

                break;

            case 'DELETE':

                if ($this->_flag == "desabilitarServico") {

                    return $this->_model_servico->desabilitarServico();
                }


                break;
            default:
                # code...
                break;
        }
    }
}
