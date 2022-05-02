<?php

class ModelServico
{

    private $_conexao;
    private $_method;

    private $_id_servico;
    private $_id_empresa;
    private $_id_funcionario;
    private $_id_especialidade;
    private $_id_parte_corpo;

    private $_dados_servico;

    private $_nome_servico;
    private $_tempo_duracao;
    private $_desconto;
    private $_intervalo;
    private $_preco;
    private $_imagem_servico;
    private $_detalhes;
    private $_status;

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


                $this->_nome_servico = $_POST["nome_servico"] ?? $this->_dados_servico->nome_servico ?? null;
                $this->_tempo_duracao = $_POST["tempo_duracao"] ?? $this->_dados_servico->tempo_duracao ?? null;
                $this->_intervalo = $_POST["intervalo"] ?? $this->_dados_servico->intervalo ?? null;
                $this->_valor_fixo = $_POST["id_servico"] ?? $this->_dados_servico->valor_fixo ?? null;
                $this->_imagem_servico = $_FILES["imagem_servico"] ?? null;
                $this->_detalhes = $_POST["detalhes"] ?? $this->_dados_servico->detalhes ?? null;
                $this->_status = $_POST["status"] ?? $this->_dados_servico->status ?? null;

                break;

            default:

                $this->_id_servico = $_GET["id_servico"] ?? $this->_dados_servico->id_servico ?? null;

                $this->_id_funcionario = $_GET["id_funcionario"] ?? $this->_dados_servico->id_funcionario ?? null;
                $this->_id_empresa = $_GET["id_empresa"] ?? $this->_dados_servico->id_empresa ?? null;

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
            return "Success";
        } else {
            return "Erro ao criar serviço";
        }
    }


    public function desabilitarServico()
    {

        $sql = "UPDATE tbl_servico SET 
        habilitado = 0
        WHERE id_servico = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindvalue(1, $this->_id_servico);

        $stm->execute();
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
