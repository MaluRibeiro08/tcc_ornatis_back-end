<?php

class ModelAgendamento
{

    private $_conexao;
    private $_method;

    private $_dados_agendamento;

    private $_id_agendamento;

    private $_id_empresa;
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

    private $_data_cancelamento;
    private $_hora_cancelamento;


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

                $this->_id_empresa = $_POST["id_empresa"] ?? $this->_dados_agendamento->id_empresa ?? null;
                $this->_id_consumidor = $_POST["id_consumidor"] ?? $this->_dados_agendamento->id_consumidor ?? null;
                $this->_id_forma_pagamento = $_POST["id_forma_pagamento"] ?? $this->_dados_agendamento->id_forma_pagamento ?? null;
                $this->_id_tipo_atendimento = $_POST["id_tipo_atendimento"] ?? $this->_dados_agendamento->id_tipo_atendimento ?? null;
                $this->_id_funcionario = $_POST["id_funcionario"] ?? $this->_dados_agendamento->id_funcionario ?? null;
                $this->_id_servico = $_POST["id_servico"] ?? $this->_dados_agendamento->id_servico ?? null;

                break;

            default:

                $this->_id_agendamento = $_GET["id_agendamento"] ?? $this->_dados_agendamento->id_agendamento ?? null;

                $this->_id_empresa = $_GET["id_empresa"] ?? $this->_dados_agendamento->id_empre_id_empresa ?? null;
                $this->_id_consumidor = $_GET["id_consumidor"] ?? $this->_dados_agendamento->id_consumidor ?? null;
                $this->_id_forma_pagamento = $_GET["id_forma_pagamento"] ?? $this->_dados_agendamento->id_forma_pagamento ?? null;
                $this->_id_tipo_atendimento = $_GET["id_tipo_atendimento"] ?? $this->_dados_agendamento->id_tipo_atendimento ?? null;
                $this->_id_funcionario = $_GET["id_funcionario"] ?? $this->_dados_agendamento->id_funcionario ?? null;
                $this->_id_servico = $_GET["id_servico"] ?? $this->_dados_agendamento->id_servico ?? null;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function getAgendamentosConsumidor()
    {
        $sql = "SELECT tbl_agendamento.data_agendamento, 
        tbl_agendamento.hora_inicio,
        tbl_servico.nome_servico,
        tbl_servico.preco,
        tbl_empresa.nome_fantasia
        FROM tbl_agendamento
        
        inner join tbl_servico 
        on tbl_agendamento.id_servico = tbl_servico.id_servico
        
        inner join tbl_funcionario
        on tbl_agendamento.id_funcionario = tbl_funcionario.id_funcionario
        
        inner join tbl_empresa
        on tbl_funcionario.id_empresa = tbl_empresa.id_empresa
        
        WHERE id_consumidor = ? AND confirmado = 0 ";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_consumidor);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDetalhesAgendamentosConsumidor()
    {
        $sql = "SELECT tbl_agendamento.*,
                tbl_funcionario.nome_funcionario,
                tbl_forma_pagamento.forma_pagamento,
                tbl_tipo_atendimento.tipo_atendimento,
                tbl_servico.nome_servico,
                tbl_empresa.nome_fantasia,
                tbl_endereco_salao.rua,
                tbl_endereco_salao.numero
                FROM tbl_agendamento 
                
                inner join tbl_funcionario
                on tbl_agendamento.id_funcionario = tbl_funcionario.id_funcionario
                
                inner join tbl_forma_pagamento
                on tbl_agendamento.id_forma_pagamento = tbl_forma_pagamento.id_forma_pagamento
                
                inner join tbl_tipo_atendimento
                on tbl_agendamento.id_tipo_atendimento = tbl_tipo_atendimento.id_tipo_atendimento
                
                inner join tbl_servico
                on tbl_agendamento.id_servico = tbl_servico.id_servico
                
                inner join tbl_empresa
                on tbl_funcionario.id_empresa = tbl_empresa.id_empresa
                
                inner join tbl_endereco_salao
                on tbl_empresa.id_empresa = tbl_endereco_salao.id_empresa
                
                WHERE id_agendamento = ? AND confirmado = 0";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_agendamento);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
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

    public function cancelarAgendamento()
    {
        $dateformat = "Y-m-d";
        $this->_data_cancelamento = date($dateformat);

        date_default_timezone_set('America/Sao_Paulo');
        $timeformat = "H:i";
        $this->_hora_cancelamento = date($timeformat);

        $sql = "INSERT INTO tbl_cancelamento (data_cancelamento, hora_cancelamento, id_agendamento)
        VALUES (?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_data_cancelamento);
        $stm->bindValue(2, $this->_hora_cancelamento);
        $stm->bindValue(3, $this->_id_agendamento);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao cadastrar agendamento";
        }

    }
}
