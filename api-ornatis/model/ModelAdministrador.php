<?php

class ModelAdministrador{

    private $_conexao;

    private $_id_administrador;
    private $_cpf;
    private $_data_nascimento;
    private $_nome_adm;

    private $_id_empresa;

    public function __construct($conexao)
    {
        
        $json = file_get_contents("php://input");
        $dadosAdmin = json_decode($json);

        $this->_id_administrador = $dadosAdmin->id_administrador ?? null;
        $this->_cpf = $dadosAdmin->cpf ?? null;
        $this->_data_nascimento = $dadosAdmin->data_nascimento ?? null;
        $this->_nome_adm = $dadosAdmin->nome_adm ?? null;

        $this->_conexao = $conexao;
        
    }

    public function findById(){

        //listagem

        $sql = "SELECT * FROM tbl_administrador WHERE id_administrador = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_administrador);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function create(){

        //cadastro

        $sql = "INSERT INTO tbl_administrador (cpf, data_nascimento, nome_adm, id_empresa) 
        VALUES (?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_cpf);
        $stm->bindValue(2, $this->_data_nascimento);
        $stm->bindValue(1, $this->_nome_adm);
        $stm->bindValue(1, $this->_id_empresa);

        if ($stm->execute()) {
            return "Success";
            
        } else {
            return "Error";
        }
        

    }

}

?>