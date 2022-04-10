<?php

class ModelEmpresa
{

    private $_conexao;
    private $_method;
    private $_flag;

    // ATRIBUTOS DE EMPRESA 
    private $_id_empresa;
    private $_biografia;
    private $_imagem_perfil;
    private $_telefone;
    private $_nome_fantasia;
    private $_cnpj;
    private $_nome_usuario_instagram;
    private $_link_faceboook;
    private $_intervalo_tempo_padrao_entre_servicos;
    private $_observacoes_pagamento;

    // ATRIBUTOS - ENDEREÇO EMPRESA
    private $_rua_empresa;
    private $_bairro_empresa;
    private $_numero_rua_empresa;
    private $_complemento_endereco;
    private $_cep;
    private $_id_cidade;

    // ATRIBUTOS DE DIA DE FUNCIONAMENTO
    private $_hora_inicio;
    private $_hora_termino;
    private $_id_dia_semana;

    // ATRIBUTOS DE FORMA DE PAGAMENTO
    private $_id_forma_pagamento;

    // ATRIBUTOS DE TAXA DE CANCELAMENTO
    private $_valor_acima_de_100;
    private $_porcentagem_sobre_valor_servico;
    private $_horas_tolerancia;

    // ATRIBUTO DE IMAGEM DO SALÃO
    private $_imagem_salao;

    public function __construct($conexao)
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dados_empresa  = json_decode($json);

        switch ($this->_method) {
            case 'POST':

                $this->_flag = $_POST["flag"] ?? null;

                // empresa
                $this->_id_empresa = $_POST["id_empresa"] ?? null;
                $this->_biografia = $_POST["biografia"] ?? null;
                $this->_imagem_perfil = $_POST["imagem_perfil"] ?? null;
                $this->_telefone = $_POST["telefone"] ?? null;
                $this->_nome_fantasia = $_POST["nome_fantasia"] ?? null;
                $this->_cnpj = $_POST["cnpj"] ?? null;
                $this->_intervalo_tempo_padrao_entre_servicos = $_POST["intervalo_tempo_padrao_entre_servicos"] ?? null;
                $this->_observacoes_pagamento = $_POST["observacoes_pagamento"] ?? null;
                $this->_taxa_unica_cancelamento = $_POST["taxa_unica_cancelamento"] ?? null;

                // $this->_nome_usuario_instagram = $_POST["nome_usuario_instagram"] ?? null;
                // $this->_link_faceboook = $_POST["link_facebook"] ?? null;

                // endereço
                $this->_rua_empresa = $_POST["rua"] ?? null;
                $this->_bairro_empresa = $_POST["bairro"] ?? null;
                $this->_numero_rua_empresa = $_POST["numero_rua"] ?? null;
                $this->_complemento_endereco = $_POST["complemento"] ?? null;
                $this->_cep = $_POST["cep"] ?? null;
                $this->_id_cidade = $_POST["id_cidade"] ?? null;

                // dia de funcionamento
                $this->_hora_inicio = $_POST["hora_inicio"] ?? null;
                $this->_hora_termino = $_POST["hora_termino"] ?? null;
                $this->_id_dia_semana = $_POST["id_dia_semana"] ?? null;

                // forma de pagamento
                $this->_id_forma_pagamento = $_POST["id_forma_pagamento"] ?? null;

                // taxa de cancelamento
                $this->_valor_acima_de_100 = $_POST["valor_acima_de_100"] ?? null;
                $this->_porcentagem_sobre_valor_servico = $_POST["porcentagem_sobre_valor_servico"] ?? null;
                $this->_horas_tolerancia = $_POST["horas_tolerancia"] ?? null;

                // imagem
                $this->_imagem_salao = $_POST["imagem_salao"] ?? null;

                break;

            default:

                $this->_id_empresa =  $_GET["id_empresa"] ?? $dados_empresa->id_empresa ?? null;

                break;
        }


        $this->_conexao = $conexao;
    }

    public function findAll()
    {

        $sql = "SELECT * FROM tbl_empresa";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById()
    {

        $sql = "SELECT * FROM tbl_empresa WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    // public function getInformacoesContaEmpresa()
    // {
    //    //$sql = "SELECT * FROM tbl_empresa WHERE id_empresa = ?";


    //     $sql =
    //         "SELECT
    //         tbl_empresa.nome_fantasia,
    //         tbl_empresa.cnpj,
    //         tbl_empresa.telefone,
    //         tbl_empresa.biografia,
    //         tbl_empresa.intervalo_tempo_padrao_entre_servicos,
    //         tbl_empresa.taxa_unica_cancelamento,
    //         tbl_endereco_salao.cep,
    //         tbl_endereco_salao.bairro,
    //         tbl_endereco_salao.rua,
    //         tbl_endereco_salao.numero,
    //         tbl_endereco_salao.complemento,
    //         tbl_cidade.nome_cidade,
    //         tbl_estado.nome_estado,
    //         tbl_forma_pagamento.forma_pagamento,
    //         tbl_taxa_cancelamento.valor_acima_de_100,
    //         tbl_taxa_cancelamento.horas_tolerancia,
    //         tbl_taxa_cancelamento.porcentagem_sobre_valor_servico,
    //         tbl_dia_semana.dia_da_semana,
    //         tbl_dia_funcionamento.hora_inicio,
    //         tbl_dia_funcionamento.hora_termino

    //             from tbl_empresa 

    //             inner join tbl_endereco_salao
    //             on tbl_empresa.id_empresa = tbl_endereco_salao.id_empresa

    //             inner join tbl_cidade
    //             on tbl_endereco_salao.id_empresa = tbl_cidade.id_cidade

    //             inner join tbl_estado
    //             on tbl_cidade.id_estado = tbl_estado.id_estado

    //             inner join tbl_empresa_forma_pagamento
    //             on tbl_empresa.id_empresa = tbl_empresa_forma_pagamento.id_empresa

    //             inner join tbl_forma_pagamento
    //             on tbl_empresa_forma_pagamento.id_forma_pagamento = tbl_forma_pagamento.id_forma_pagamento

    //             inner join tbl_taxa_cancelamento
    //             on tbl_empresa.id_empresa = tbl_taxa_cancelamento.id_empresa

    //             inner join tbl_dia_funcionamento
    //             on tbl_empresa.id_empresa = tbl_dia_funcionamento.id_empresa

    //             inner join tbl_dia_semana
    //             on tbl_dia_funcionamento.id_dia_semana = tbl_dia_semana.id_dia_semana

    //             where tbl_empresa.id_empresa = ?";

    //     $stm = $this->_conexao->prepare($sql);
    //     $stm->bindValue(1, $this->_id_empresa);

    //     $stm->execute();

    //     return $stm->fetchAll(\PDO::FETCH_ASSOC);
    //}

    public function getInformacoesEmpresa()
    {
        $sql = " SELECT 
                tbl_empresa.nome_fantasia,
                tbl_empresa.cnpj,
                tbl_empresa.telefone,
                tbl_empresa.biografia,
                tbl_empresa.intervalo_tempo_padrao_entre_servicos,
                tbl_empresa.taxa_unica_cancelamento,
                tbl_empresa.imagem_perfil,
                tbl_empresa.nome_usuario_instagram,
                tbl_empresa.link_facebook
                

                FROM tbl_empresa 
                WHERE tbl_empresa.id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEnderecoEmpresa()
    {
        $sql = "SELECT 
                tbl_endereco_salao.cep,
                tbl_endereco_salao.bairro,
                tbl_endereco_salao.rua,
                tbl_endereco_salao.numero,
                tbl_endereco_salao.complemento,
                tbl_cidade.nome_cidade,
                tbl_estado.nome_estado

                FROM tbl_endereco_salao
                    inner join tbl_cidade
                    on tbl_endereco_salao.id_cidade = tbl_cidade.id_cidade
                    
                    inner join tbl_estado
                    on tbl_cidade.id_estado = tbl_estado.id_estado

                WHERE tbl_endereco_salao.id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFuncionamento()
    {

        $sql = "SELECT 
                tbl_dia_funcionamento.hora_inicio,
                tbl_dia_funcionamento.hora_termino,
                tbl_dia_semana.dia_da_semana

                FROM tbl_dia_funcionamento
                    inner join tbl_dia_semana
                    on tbl_dia_funcionamento.id_dia_semana = tbl_dia_semana.id_dia_semana
                    

                WHERE tbl_dia_funcionamento.id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        $funcionamentos = $stm->fetchAll(\PDO::FETCH_ASSOC);
        $dias_funcionamento = [];
        $verificacao = [];
        foreach ($funcionamentos as $funcionamento) {
            $dia_semana = $funcionamento["dia_da_semana"];

            if ($dias_funcionamento == null) {
                $dias_funcionamento[$dia_semana][] = $funcionamento;
            } else {
                $confimacao_criacao = false;
                foreach ($dias_funcionamento as $dia_semana_que_tem_funcionamento => $infos_funcionamento) {

                    if ($dia_semana_que_tem_funcionamento == $dia_semana) {
                        $dias_funcionamento[$dia_semana_que_tem_funcionamento][] = $funcionamento;
                        $confimacao_criacao = true;
                    }
                }
                if ($confimacao_criacao == false) {
                    $dias_funcionamento[$dia_semana][] = $funcionamento;
                }
            }
        };
        // return $verificacao;
        return $dias_funcionamento;
    }

    public function getInformacoesPagamento()
    {
        $sql = "SELECT 
                tbl_forma_pagamento.id_forma_pagamento,
                tbl_forma_pagamento.forma_pagamento,
                tbl_empresa.observacoes_pagamento

                FROM tbl_empresa_forma_pagamento
                    inner join tbl_forma_pagamento
                    on tbl_empresa_forma_pagamento.id_forma_pagamento = tbl_forma_pagamento.id_forma_pagamento

                    inner join tbl_empresa
                    on tbl_empresa_forma_pagamento.id_empresa = tbl_empresa.id_empresa

                WHERE tbl_empresa_forma_pagamento.id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        $formas_pagamento = $stm->fetchAll(\PDO::FETCH_ASSOC);

        if ($formas_pagamento == null) {
            return "Nenhuma forma de pagamento encontrada";
        } else {
            // return $formas_pagamento;
            $lista_formas_pagamento = [];
            foreach ($formas_pagamento as $forma_pagamento) {
                $lista_formas_pagamento["formas_aceitas"][$forma_pagamento["id_forma_pagamento"]] = $forma_pagamento["forma_pagamento"];
            };

            $lista_formas_pagamento["observacoes_pagamento"] = $formas_pagamento[0]["observacoes_pagamento"];
            return $lista_formas_pagamento;
        }
    }

    public function getTaxasCancelamento()
    {
        $sql = "SELECT 
                tbl_empresa.taxa_unica_cancelamento,
                tbl_taxa_cancelamento.id_taxa_cancelamento,
                tbl_taxa_cancelamento.valor_acima_de_100,
                tbl_taxa_cancelamento.horas_tolerancia,
                tbl_taxa_cancelamento.porcentagem_sobre_valor_servico

                FROM tbl_empresa
                INNER JOIN tbl_taxa_cancelamento
                ON tbl_empresa.id_empresa = tbl_taxa_cancelamento.id_empresa

                WHERE tbl_empresa.id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        $dados = $stm->fetchAll(\PDO::FETCH_ASSOC);
        $taxas = [];

        if ($dados[0]['taxa_unica_cancelamento'] == null) {
            foreach ($dados as $taxa) {
                $id_taxa =  $taxa["id_taxa_cancelamento"];
                $taxas[$id_taxa] = $taxa;
            }
        } else {
            $taxas["taxa_unica_cancelamento"] = $dados[0]["taxa_unica_cancelamento"];
        };

        return $taxas;
    }

    public function getImagensEstabelecimento()
    {
        $sql = " SELECT 
            tbl_imagem_espaco_salao.id_imagem_espaco_salao,
            tbl_imagem_espaco_salao.imagem_salao

            from tbl_imagem_espaco_salao 
            where tbl_imagem_espaco_salao.id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        $imagens = $stm->fetchAll(\PDO::FETCH_ASSOC);
        if ($imagens == null) {
            return "Nenhuma imagem de estabelecimento encontrada";
        } else {
            return $imagens;
        }
    }


    // CREATE CONTA ADM

    public function createEmpresa()
    {

        $sql = "INSERT INTO tbl_empresa (biografia, imagem_perfil,
         telefone, nome_fantasia, cnpj,
         intervalo_tempo_padrao_entre_servicos, observacoes_pagamento, taxa_unica_cancelamento)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_biografia);
        $stm->bindValue(2, $this->_imagem_perfil);
        $stm->bindValue(3, $this->_telefone);
        $stm->bindValue(4, $this->_nome_fantasia);
        $stm->bindValue(5, $this->_cnpj);
        $stm->bindValue(6, $this->_intervalo_tempo_padrao_entre_servicos);
        $stm->bindValue(7, $this->_observacoes_pagamento);
        $stm->bindValue(8, $this->_taxa_unica_cancelamento);

        if ($stm->execute()) {

            $dados_empresa["lastInsertId"] = $this->_conexao->lastInsertId();
            $dados_empresa["taxa_unica_cancelamento"] = $this->_taxa_unica_cancelamento;

            return $dados_empresa;
        } else {
            return "Erro ao criar empresa";
        }
    }

    public function createEnderecoEmpresa($idEmpresaRecebido)
    {
        $this->_id_empresa = $idEmpresaRecebido;

        $sql = "INSERT INTO tbl_endereco_salao (bairro, rua, numero, 
         complemento, cep, id_cidade, id_empresa) 
         VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_bairro_empresa);
        $stm->bindValue(2, $this->_rua_empresa);
        $stm->bindValue(3, $this->_numero_rua_empresa);
        $stm->bindValue(4, $this->_complemento_endereco);
        $stm->bindValue(5, $this->_cep);
        $stm->bindValue(6, $this->_id_cidade);
        $stm->bindValue(7, $this->_id_empresa);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao criar empresa - endereço";
        }
    }

    public function createFuncionamento($funcionamento, $idEmpresaRecebido)
    {

        /* estrutura array - dados_funcionamento->id (id_dia_semana)->hora_inicio, hora_termino */

        $this->_id_empresa = $idEmpresaRecebido;

        foreach ($funcionamento as $diaFuncionamento) {

            //recebendo valores dos atributos
            $this->_id_dia_semana = $diaFuncionamento["id_dia_semana"];
            $this->_hora_inicio = $diaFuncionamento["hora_inicio"];
            $this->_hora_termino = $diaFuncionamento["hora_termino"];

            $sql = "INSERT INTO tbl_dia_funcionamento (hora_inicio, hora_termino,
            id_dia_semana, id_empresa) 
            VALUES (?, ?, ?, ?)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_hora_inicio);
            $stm->bindValue(2, $this->_hora_termino);
            $stm->bindValue(3, $this->_id_dia_semana);
            $stm->bindValue(4, $idEmpresaRecebido);
            $stm->execute();
        }
    }

    public function createFormasPagamento($formasPagamento, $idEmpresaRecebido)
    {

        $this->_id_empresa = $idEmpresaRecebido;

        foreach ($formasPagamento as $formaPagamento) {

            $sql = " INSERT INTO tbl_empresa_forma_pagamento (id_empresa, id_forma_pagamento)
            VALUES ($idEmpresaRecebido, $formaPagamento)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_empresa);
            $stm->bindValue(2, $this->_id_forma_pagamento);

            $stm->execute();
        }
    }

    // public function createTaxasCancelamento($idEmpresaRecebido)
    // {

    //     $this->_id_empresa = $idEmpresaRecebido;

    //     $sql = "INSERT INTO tbl_taxa_cancelamento (valor_acima_de_100, porcentagem_sobre_valor_servico, 
    //      horas_tolerancia, id_empresa)
    //      VALUES (?, ?, ?, ?)";

    //     $stm = $this->_conexao->prepare($sql);
    //     $stm->bindValue(1, $this->_valor_acima_de_100);
    //     $stm->bindValue(2, $this->_porcentagem_sobre_valor_servico);
    //     $stm->bindValue(3, $this->_horas_tolerancia);
    //     $stm->bindValue(4, $this->_id_empresa);

    //     if ($stm->execute()) {
    //         return "Success";
    //     } else {
    //         return "Erro ao criar empresa - taxas de cancelamento";
    //     }
    // }

    public function createTaxasCancelamento($taxasCancelamento, $idEmpresaRecebido)
    {

        foreach ($taxasCancelamento as $taxaCancelamento) {

            $this->_valor_acima_de_100 = $taxaCancelamento["valor_acima_de_100"];
            $this->_porcentagem_sobre_valor_servico = $taxaCancelamento["porcentagem_sobre_valor_servico"];
            $this->_horas_tolerancia = $taxaCancelamento["horas_tolerancia"];

            $sql = "INSERT INTO tbl_taxa_cancelamento (valor_acima_de_100, porcentagem_sobre_valor_servico, 
            horas_tolerancia, id_empresa)
            VALUES (?, ?, ?, ?)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_valor_acima_de_100);
            $stm->bindValue(2, $this->_porcentagem_sobre_valor_servico);
            $stm->bindValue(3, $this->_horas_tolerancia);
            $stm->bindValue(4, $idEmpresaRecebido);
            $stm->execute();

        }
    }

    public function createImagensEstabelecimento($idEmpresaRecebido)
    {

        $this->_id_empresa = $idEmpresaRecebido;

        $sql = "INSERT INTO tbl_imagem_espaco_salao (imagem_salao, id_empresa)
         VALUES (?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_imagem_salao);
        $stm->bindValue(2, $this->_id_empresa);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao criar empresa - taxas de cancelamento";
        }
    }



    public function delete()
    {

        $sql = "DELETE FROM tbl_empresa WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);


        if ($stm->execute()) {
            return "Dados excluídos com sucesso!";
        }
    }

    public function update()
    {

        $sql = "UPDATE tbl_empresa SET
        biografia = ?,
        imagem_perfil = ?,
        telefone = ?,
        nome_fantasia = ?,
        cnpj = ?,
        intervalo_tempo_padrao_entre_servicos = ?,
        observacoes_pagamento = ?
        WHERE id_empresa = ?
        ";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindvalue(1, $this->_biografia);
        $stm->bindvalue(2, $this->_imagem_perfil);
        $stm->bindvalue(3, $this->_telefone);
        $stm->bindvalue(4, $this->_nome_fantasia);
        $stm->bindvalue(5, $this->_cnpj);
        $stm->bindvalue(6, $this->_intervalo_tempo_padrao_entre_servicos);
        $stm->bindvalue(7, $this->_observacoes_pagamento);
        $stm->bindvalue(8, $this->_id_empresa);

        if ($stm->execute()) {
            return "Dados alterados com sucesso!";
        }
    }

    public function updateRedesSociais()
    {

        $sql = "UPDATE tbl_empresa SET
        nome_usuario_instagram = ?,
        link_facebook = ?
        WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindvalue(1, $this->_nome_usuario_instagram);
        $stm->bindvalue(2, $this->_link_faceboook);
        $stm->bindvalue(3, $this->_id_empresa);


        if ($stm->execute()) {
            return "Dados alterados com sucesso!";
        }
    }
}
