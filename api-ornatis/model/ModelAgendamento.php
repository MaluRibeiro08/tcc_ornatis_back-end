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

                $this->_id_consumidor = $_POST["id_consumidor"] ?? $this->_dados_agendamento->id_consumidor ?? null;
                $this->_id_forma_pagamento = $_POST["id_forma_pagamento"] ?? $this->_dados_agendamento->id_forma_pagamento ?? null;
                $this->_id_tipo_atendimento = $_POST["id_tipo_atendimento"] ?? $this->_dados_agendamento->id_tipo_atendimento ?? null;
                $this->_id_funcionario = $_POST["id_funcionario"] ?? $this->_dados_agendamento->id_funcionario ?? null;
                $this->_id_servico = $_POST["id_servico"] ?? $this->_dados_agendamento->id_servico ?? null;

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

    public function createAgendamento()
    {
        $sql = "INSERT INTO tbl_agendamento (data_agendamento, 
        hora_inicio, hora_fim, observacoes, id_tipo_atendimento, 
        id_funcionario, id_consumidor, id_servico, id_forma_pagamento) VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_data_agendamento);
        $stm->bindValue(2, $this->_hora_inicio);
        $stm->bindValue(3, $this->_hora_fim);
        $stm->bindValue(4, $this->_observacoes);
        $stm->bindValue(5, $this->_id_tipo_atendimento);
        $stm->bindValue(6, $this->_id_funcionario);
        $stm->bindValue(7, $this->_id_consumidor);
        $stm->bindValue(8, $this->_id_servico);
        $stm->bindValue(9, $this->_id_forma_pagamento);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao cadastrar agendamento";
        }

    }
}
