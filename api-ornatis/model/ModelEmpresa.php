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

    public function __construct($conexao)
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dados_empresa  = json_decode($json);

        switch ($this->_method) {
            case 'POST':

                $this->_flag = $_POST["flag"] ?? null;

                $this->_id_empresa = $_POST["id_empresa"] ?? null;
                $this->_biografia = $_POST["biografia"] ?? null;
                $this->_imagem_perfil = $_POST["imagem_perfil"] ?? null;
                $this->_telefone = $_POST["telefone"] ?? null;
                $this->_nome_fantasia = $_POST["nome_fantasia"] ?? null;
                $this->_cnpj = $_POST["cnpj"] ?? null;
                $this->_intervalo_tempo_padrao_entre_servicos = $_POST["intervalo_tempo_padrao_entre_servicos"] ?? null;
                $this->_observacoes_pagamento = $_POST["observacoes_pagamento"] ?? null;

                $this->_nome_usuario_instagram = $_POST["nome_usuario_instagram"] ?? null;
                $this->_link_faceboook = $_POST["link_facebook"] ?? null;

                break;

            default:

                $this->_id_empresa = $dados_empresa->id_empresa ?? null;

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

    public function create()
    {

        $sql = "INSERT INTO tbl_empresa (biografia, imagem_perfil,
        telefone, nome_fantasia, cnpj,
        intervalo_tempo_padrao_entre_servicos, observacoes_pagamento)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_biografia);
        $stm->bindValue(2, $this->_imagem_perfil);
        $stm->bindValue(3, $this->_telefone);
        $stm->bindValue(4, $this->_nome_fantasia);
        $stm->bindValue(5, $this->_cnpj);
        $stm->bindValue(6, $this->_intervalo_tempo_padrao_entre_servicos);
        $stm->bindValue(7, $this->_observacoes_pagamento);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Error";
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
