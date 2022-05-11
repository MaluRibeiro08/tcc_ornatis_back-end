<?php

class ModelConsumidor
{
    private $_conexao;
    private $_method;

    private $_id_consumidor;
    private $_dados_consumidor;

    private $_id_genero;
    private $_id_cor_cabelo;
    private $_id_comprimento_cabelo;
    private $_id_tipo_cabelo;

    private $_nome_consumidor;
    private $_data_nascimento;
    private $_cpf_consumidor;
    private $_telefone;

    private $_email_consumidor;
    private $_senha_consumidor;

    public function __construct($conexao)
    {
        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $this->_dados_consumidor  = json_decode($json);

        switch ($this->_method) {
            case 'POST':

                $this->_id_consumidor = $_POST["id_consumidor"] ?? $this->_dados_consumidor->id_consumidor ?? null;

                $this->_nome_consumidor = $_POST["nome_consumidor"] ?? $this->_dados_consumidor->nome_consumidor ?? null;
                $this->_data_nascimento = $_POST["data_nascimento"] ?? $this->_dados_consumidor->data_nascimento ?? null;
                $this->_cpf_consumidor = $_POST["cpf_consumidor"] ?? $this->_dados_consumidor->cpf_consumidor ?? null;
                $this->_telefone = $_POST["telefone"] ?? $this->_dados_consumidor->telefone ?? null;

                $this->_email_consumidor = $_POST["email_consumidor"] ?? $this->_dados_consumidor->email_consumidor ?? null;
                $this->_senha_consumidor = $_POST["senha_consumidor"] ?? $this->_dados_consumidor->senha_consumidor ?? null;

                $this->_id_genero = $_POST["id_genero"] ?? $this->_dados_consumidor->id_genero ?? null;
                $this->_id_cor_cabelo = $_POST["id_cor_cabelo"] ?? $this->_dados_consumidor->id_cor_cabelo ?? null;
                $this->_id_comprimento_cabelo = $_POST["id_comprimento_cabelo"] ?? $this->_dados_consumidor->id_comprimento_cabelo ?? null;
                $this->_id_tipo_cabelo = $_POST["id_tipo_cabelo"] ?? $this->_dados_consumidor->id_tipo_cabelo ?? null;

                break;

            default:

                $this->_id_consumidor = $_GET["id_consumidor"] ?? $this->_dados_consumidor->id_consumidor ?? null;
                $this->_id_genero = $_GET["id_genero"] ?? $this->_dados_consumidor->id_genero ?? null;
                $this->_id_cor_cabelo = $_GET["id_cor_cabelo"] ?? $this->_dados_consumidor->id_cor_cabelo ?? null;
                $this->_id_comprimento_cabelo = $_GET["id_comprimento_cabelo"] ?? $this->_dados_consumidor->id_comprimento_cabelo ?? null;
                $this->_id_tipo_cabelo = $_GET["id_tipo_cabelo"] ?? $this->_dados_consumidor->id_tipo_cabelo ?? null;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function getCoresCabelo()
    {
        $sql = "SELECT * from tbl_cor_cabelo";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGeneros()
    {
        $sql = "SELECT * FROM tbl_genero";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createConsumidor()
    {

        // realizar verificação de login com o email dps
        $sql = "SELECT email_consumidor FROM tbl_login_consumidor";
        $stm = $this->_conexao->prepare($sql);
        $stm->execute();
        $emails = $stm->fetchAll(\PDO::FETCH_ASSOC);

        $emailValido = true;

        foreach ($emails as $email) {

            if ($this->_email_consumidor == $email["email_consumidor"]) {
                $emailValido = false;
            }
        }

        if ($emailValido) {

            $sql = "INSERT INTO tbl_consumidor (nome_consumidor, data_nascimento, cpf_consumidor, 
            telefone, id_genero, id_cor_cabelo, id_tipo_cabelo, id_comprimento_cabelo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_nome_consumidor);
            $stm->bindValue(2, $this->_data_nascimento);
            $stm->bindValue(3, $this->_cpf_consumidor);
            $stm->bindValue(4, $this->_telefone);
            $stm->bindValue(5, $this->_id_genero);
            $stm->bindValue(6, $this->_id_cor_cabelo);
            $stm->bindValue(7, $this->_id_tipo_cabelo);
            $stm->bindValue(8, $this->_id_comprimento_cabelo);
            $stm->execute();

            $this->_id_consumidor = $this->_conexao->lastInsertId();

            $sql = "INSERT INTO tbl_login_consumidor (id_consumidor, email_consumidor, senha_consumidor)
            VALUES (?, ?, ?)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_consumidor);
            $stm->bindValue(2, $this->_email_consumidor);
            $stm->bindValue(3, $this->_senha_consumidor);

            if ($stm->execute()) {
                return $this->_id_consumidor;
            } else {
                return "Erro no cadastro de consumidor";
            }
        } else {
            return "Email já cadastrado";
        }
    }

    public function desabilitarConsumidor()
    {
        $sql = "UPDATE tbl_consumidor SET
        habilitado = 0 
        WHERE id_consumidor = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_consumidor);

        if ($stm->execute()) {
            return "Success";
        }
    }

    public function updateConsumidor($idConsumidorRecebido)
    {
        $this->_id_consumidor = $idConsumidorRecebido;

        if (isset($_POST["envio_form"])) {

            $envio_form = $_POST["envio_form"];

            if ($envio_form == "true") {

                if ($_FILES["foto_perfil_consumidor"]["error"] == 4) {
                    //não faz nada pq não veio img
                    return "estariamos fazendo nada porque não veio img";
                } else {
                    //selecionar imagem de perfil 
                    $sqlImg = "SELECT foto_perfil_consumidor FROM tbl_consumidor WHERE id_consumidor = ?";

                    $stm = $this->_conexao->prepare($sqlImg);
                    $stm->bindValue(1, $this->_id_consumidor);

                    $stm->execute();

                    $consumidor = $stm->fetchAll(\PDO::FETCH_ASSOC);

                    //exclusão da imagem antiga se tiver
                    if ($consumidor[0]["foto_perfil_consumidor"] != null) {
                        unlink("../../upload/foto_perfil_consumidor/" . $consumidor[0]["foto_perfil_consumidor"]);
                    }

                    //nova imagem
                    $nomeArquivo = $_FILES["foto_perfil_consumidor"]["name"];

                    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
                    $novoNomeArquivo = md5(microtime()) . ".$extensao";


                    move_uploaded_file($_FILES["foto_perfil_consumidor"]["tmp_name"], "../../upload/foto_perfil_consumidor/$novoNomeArquivo");

                    $sql = "UPDATE tbl_consumidor SET
                    foto_perfil_consumidor = ? WHERE id_consumidor = ? ";

                    $stm = $this->_conexao->prepare($sql);

                    $stm->bindvalue(1, $novoNomeArquivo);
                    $stm->bindvalue(2, $this->_id_consumidor);

                    $stm->execute();
                }
            }
        } else {

            // realizar verificação de login com o email dps
            $sql = "SELECT email_consumidor, id_consumidor FROM tbl_login_consumidor";
            $stm = $this->_conexao->prepare($sql);
            $stm->execute();
            $emails = $stm->fetchAll(\PDO::FETCH_ASSOC);

            $emailValido = true;

            foreach ($emails as $email) {

                if ($this->_email_consumidor == $email["email_consumidor"] && $this->_id_consumidor != $email["id_consumidor"]) {
                    $emailValido = false;
                }
            }

            if ($emailValido) {


                $sql = "UPDATE tbl_consumidor SET
                nome_consumidor = ?,
                data_nascimento = ?,
                cpf_consumidor = ?,
                telefone = ?,
                id_genero = ?,
                id_cor_cabelo = ?,
                id_tipo_cabelo = ?,
                id_comprimento_cabelo = ?
                WHERE id_consumidor = ?";

                $stm = $this->_conexao->prepare($sql);
                $stm->bindValue(1, $this->_nome_consumidor);
                $stm->bindValue(2, $this->_data_nascimento);
                $stm->bindValue(3, $this->_cpf_consumidor);
                $stm->bindValue(4, $this->_telefone);
                $stm->bindValue(5, $this->_id_genero);
                $stm->bindValue(6, $this->_id_cor_cabelo);
                $stm->bindValue(7, $this->_id_tipo_cabelo);
                $stm->bindValue(8, $this->_id_comprimento_cabelo);
                $stm->bindvalue(9, $this->_id_consumidor);

                $stm->execute();

                $sql = "UPDATE tbl_login_consumidor SET
                email_consumidor = ?,
                senha_consumidor = ?
                WHERE id_consumidor = ?";

                $stm = $this->_conexao->prepare($sql);
                $stm->bindValue(1, $this->_email_consumidor);
                $stm->bindValue(2, $this->_senha_consumidor);
                $stm->bindValue(3, $this->_id_consumidor);

                if ($stm->execute()) {
                    return "Success";
                }
            } else {
                return "Email já cadastrado";
            }
        }
    }
}
