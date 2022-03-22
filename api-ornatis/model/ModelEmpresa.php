<?php

class ModelEmpresa
{

    private $_conexao;
    private $_method;

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


    public function __construct($conexao)
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dados_empresa  = json_decode($json);

        switch ($this->_method) {
            case 'POST':
                
                $this->_biografia = $_POST["biografia"];
                $this->_imagem_perfil = $_POST["imagem_perfil"];
                $this->_telefone = $_POST["telefone"];
                $this->_nome_fantasia = $_POST["nome_fantasia"];
                $this->_cnpj = $_POST["cnpj"];
                $this->_nome_usuario_instagram = $_POST["nome_usuario_instagram"];
                $this->_link_faceboook = $_POST["link_facebook"];
                $this->_intervalo_tempo_padrao_entre_servicos = $_POST["intervalo_tempo_padrao_entre_servicos"];
                $this->_observacoes_pagamento = $_POST["observacoes_pagamento"];

                break;

            default:

                $this->_id_empresa = $dados_empresa->id_empresa;
                // $this->_biografia = $dados_empresa->biografia;
                // //imagem
                // $this->_telefone = $dados_empresa->telefone;
                // $this->_nome_fantasia = $dados_empresa->nome_fantasia;
                // $this->_cnpj = $dados_empresa->cnpj;
                // $this->_nome_usuario_instagram = $dados_empresa->nome_usuario_instagram;
                // $this->_link_faceboook = $dados_empresa->link_facebook;
                // $this->_intervalo_tempo_padrao_entre_servicos = $dados_empresa->intervalo_tempo_padrao_entre_servicos;
                // $this->_observacoes_pagamento = $dados_empresa->observacoes_pagamento;

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

    public function create(){

        $sql = "INSERT INTO tbl_empresa (biografia, imagem_perfil,
        telefone, nome_fantasia, cnpj, 
        nome_usuario_instagram, link_facebook,
        intervalo_tempo_padrao_entre_servicos, observacoes_pagamento)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_biografia);
        $stm->bindValue(2, $this->_imagem_perfil);
        $stm->bindValue(3, $this->_telefone);
        $stm->bindValue(4, $this->_nome_fantasia);
        $stm->bindValue(5, $this->_cnpj);
        $stm->bindValue(6, $this->_nome_usuario_instagram);
        $stm->bindValue(7, $this->_link_faceboook);
        $stm->bindValue(8, $this->_intervalo_tempo_padrao_entre_servicos);
        $stm->bindValue(9, $this->_observacoes_pagamento);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Error";
        }

    }

    public function delete(){
        
    }
}
