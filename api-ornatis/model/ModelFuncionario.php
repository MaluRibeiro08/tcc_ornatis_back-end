<?php

class ModelFuncionario
{

    private $_conexao;
    private $_method;

    private $_id_empresa;

    //ATRIBUTOS DE FUNCIONÁRIO
    private $_id_funcionario;

    private $_dados_funcionario;

    private $_nome_funcionario;
    private $_foto_perfil;

    //ATRIBUTOS DE LOGIN
    private $_cod_funcionario;
    private $_senha;

    //ATRIBUTOS DE DIA DE TRABALHO
    private $_hora_inicio;
    private $_hora_termino;
    private $_id_dia_semana;

    private $_token;

    public function __construct($conexao)
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $this->_dados_funcionario  = json_decode($json);

        switch ($this->_method) {
            case 'POST':

                $this->_id_funcionario = $_POST["id_funcionario"] ?? $this->_dados_funcionario->id_funcionario ?? null;
                $this->_nome_funcionario = $_POST["nome_funcionario"] ?? $this->_dados_funcionario->nome_funcionario ?? null;
                $this->_foto_perfil = $_FILES["foto_perfil"] ?? null;

                $this->_id_empresa = $_POST["id_empresa"] ?? $this->_dados_funcionario->id_empresa ?? null;

                //login
                $this->_cod_funcionario = $_POST["cod_funcionario"] ?? null;
                $this->_senha = $_POST["senha"] ?? $this->_dados_funcionario->senha ?? null;

                $this->_hora_inicio = $_POST["hora_inicio"] ?? $this->_dados_funcionario->hora_inicio ?? null;
                $this->_hora_termino = $_POST["hora_termino"] ?? $this->_dados_funcionario->hora_termino ?? null;
                $this->_id_dia_semana = $_POST["id_dia_semana"] ?? $this->_dados_funcionario->id_dia_semana ?? null;

                break;

            default:

                $this->_id_funcionario = $_GET["id_funcionario"] ?? $this->_dados_funcionario->id_funcionario ?? null;
                $this->_id_empresa = $_GET["id_empresa"] ?? $this->_dados_funcionario->id_empresa ?? null;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function getFuncionariosEmpresa()
    {

        $sql = "SELECT * from tbl_funcionario WHERE habilitado = 1 
        AND id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFuncionariosPorServico($idServico)
    {
        $sql = "SELECT tbl_funcionario.id_funcionario,
        tbl_funcionario.nome_funcionario,
        tbl_funcionario.foto_perfil
        
        FROM tbl_funcionario
        
        inner join tbl_servico_funcionario
        on tbl_funcionario.id_funcionario = tbl_servico_funcionario.id_funcionario
        
        inner join tbl_servico
        on tbl_servico_funcionario.id_servico = tbl_servico.id_servico
        
        WHERE tbl_servico_funcionario.id_servico = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $idServico);

        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFuncionariosDiaTrabalho($funcionarios)
    {

        foreach ($funcionarios as $funcionario) {

            $this->_id_funcionario = $funcionario["id_funcionario"];

            $sql = "SELECT hora_inicio, hora_termino, id_dia_semana, id_funcionario 
                    FROM tbl_dia_trabalho 
                    WHERE id_funcionario = ?";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_funcionario);

            $stm->execute();
            $array[] = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $array;
    }

    public function getAgendamentosFuncionario($funcionarios)
    {
        foreach ($funcionarios as $funcionario) {

            $this->_id_funcionario = $funcionario["id_funcionario"];

            $sql = "SELECT hora_inicio, hora_fim, data_agendamento, id_funcionario 
                    FROM tbl_agendamento 
                    WHERE id_funcionario = ? 
                    AND confirmado != 1";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_funcionario);

            $stm->execute();
            $array[] = $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $array;
    }

    public function getInformacoesFuncionario()
    {
        $sql = "SELECT tbl_funcionario.nome_funcionario, 
        tbl_funcionario.foto_perfil, tbl_login_funcionario.cod_funcionario, tbl_login_funcionario.senha
        FROM tbl_funcionario 
        
        inner join tbl_login_funcionario
        on tbl_funcionario.id_funcionario =  tbl_login_funcionario.id_funcionario
        WHERE tbl_funcionario.id_funcionario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);

        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDiaTrabalho()
    {

        $sql = "SELECT tbl_dia_trabalho.hora_inicio, 
		tbl_dia_trabalho.hora_termino, 
		tbl_dia_semana.dia_da_semana,
		tbl_dia_semana.id_dia_semana
        
		FROM tbl_dia_trabalho
			inner join tbl_dia_semana 
            on tbl_dia_trabalho.id_dia_semana = tbl_dia_semana.id_dia_semana
            
		WHERE id_funcionario = ?";


        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);

        $stm->execute();

        $trabalhos = $stm->fetchAll(\PDO::FETCH_ASSOC);
        $dias_trabalho = [];
        $verificacao = [];
        foreach ($trabalhos as $trabalho) {
            $dia_semana = $trabalho["id_dia_semana"];

            if ($dias_trabalho == null) {
                $dias_trabalho[$dia_semana][] = $trabalho;
            } else {
                $confimacao_criacao = false;
                foreach ($dias_trabalho as $dia_semana_que_tem_trabalho => $infos_trabalho) {

                    if ($dia_semana_que_tem_trabalho == $dia_semana) {
                        $dias_trabalho[$dia_semana_que_tem_trabalho][] = $trabalho;
                        $confimacao_criacao = true;
                    }
                }
                if ($confimacao_criacao == false) {
                    $dias_trabalho[$dia_semana][] = $trabalho;
                }
            }
        };
        // return $verificacao;
        return $dias_trabalho;
    }

    //** CREATE **

    public function createFuncionario()
    {
        $sql = "INSERT INTO tbl_funcionario (nome_funcionario, id_empresa)
            VALUES (?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_nome_funcionario);
        $stm->bindValue(2, $this->_id_empresa);
        $stm->execute();

        $idFuncionario = $this->_conexao->lastInsertId();

        $primeiroNomeFuncionario = strtok($this->_nome_funcionario, " ");
        $codigo = substr(uniqid(rand()), 0, 4);

        //verificação
        $sql = "SELECT cod_funcionario FROM tbl_login_funcionario";
        $stm = $this->_conexao->prepare($sql);
        $stm->execute();

        $cod_funcionarios = $stm->fetchAll(\PDO::FETCH_ASSOC);

        $this->_cod_funcionario = $primeiroNomeFuncionario . $codigo;

        foreach ($cod_funcionarios as $cod_funcionario) {

            if ($this->_cod_funcionario ==  $cod_funcionario["cod_funcionario"]) {

                $primeiroNomeFuncionario = strtok($this->_nome_funcionario, " ");
                $codigo = substr(uniqid(rand()), 0, 4);

                $this->_cod_funcionario = $primeiroNomeFuncionario . $codigo;
            }

        }

        $hash = password_hash($this->_senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tbl_login_funcionario (cod_funcionario, senha, id_funcionario)
            VALUES (?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_cod_funcionario);
        $stm->bindValue(2, $hash);
        $stm->bindValue(3, $idFuncionario);
        $stm->execute();

        return $idFuncionario;
    }

    public function createDiaTrabalhoFuncionario($diasTrabalho, $idFuncionarioRecebido)
    {

        foreach ($diasTrabalho as $diaTrabalho) {

            $this->_hora_inicio = $diaTrabalho->hora_inicio;
            $this->_hora_termino = $diaTrabalho->hora_termino;
            $this->_id_dia_semana = $diaTrabalho->id_dia_semana;
            $this->_id_funcionario = $idFuncionarioRecebido;

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

    //** DELETE **

    public function desabilitarFuncionario($idFuncionarioRecebido)
    {

        $this->_id_funcionario = $idFuncionarioRecebido;

        $sql = "UPDATE tbl_funcionario SET
        habilitado = 0
        WHERE id_funcionario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);

        if ($stm->execute()) {
            return "success";
        }
    }

    public function limparDiasTrabalho()
    {
        $sql = "DELETE FROM tbl_dia_trabalho WHERE id_funcionario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_funcionario);

        $stm->execute();
    }

    //** UPDATE **

    public function updateFuncionarioAdm()
    {
        if (isset($_POST["envio_form"])) {

            $envio_form = $_POST["envio_form"];

            if ($envio_form == "true") {

                if ($_FILES["foto_perfil"]["error"] == 4) {
                    //não faz nada pq não veio img
                    return "estariamos fazendo nada porque não veio img";
                } else {
                    //selecionar imagem de perfil 
                    $sqlImg = "SELECT foto_perfil FROM tbl_funcionario WHERE id_funcionario = ?";

                    $stm = $this->_conexao->prepare($sqlImg);
                    $stm->bindValue(1, $this->_id_funcionario);

                    $stm->execute();

                    $funcionario = $stm->fetchAll(\PDO::FETCH_ASSOC);

                    //exclusão da imagem antiga se tiver
                    if ($funcionario[0]["foto_perfil"] != null) {
                        unlink("../../../upload/foto_perfil_funcionario/" . $funcionario[0]["foto_perfil"]);
                    }

                    //nova imagem
                    $nomeArquivo = $_FILES["foto_perfil"]["name"];

                    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
                    $novoNomeArquivo = md5(microtime()) . ".$extensao";


                    move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], "../../../upload/foto_perfil_funcionario/$novoNomeArquivo");

                    $sql = "UPDATE tbl_funcionario SET
                    foto_perfil = ? WHERE id_funcionario = ? ";

                    $stm = $this->_conexao->prepare($sql);

                    $stm->bindvalue(1, $novoNomeArquivo);
                    $stm->bindvalue(2, $this->_id_funcionario);

                    $stm->execute();
                }
            }
        } else {

            $sql = "UPDATE tbl_funcionario SET 
            nome_funcionario = ?
            WHERE id_funcionario = ?";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_nome_funcionario);
            $stm->bindValue(2, $this->_id_funcionario);
            $stm->execute();
        }
    }

    public function updateLoginFuncionario()
    {

        $sql = "UPDATE tbl_login_funcionario SET
        cod_funcionario = ?,
        senha = ?
        WHERE id_funcionario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_cod_funcionario);
        $stm->bindValue(2, $this->_senha);
        $stm->bindValue(3, $this->_id_funcionario);
        $stm->execute();

        return "Dados atualizados com sucesso!";
    }

    public function login()
    {
        $this->_token = md5(uniqid(rand(), true));

        $sql = "SELECT * FROM tbl_login_funcionario WHERE cod_funcionario = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_cod_funcionario);
        $stm->execute();

        $login = $stm->fetchAll(\PDO::FETCH_ASSOC);

        if ($login == []) {
            return "Login incorreto";
        } else {
            if (password_verify($this->_senha, $login[0]["senha"])) {
                return $this->_token;
            } else {
                return "Login ou senha incorretos";
            }
        }

    }

}
