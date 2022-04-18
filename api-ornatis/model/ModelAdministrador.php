<?php

class ModelAdministrador
{

    private $_conexao;
    private $_method;

    //ATRIBUTOS DE ADMINISTRADOR
    private $_id_administrador;
    private $_cpf;
    private $_data_nascimento;
    private $_nome_adm;

    //ATRIBUTOS - LOGIN
    private $_email_adm;
    private $_senha_adm;

    private $_id_empresa;

    public function __construct($conexao)
    {
        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dados_admin = json_decode($json);
        // var_dump($dados_admin);

        switch ($this->_method) {
            case 'POST':

                $this->_id_administrador = $_POST["id_administrador"] ?? $dados_admin->id_administrador ?? null;
                $this->_cpf = $_POST["cpf"] ?? $dados_admin->cpf ?? null;
                $this->_data_nascimento = $_POST["data_nascimento"] ?? $dados_admin->data_nascimento ?? null;
                $this->_nome_adm = $_POST["nome_adm"] ?? $dados_admin->nome_adm ?? null;
                $this->_id_empresa = $_POST["id_empresa"] ?? $dados_admin->_id_empresa ?? null;

                $this->_email_adm = $_POST["email_adm"] ?? $dados_admin->email_adm ?? null;
                $this->_senha_adm = $_POST["senha_adm"] ?? $dados_admin->senha_adm ?? null;

                break;

            default:
                $this->_id_administrador = $_GET["id_administrador"] ?? $dados_admin->id_administrador ?? null;
                $this->_id_empresa =  $_GET["id_empresa"] ?? $dados_admin->id_empresa ?? null;
                // $this->_cpf = $dados_admin->cpf ?? null;
                // $this->_data_nascimento = $dados_admin->data_nascimento ?? null;
                // $this->_nome_adm = $dados_admin->nome_adm ?? null;
                // $this->_id_empresa = $dados_admin->_id_empresa ?? null;
                break;
        }



        $this->_conexao = $conexao;
    }

    public function findById()
    {

        //listagem

        $sql = "SELECT * FROM tbl_administrador WHERE id_administrador = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_administrador);

        if ($stm->execute()) {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return "deu errado";
        };
    }

    public function findByEmpresa()
    {
        //listagem

        $sql = "SELECT * FROM tbl_administrador WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        if ($stm->execute()) {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return "Erro ao buscar dados de administrador";
        };
    }

    public function findIdByEmpresa()
    {

        $sql = "SELECT id_administrador FROM tbl_administrador WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLogin(int $idAdministrador)
    {
        $sql = "SELECT
                tbl_login_adm.email_adm,
                tbl_login_adm.senha_adm
               
                FROM tbl_login_adm 
                WHERE id_administrador = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $idAdministrador);

        if ($stm->execute()) {
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return "Erro ao buscar dados de login de administrador";
        };
    }

    public function createAdministrador($idEmpresaRecebido)
    {

        $this->_id_empresa = $idEmpresaRecebido;

        $sql = "INSERT INTO tbl_administrador (cpf, data_nascimento, nome_adm, id_empresa) 
        VALUES (?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_cpf);
        $stm->bindValue(2, $this->_data_nascimento);
        $stm->bindValue(3, $this->_nome_adm);
        $stm->bindValue(4, $this->_id_empresa);
        $stm->execute();
        $this->_id_administrador = $this->_conexao->lastInsertId();

        $sql = "INSERT INTO tbl_login_adm (email_adm, senha_adm, id_administrador)
        VALUES (?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_email_adm);
        $stm->bindValue(2, $this->_senha_adm);
        $stm->bindValue(3, $this->_id_administrador);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao criar administrador";
        }
    }

    public function updateAdministrador($idEmpresaRecebido, $idAdministradorRecebido)
    {

        $sql = "UPDATE tbl_administrador SET
        cpf = ?,
        data_nascimento = ?,
        nome_adm = ? 
        WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_cpf);
        $stm->bindValue(2, $this->_data_nascimento);
        $stm->bindValue(3, $this->_nome_adm);
        $stm->bindValue(4, $idEmpresaRecebido);
        $stm->execute();


        $sql = "UPDATE tbl_login_adm SET
        email_adm = ?,
        senha_adm = ? 
        WHERE id_administrador = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_email_adm);
        $stm->bindValue(2, $this->_senha_adm);
        $stm->bindValue(3, $idAdministradorRecebido);

        if ($stm->execute()) {
            return "Dados alterados com sucesso!";
        }
    }
}
