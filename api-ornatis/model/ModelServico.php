<?php

class ModelServico
{

    private $_conexao;
    private $_method;

    private $_id_servico;

    private $_id_empresa;
    private $_id_funcionario;
    private $_id_especialidade_parte_corpo;
    private $_id_especialidade;
    private $_id_parte_corpo;
    private $_id_genero;

    private $_dados_servico;

    private $_nome_servico;
    private $_tempo_duracao;
    private $_desconto;
    private $_intervalo;
    private $_preco;
    private $_imagem_servico;
    private $_detalhes;

    public function __construct($conexao)
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $this->_dados_servico  = json_decode($json);

        switch ($this->_method) {
            case 'POST':

                $this->_id_servico = $_POST["id_servico"] ?? $this->_dados_servico->id_servico ?? null;

                $this->_id_funcionario = $_POST["id_funcionario"] ?? $this->_dados_servico->id_funcionario ?? null;
                $this->_id_empresa = $_POST["id_empresa"] ?? $this->_dados_servico->id_empresa ?? null;

                $this->_id_especialidade = $_POST["id_especialidade"] ?? $this->_dados_servico->id_especialidade ?? null;
                $this->_id_parte_corpo = $_POST["id_parte_corpo"] ?? $this->_dados_servico->id_parte_corpo ?? null;
                $this->_id_especialidade_parte_corpo = $_POST["id_especialidade_parte_corpo"] ?? $this->_dados_servico->id_especialidade_parte_corpo ?? null;

                $this->_nome_servico = $_POST["nome_servico"] ?? $this->_dados_servico->nome_servico ?? null;
                $this->_tempo_duracao = $_POST["tempo_duracao"] ?? $this->_dados_servico->tempo_duracao ?? null;
                $this->_desconto = $_POST["desconto"] ?? $this->_dados_servico->desconto ?? null;
                $this->_intervalo = $_POST["intervalo"] ?? $this->_dados_servico->intervalo ?? null;
                $this->_preco = $_POST["preco"] ?? $this->_dados_servico->preco ?? null;
                $this->_imagem_servico = $_FILES["imagem_servico"] ?? null;
                $this->_detalhes = $_POST["detalhes"] ?? $this->_dados_servico->detalhes ?? null;

                break;

            default:

                $this->_id_servico = $_GET["id_servico"] ?? $this->_dados_servico->id_servico ?? null;

                $this->_id_funcionario = $_GET["id_funcionario"] ?? $this->_dados_servico->id_funcionario ?? null;
                $this->_id_empresa = $_GET["id_empresa"] ?? $this->_dados_servico->id_empresa ?? null;

                $this->_id_especialidade = $_GET["id_especialidade"] ?? $this->_dados_servico->id_especialidade ?? null;
                $this->_id_parte_corpo = $_GET["id_parte_corpo"] ?? $this->_dados_servico->id_parte_corpo ?? null;
                $this->_id_especialidade_parte_corpo = $_GET["id_especialidade_parte_corpo"] ?? $this->_dados_servico->id_especialidade_parte_corpo ?? null;

                break;
        }

        $this->_conexao = $conexao;
    }

    public function createServico()
    {

        $sql = "INSERT INTO tbl_servico (nome_servico, tempo_duracao, desconto, intervalo, preco, detalhes, id_empresa)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindvalue(1, $this->_nome_servico);
        $stm->bindvalue(2, $this->_tempo_duracao);
        $stm->bindvalue(3, $this->_desconto);
        $stm->bindvalue(4, $this->_intervalo);
        $stm->bindvalue(5, $this->_preco);
        $stm->bindvalue(6, $this->_detalhes);
        $stm->bindvalue(7, $this->_id_empresa);

        if ($stm->execute()) {
            return $this->_conexao->lastInsertId();
        } else {
            return "Erro ao criar serviço";
        }
    }

    public function addEspecialidadePartesCorpo($idServicoRecebido)
    {

        $this->_id_servico = $idServicoRecebido;

        $sql = "SELECT id_especialidade_partes_corpo 
        from tbl_especialidade_partes_corpo 
        WHERE id_especialidade = ? AND id_parte_corpo = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_especialidade);
        $stm->bindValue(2, $this->_id_parte_corpo);
        $stm->execute();

        $id = $stm->fetchAll(\PDO::FETCH_ASSOC);

        $idEspecialidadePartesCorpo = $id[0]["id_especialidade_partes_corpo"];

        $sql = "INSERT INTO tbl_servico_especialidade_partesCorpo (id_servico, id_especialidade_partes_corpo)
        VALUES (?, ?)";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_servico);
        $stm->bindValue(2, $idEspecialidadePartesCorpo);
        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao criar serviço - addEspecialidadePartesCorpo";
        }
    }

    public function addFuncionariosServico($funcionarios, $idServicoRecebido)
    {

        $this->_id_servico = $idServicoRecebido;

        foreach ($funcionarios as $funcionario) {

            $this->_id_funcionario = $funcionario->id_funcionario;

            $sql = "INSERT INTO tbl_servico_funcionario (id_servico, id_funcionario)
            VALUES (?, ?)";
            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_servico);
            $stm->bindValue(2, $this->_id_funcionario);
            $stm->execute();

        }

        return "Success";

    }

    public function addGeneroServico($generos, $idServicoRecebido)
    {

        $this->_id_servico = $idServicoRecebido;

        foreach ($generos as $genero) {
            
            $this->_id_genero = $genero->_id_genero;

            $sql = "INSERT INTO tbl_servico_empresa (id_servico, id_genero)
            VALUES (?, ?)";
            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_servico);
            $stm->bindValue(2, $this->_id_genero);
            $stm->execute();

        }

        return "Success";


    }


    public function desabilitarServico()
    {

        $sql = "UPDATE tbl_servico SET 
        habilitado = 0
        WHERE id_servico = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindvalue(1, $this->_id_servico);

        if ($stm->execute()) {
            return "Success";
        }
    }

    public function updateServico()
    {

        $sql = "UPDATE tbl_servico SET
        nome_servico = ?,
        tempo_duracao = ?,
        desconto = ?,
        intervalo = ?,
        preco = ?,
        detalhes = ?
        WHERE id_servico = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindvalue(1, $this->_nome_servico);
        $stm->bindvalue(2, $this->_tempo_duracao);
        $stm->bindvalue(3, $this->_desconto);
        $stm->bindvalue(4, $this->_intervalo);
        $stm->bindvalue(5, $this->_preco);
        $stm->bindvalue(6, $this->_detalhes);
        $stm->bindvalue(7, $this->_id_servico);

        if ($stm->execute()) {
            return "Success";
        }
    }
}
