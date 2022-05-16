<?php

class ModelAgendamento
{

    private $_conexao;
    private $_method;

    private $_dados_agendamento;

    private $_id_agendamento;

    private $_id_forma_pagamento;
    private $_id_tipo_atendimento;
    private $_id_funcionario;
    private $_id_consumidor;
    private $_id_servico;

    private $_id_efetuacao_agendamento;

    private $_data_agendamento;
    private $_hora_inicio;
    private $_hora_fim;
    private $_observacoes;
    private $_confirmado;

    public function __construct($conexao)
    {
        
        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $this->_dados_agendamento  = json_decode($json);

        switch ($this->_method) {
            case 'POST':
                
                $this->_data_agendamento = $_POST["data_agendamento"] ?? $this->_dados_agendamento->data_agendamento ?? null;
                $this->_hora_inicio = $_POST["hora_inicio"] ?? $this->_dados_agendamento->hora_inicio ?? null;
                $this->_hora_fim = $_POST["hora_fim"] ?? $this->_dados_agendamento->hora_fim ?? null;
                $this->_observacoes = $_POST["observacoes"] ?? $this->_dados_agendamento->observacoes ?? null;
                $this->_confirmado = $_POST["confirmado"] ?? $this->_dados_agendamento->confirmado ?? null;
                
                break;
            
            default:

                $this->_id_agendamento = $_GET["id_agendamento"] ?? $this->_dados_agendamento->id_agendamento ?? null;

                $this->_id_consumidor = $_GET["id_consumidor"] ?? $this->_dados_agendamento->id_consumidor ?? null;
                $this->_id_forma_pagamento = $_GET["id_forma_pagamento"] ?? $this->_dados_agendamento->id_forma_pagamento ?? null;
                $this->_id_tipo_atendimento = $_GET["id_tipo_atendimento"] ?? $this->_dados_agendamento->id_tipo_atendimento ?? null;
                $this->_id_funcionario = $_GET["id_funcionario"] ?? $this->_dados_agendamento->id_funcionario ?? null;
                $this->_id_servico = $_GET["id_servico"] ?? $this->_dados_agendamento->id_servico ?? null;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function create()
    {
        $sql = "INSERT INTO tbl_agendamento (data_agendamento, hora_inicio, hora_fim, observacoes, )";

    }

    
}

?>