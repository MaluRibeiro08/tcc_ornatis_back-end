<?php

class ModelFuncionario
{

    private $_conexao;
    private $_method;

    private $_id_empresa;

    //ATRIBUTOS DE FUNCIONÁRIO
    private $_id_funcionario;
    private $_nome_funcionario;
    private $_foto_perfil;

    //ATRIBUTOS DE LOGIN
    private $_cod_funcionario;
    private $_senha;

    //ATRIBUTOS DE DIA DE TRABALHO
    private $_hora_inicio;
    private $_hora_termino;
    private $_id_dia_semana;

    public function __construct($conexao)
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dados_funcionario  = json_decode($json);

        switch ($this->_method) {
            case 'POST':

                $this->_id_funcionario = $_POST["id_funcionario"] ?? $dados_funcionario->id_funcionario;
                $this->_nome_funcionario = $_POST["nome_funcionario"] ?? $dados_funcionario->nome_funcionario;
                $this->_foto_perfil = $_FILES["foto_perfil"] ?? null;

                $this->_id_empresa = $_POST["id_empresa"] ?? $dados_funcionario->id_empresa;

                //login
                // $this->_cod_funcionario = $_POST["cod_funcionario"] ?? $dados_funcionario->cod_funcionario;
                $this->_senha = $_POST["senha"] ?? $dados_funcionario->senha;

                $this->_hora_inicio = $_POST["hora_inicio"] ?? $dados_funcionario->hora_inicio;
                $this->_hora_termino = $_POST["hora_termino"] ?? $dados_funcionario->hora_termino;
                $this->_id_dia_semana = $_POST["id_dia_semana"] ?? $dados_funcionario->id_dia_semana;

                break;

            default:

                $this->_id_funcionario = $_GET["id_funcionario"] ?? $dados_funcionario->id_funcionario;
                $this->_id_empresa = $_GET["id_empresa"] ?? $dados_funcionario->id_empresa;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function getFuncionariosEmpresa()
    {
            
        $sql = "SELECT * from tbl_funcionario WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function getInformacoesFuncionarios()
    {

        $sql = "";

    }

    public function createFuncionario()
    {

        // if ("condition") {

        //     $sql = "INSERT INTO tbl_funcionario (foto_perfil) 
        //     VALUES (?)";

        //     $extensao = pathinfo($this->_foto_perfil["name"], PATHINFO_EXTENSION);

        //     $fotoPerfil = md5(microtime()) . ".$extensao";
        //     move_uploaded_file($_FILES["imagem_perfil"]["tmp_name"], "../../upload/imagem_perfil_salao/$fotoPerfil");

        //     $stm = $this->_conexao->prepare($sql);
        //     $stm->bindValue(1, $this->_nome_funcionario);
        //     $stm->bindValue(2, $fotoPerfil);
        //     $stm->bindValue(3, $this->_id_empresa);
        //     $stm->execute();
        // } else {
        //     # code...
        // }

        $sql = "INSERT INTO tbl_funcionario (nome_funcionario, id_empresa)
        VALUES (?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_nome_funcionario);
        $stm->bindValue(2, $this->_id_empresa);
        $stm->execute();

        $idFuncionario = $this->_conexao->lastInsertId();

        $primeiroNomeFuncionario = strtok($this->_nome_funcionario, " ");
        $codigo = substr(md5($primeiroNomeFuncionario), 0, 3);

        $this->_cod_funcionario = $primeiroNomeFuncionario . $codigo;

        $sql = "INSERT INTO tbl_login_funcionario (cod_funcionario, senha, id_funcionario)
        VALUES (?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_cod_funcionario);
        $stm->bindValue(2, $this->_senha);
        $stm->bindValue(3, $idFuncionario);

        if ($stm->execute()) {
            return "Create funcionario completo";
        } else {
            return "Erro ao criar funcionário";
        }
    }

    public function createDiaTrabalhoFuncionario($diasTrabalho, $idFuncionarioRecebido)
    {

        foreach ($diasTrabalho as $diaTrabalho) {

            // $this->_hora_inicio = $diaTrabalho->hora_inicio;
            // $this->_hora_termino = $diaTrabalho->hora_termino;
            // $this->_id_dia_semana = $diaTrabalho->id_dia_semana;
            // $this->_id_funcionario = $idFuncionarioRecebido;

            $sql = "INSERT INTO tbl_dia_trabalho (hora_inicio, hora_termino, 
            id_dia_semana, id_funcionario)
            VALUES (?, ?, ?, ?)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_hora_inicio);
            $stm->bindValue(2, $this->_hora_termino);
            $stm->bindValue(3, $this->_id_dia_semana);
            $stm->bindValue(4, $this->_id_funcionario);

            $stm->execute();
        }

        return "Success";
    }

    //DELETE

    public function limparDiasTrabalho()
    {
        $sql = "DELETE FROM tbl_dia_trabalho WHERE id_funcionario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);

        $stm->execute();

    }

    //UPDATE

    public function updateFuncionario()
    {

        $sql = "UPDATE tbl_funcionario SET 
        nome_funcionario = ?
        WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_nome_funcionario);
        $stm->bindValue(2, $this->_id_empresa);
        $stm->execute();
    }

    public function updateLoginFuncionario()
    {

        $sql = "UPDATE tbl_login_funcionario SET
        cod_funcionario = ?,
        senha = ?
        WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_cod_funcionario);
        $stm->bindValue(2, $this->_senha);
        $stm->bindValue(3, $this->_id_empresa);
        $stm->execute();

        return "Dados atualizados com sucesso!";

    }

}
