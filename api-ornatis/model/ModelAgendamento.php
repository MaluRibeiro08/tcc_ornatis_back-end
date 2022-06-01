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

    private $_preco;
    private $_taxa_unica_cancelamento;

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

                $this->_preco = $_GET["preco"] ?? $this->_dados_agendamento->preco ?? null;
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
        
        WHERE id_consumidor = ? AND confirmado = 0 AND cancelado = 0";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_consumidor);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAgendamentoAdm()
    {
        $sql = "SELECT tbl_agendamento.id_agendamento,
                tbl_agendamento.data_agendamento,
                tbl_agendamento.hora_inicio,
                tbl_agendamento.hora_fim,
                tbl_agendamento.observacoes,
                tbl_agendamento.id_tipo_atendimento,
                tbl_servico.nome_servico,
                tbl_consumidor.nome_consumidor,
                tbl_consumidor.telefone,
                tbl_funcionario.nome_funcionario,
                tbl_forma_pagamento.forma_pagamento
                
                FROM tbl_agendamento
                
                inner join tbl_servico
                on tbl_agendamento.id_servico = tbl_servico.id_servico
                
                inner join tbl_consumidor
                on tbl_agendamento.id_consumidor = tbl_consumidor.id_consumidor
                
                inner join tbl_funcionario
                on tbl_agendamento.id_funcionario = tbl_funcionario.id_funcionario
                
                inner join tbl_forma_pagamento
                on tbl_agendamento.id_forma_pagamento = tbl_forma_pagamento.id_forma_pagamento
                
                WHERE tbl_funcionario.id_empresa = ? 
                AND tbl_agendamento.confirmado = 0
                AND tbl_agendamento.cancelado = 0";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAgendamentosFuncionario()
    {
        $sql = "SELECT tbl_agendamento.id_agendamento,
                tbl_agendamento.data_agendamento,
                tbl_agendamento.hora_inicio,
                tbl_agendamento.hora_fim,
                tbl_agendamento.observacoes,
                tbl_agendamento.id_tipo_atendimento,
                tbl_servico.nome_servico,
                tbl_consumidor.nome_consumidor,
                tbl_consumidor.telefone,
                tbl_funcionario.nome_funcionario,
                tbl_forma_pagamento.forma_pagamento
                
                FROM tbl_agendamento
                
                inner join tbl_servico
                on tbl_agendamento.id_servico = tbl_servico.id_servico
                
                inner join tbl_consumidor
                on tbl_agendamento.id_consumidor = tbl_consumidor.id_consumidor
                
                inner join tbl_funcionario
                on tbl_agendamento.id_funcionario = tbl_funcionario.id_funcionario
                
                inner join tbl_forma_pagamento
                on tbl_agendamento.id_forma_pagamento = tbl_forma_pagamento.id_forma_pagamento
                
                WHERE tbl_funcionario.id_funcionario = ? 
                AND tbl_agendamento.confirmado = 0
                AND tbl_agendamento.cancelado = 0";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAgendamentosCalendario()
    {
        $sql = "SELECT tbl_agendamento.id_agendamento,
                tbl_agendamento.data_agendamento, 
                tbl_agendamento.hora_inicio,
                tbl_agendamento.hora_fim
                FROM tbl_agendamento
                
                inner join tbl_funcionario
                on tbl_agendamento.id_funcionario = tbl_funcionario.id_funcionario
                
                WHERE tbl_funcionario.id_empresa = ? 
                AND tbl_agendamento.confirmado = 0
                AND tbl_agendamento.cancelado = 0";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAgendamentosCalendarioFuncionario()
    {
        $sql = "SELECT tbl_agendamento.id_agendamento,
                tbl_agendamento.data_agendamento, 
                tbl_agendamento.hora_inicio,
                tbl_agendamento.hora_fim
                FROM tbl_agendamento
                
                WHERE id_funcionario = ? 
                AND confirmado = 0
                AND cancelado = 0";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAgendamentoPorId()
    {
        $sql = "SELECT tbl_agendamento.id_agendamento,
                tbl_agendamento.data_agendamento,
                tbl_agendamento.hora_inicio,
                tbl_agendamento.hora_fim,
                tbl_agendamento.observacoes,
                tbl_agendamento.id_tipo_atendimento,
                tbl_servico.nome_servico,
                tbl_consumidor.nome_consumidor,
                tbl_consumidor.telefone,
                tbl_funcionario.nome_funcionario,
                tbl_forma_pagamento.forma_pagamento
                
                FROM tbl_agendamento
                
                inner join tbl_servico
                on tbl_agendamento.id_servico = tbl_servico.id_servico
                
                inner join tbl_consumidor
                on tbl_agendamento.id_consumidor = tbl_consumidor.id_consumidor
                
                inner join tbl_funcionario
                on tbl_agendamento.id_funcionario = tbl_funcionario.id_funcionario
                
                inner join tbl_forma_pagamento
                on tbl_agendamento.id_forma_pagamento = tbl_forma_pagamento.id_forma_pagamento
                
                WHERE tbl_agendamento.id_agendamento = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_agendamento);
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
        $divida = false;

        $sql = "SELECT hora_inicio, data_agendamento 
                FROM tbl_agendamento 
                WHERE id_agendamento = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_agendamento);
        $stm->execute();

        $atendimento = $stm->fetchAll(\PDO::FETCH_ASSOC);

        //data e hora atuais
        $dateformat = "Y-m-d";
        $this->_data_cancelamento = date($dateformat);

        date_default_timezone_set('America/Sao_Paulo');

        $timeformat = "H:i";
        $this->_hora_cancelamento = date($timeformat);

        //cálculo de dias até o atendimento
        $data_inicio = new DateTime($this->_data_cancelamento);
        $data_fim = new DateTime($atendimento[0]["data_agendamento"]);

        // Resgata diferença entre as datas
        $dateInterval = $data_inicio->diff($data_fim);
        $dias = $dateInterval->days;

        //cálculo de horas até o atendimento
        $string1 = $this->_hora_cancelamento;
        $string2 = $atendimento[0]["hora_inicio"];
        list($h1, $m1, $s1) = explode(':', $string1);
        list($h2, $m2, $s2) = explode(':', $string2);

        $dateTime1 = new DateTime();
        $dateTime1->setTime($h1, $m1, $s1);

        $dateTime2 = new DateTime();
        $dateTime2->setTime($h2, $m2, $s2);

        $intervalo = $dateTime1->diff($dateTime2);
        $intervalString = $intervalo->format('%H:%i');
        list($h, $m) = explode(':', $intervalString);

        $hora = intval($h);
        $minuto = intval($m);

        if ($minuto > 30) {
            $hora = $hora + 1;
        }

        //cálculo com dias até o atendimento
        if ($dias > 0) {
            $hora = $hora + ($dias * 24);
        }

        //verificação de dívida
        $sql = "SELECT valor_acima_de_100, 
                porcentagem_sobre_valor_servico, 
                horas_tolerancia
                FROM tbl_taxa_cancelamento 
                WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);
        $stm->execute();

        $taxasCancelamento = $stm->fetchAll(\PDO::FETCH_ASSOC);

        if ($taxasCancelamento != null) {

            //criação de array de horas de tolerancia 
            $array_tolerancia_abaixo_de_100 = [];
            $array_tolerancia_acima_de_100 = [];
            foreach ($taxasCancelamento as $taxaCancelamento) {

                if ($taxaCancelamento["valor_acima_de_100"] == 0) {
                    $array_tolerancia_abaixo_de_100[] = $taxaCancelamento["horas_tolerancia"];
                } else {
                    $array_tolerancia_acima_de_100[] = $taxaCancelamento["horas_tolerancia"];
                }
            }

            foreach ($taxasCancelamento as $taxaCancelamento) {

                //verificar o valor do servico
                if ($taxaCancelamento["valor_acima_de_100"] == 0 && $this->_preco <= 100) {

                    if ($hora == $taxaCancelamento["hora_tolerancia"]) {

                        $valorDivida = number_format($taxaCancelamento["porcentagem_sobre_valor_servico"] * ($this->_preco / 100), 2);

                        $sql = "INSERT INTO tbl_divida (valor_divida, 
                        consumidor_paga_prestador, data_criacao, hora_criacao, 
                        id_agendamento, id_tipo_divida) 
                        VALUES (?, ?, ?, ?, ?, ?)";

                        $stm = $this->_conexao->prepare($sql);
                        $stm->bindValue(1, $valorDivida);
                        $stm->bindValue(2, 1);
                        $stm->bindValue(3, $this->_data_cancelamento);
                        $stm->bindValue(4, $this->_hora_cancelamento);
                        $stm->bindValue(5, $this->_id_agendamento);
                        $stm->bindValue(6, 1);
                        if($stm->execute()){
                            return;
                        }
                    
                    } 
                    // elseif ($hora < $taxaCancelamento["hora_tolerancia"]) {
                        


                    // }  else {
                    //     return $hora . 'hora n está no array';
                    // }

                    //verificar hora de tolerância
                    //calcular valor da divida


                } else {

                    if ($hora == $taxaCancelamento["hora_tolerancia"]) {

                        $valorDivida = number_format($taxaCancelamento["porcentagem_sobre_valor_servico"] * ($this->_preco / 100), 2);
                        return $valorDivida;
                    }
                }
            }
        } else {

            //cobrança de divida qdo há taxa única
            $sql = "SELECT taxa_unica_cancelamento 
                    FROM tbl_empresa 
                    WHERE id_empresa = ?";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_empresa);
            $stm->execute();

            $empresa = $stm->fetchAll(\PDO::FETCH_ASSOC);

            $this->_taxa_unica_cancelamento = $empresa[0]["taxa_unica_cancelamento"];

            if ($this->_taxa_unica_cancelamento != 0) {

                $sql = "INSERT INTO tbl_divida (valor_divida, 
                    consumidor_paga_prestador, data_criacao, hora_criacao, 
                    id_agendamento, id_tipo_divida) 
                    VALUES (?, ?, ?, ?, ?, ?)";

                $stm = $this->_conexao->prepare($sql);
                $stm->bindValue(1, $this->_taxa_unica_cancelamento);
                $stm->bindValue(2, 1);
                $stm->bindValue(3, $this->_data_cancelamento);
                $stm->bindValue(4, $this->_hora_cancelamento);
                $stm->bindValue(5, $this->_id_agendamento);
                $stm->bindValue(6, 1);
                $stm->execute();
            }
        }

        $sql = "INSERT INTO tbl_cancelamento (data_cancelamento, hora_cancelamento, id_agendamento)
        VALUES (?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_data_cancelamento);
        $stm->bindValue(2, $this->_hora_cancelamento);
        $stm->bindValue(3, $this->_id_agendamento);
        $stm->execute();

        $sql = "UPDATE tbl_agendamento SET
        cancelado = 1
        WHERE id_agendamento = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_agendamento);
        $stm->execute();


        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao cancelar agendamento";
        }
    }
}
