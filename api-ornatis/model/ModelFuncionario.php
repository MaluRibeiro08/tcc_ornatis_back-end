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

                //login
                $this->_cod_funcionario = $_POST["cod_funcionario"] ?? $dados_funcionario->cod_funcionario;
                $this->_senha = $_POST["senha"] ?? $dados_funcionario->senha;

                $this->_hora_inicio = $_POST["hora_inicio"] ?? $dados_funcionario->hora_inicio;
                $this->_hora_termino = $_POST["hora_termino"] ?? $dados_funcionario->hora_termino;
                $this->_id_dia_semana = $_POST["id_dia_semana"] ?? $dados_funcionario->id_dia_semana;

                break;
            
            default:
            
                $this->_id_funcionario = $_GET["id_funcionario"] ?? $dados_funcionario->id_funcionario;

                break;

        }

        $this->_conexao = $conexao;
        
    }

    public function createFuncionario()
    {

        $sql = "INSERT INTO tbl_funcionario (nome_funcionario, foto_perfil, id_empresa)
        VALUES (?, ?, ?)";

        $extensao = pathinfo($this->_foto_perfil["name"], PATHINFO_EXTENSION);

        $fotoPerfil = md5(microtime()) . ".$extensao";
        move_uploaded_file($_FILES["imagem_perfil"]["tmp_name"], "../../upload/imagem_perfil_salao/$fotoPerfil");

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_nome_funcionario);
        $stm->bindValue(2, $fotoPerfil);
        $stm->bindValue(3, $this->_id_empresa);
        $stm->execute();

        $sql = "";

    }

}
