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
    private $_id_genero;
    private $_id_tipo_atendimento;

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

                //ID's
                $this->_id_servico = $_POST["id_servico"] ?? $this->_dados_servico->id_servico ?? null;

                $this->_id_funcionario = $_POST["id_funcionario"] ?? $this->_dados_servico->id_funcionario ?? null;
                $this->_id_empresa = $_POST["id_empresa"] ?? $this->_dados_servico->id_empresa ?? null;

                $this->_id_especialidade = $_POST["id_especialidade"] ?? $this->_dados_servico->id_especialidade ?? null;
                $this->_id_parte_corpo = $_POST["id_parte_corpo"] ?? $this->_dados_servico->id_parte_corpo ?? null;

                $this->_id_tipo_atendimento = $_POST["id_tipo_atendimento"] ?? $this->_dados_servico->id_tipo_atendimento ?? null;

                //campos
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


                break;
        }

        $this->_conexao = $conexao;
    }

    public function getDetalhesServico()
    {
        $sql = "SELECT tbl_servico.nome_servico,
        tbl_servico.preco,
        tbl_servico.desconto,
        tbl_servico.tempo_duracao,
        tbl_servico.habilitado,
        tbl_servico.intervalo,
        tbl_servico.imagem_servico,
        
        tbl_especialidade.nome_especialidade,
        
        tbl_partes_corpo.nome_parte_corpo,
        
        tbl_funcionario.nome_funcionario,
        
        tbl_genero.genero,
        
        tbl_tipo_atendimento.tipo_atendimento
        from tbl_servico
        
        inner join tbl_servico_especialidade_partescorpo
        on tbl_servico.id_servico = tbl_servico_especialidade_partescorpo.id_servico
        
        inner join tbl_especialidade_partes_corpo
        on tbl_servico_especialidade_partescorpo.id_especialidade_partes_corpo = tbl_especialidade_partes_corpo.id_especialidade_partes_corpo
        
        inner join tbl_especialidade
        on tbl_especialidade_partes_corpo.id_especialidade = tbl_especialidade.id_especialidade
        
        inner join tbl_partes_corpo
        on tbl_especialidade_partes_corpo.id_parte_corpo = tbl_partes_corpo.id_parte_corpo
        
        inner join tbl_servico_tipo_atendimento
        on tbl_servico.id_servico = tbl_servico_tipo_atendimento.id_servico
        
        inner join tbl_tipo_atendimento
        on tbl_servico_tipo_atendimento.id_tipo_atendimento = tbl_tipo_atendimento.id_tipo_atendimento
        
        inner join tbl_servico_genero
        on tbl_servico.id_servico = tbl_servico_genero.id_servico
        
        inner join tbl_genero
        on tbl_servico_genero.id_genero = tbl_genero.id_genero
        
        inner join tbl_servico_funcionario
        on tbl_servico.id_servico = tbl_servico_funcionario.id_servico
        
        inner join tbl_funcionario
        on tbl_servico_funcionario.id_funcionario = tbl_funcionario.id_funcionario
        
        WHERE tbl_servico.id_servico = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_servico);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getServicosEmpresaByCategoria()
    {
        $sql = "SELECT tbl_servico.nome_servico, 
                tbl_servico.preco, 
                tbl_servico.id_servico, 
                tbl_especialidade.nome_especialidade
                FROM tbl_servico
                
                inner join tbl_servico_especialidade_partescorpo
                on tbl_servico.id_servico = tbl_servico_especialidade_partescorpo.id_servico
                
                inner join tbl_especialidade_partes_corpo
                on tbl_servico_especialidade_partescorpo.id_especialidade_partes_corpo = tbl_especialidade_partes_corpo.id_especialidade_partes_corpo
                
                inner join tbl_especialidade
                on tbl_especialidade_partes_corpo.id_especialidade = tbl_especialidade.id_especialidade
                
                WHERE id_empresa = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_empresa);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEspecialidades()
    {
        $sql = "SELECT * from tbl_especialidade";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEspecialidadesPartesCorpo()
    {
        $sql = "SELECT 
                tbl_partes_corpo.nome_parte_corpo, 
                tbl_partes_corpo.id_parte_corpo 
                FROM tbl_partes_corpo

                inner join tbl_especialidade_partes_corpo
                on tbl_partes_corpo.id_parte_corpo = tbl_especialidade_partes_corpo.id_parte_corpo 
                
                inner join tbl_especialidade
                on tbl_especialidade_partes_corpo.id_especialidade = tbl_especialidade.id_especialidade
                
                WHERE tbl_especialidade.id_especialidade = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_especialidade);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** CREATE **/
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

            $this->_id_genero = $genero->id_genero;

            $sql = "INSERT INTO tbl_servico_genero (id_servico, id_genero)
            VALUES (?, ?)";
            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_servico);
            $stm->bindValue(2, $this->_id_genero);
            $stm->execute();
        }

        return "Success";
    }

    public function addTipoAtendimentoServico($tiposAtendimento, $idServicoRecebido)
    {
        $this->_id_servico = $idServicoRecebido;

        foreach ($tiposAtendimento as $tipoAtendimento) {

            $this->_id_tipo_atendimento = $tipoAtendimento->id_tipo_atendimento;

            $sql = "INSERT INTO tbl_servico_tipo_atendimento (id_servico, id_tipo_atendimento)
            VALUES (?, ?)";

            $stm = $this->_conexao->prepare($sql);
            $stm->bindValue(1, $this->_id_servico);
            $stm->bindValue(2, $this->_id_tipo_atendimento);
            $stm->execute();
        }

        return "Success";
    }

    /** DELETES - DESABILITAR **/

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

    public function removerFuncionarios()
    {
        $sql = "DELETE FROM tbl_servico_funcionario WHERE id_servico = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindvalue(1, $this->_id_servico);
        $stm->execute();

    }

    public function limparGeneros()
    {
        $sql = "DELETE FROM tbl_servico_genero WHERE id_servico = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindvalue(1, $this->_id_servico);
        $stm->execute();

    }

    public function limparTipoAtendimento()
    {
        $sql = "DELETE FROM tbl_servico_tipo_atendimento WHERE id_servico = ?";
        
        $stm = $this->_conexao->prepare($sql);
        $stm->bindvalue(1, $this->_id_servico);
        $stm->execute();

    }


    /** UPDATE **/

    public function updateServico($idServicoRecebido)
    {

        if (isset($_POST["envio_form"])) {

            $envio_form = $_POST["envio_form"];

            if ($envio_form == "true") {

                if ($_FILES["imagem_servico"]["error"] == 4) {
                    //não faz nada pq não veio img
                    return "estariamos fazendo nada porque não veio img";
                } else {
                    //selecionar imagem de perfil 
                    $sqlImg = "SELECT imagem_servico FROM tbl_servico WHERE id_servico = ?";

                    $stm = $this->_conexao->prepare($sqlImg);
                    $stm->bindValue(1, $idServicoRecebido);

                    $stm->execute();

                    $servico = $stm->fetchAll(\PDO::FETCH_ASSOC);

                    //exclusão da imagem antiga se tiver
                    if (isset($servico[0]["imagem_servico"])) {
                        if ($servico[0]["imagem_servico"] != null) {
                            unlink("../../../upload/imagem_servico/" . $servico[0]["imagem_servico"]);
                        }
                    }

                    //nova imagem
                    $nomeArquivo = $_FILES["imagem_servico"]["name"];

                    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
                    $novoNomeArquivo = md5(microtime()) . ".$extensao";


                    move_uploaded_file($_FILES["imagem_servico"]["tmp_name"], "../../../upload/imagem_servico/$novoNomeArquivo");

                    $sql = "UPDATE tbl_servico SET
                    imagem_servico = ? WHERE id_servico = ?";

                    $stm = $this->_conexao->prepare($sql);

                    $stm->bindvalue(1, $novoNomeArquivo);
                    $stm->bindvalue(2, $idServicoRecebido);

                    $stm->execute();

                    return "imagem do serviço salva com sucesso!";
                }
            }

        } else {

            $this->_id_servico = $idServicoRecebido;

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

    public function updateEspecialidadePartesCorpo()
    {

        $sql = "SELECT id_especialidade_partes_corpo 
        from tbl_especialidade_partes_corpo 
        WHERE id_especialidade = ? AND id_parte_corpo = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_id_especialidade);
        $stm->bindValue(2, $this->_id_parte_corpo);
        $stm->execute();

        $id = $stm->fetchAll(\PDO::FETCH_ASSOC);

        $idEspecialidadePartesCorpo = $id[0]["id_especialidade_partes_corpo"];

        $sql = "UPDATE tbl_servico_especialidade_partesCorpo SET 
        id_especialidade_partes_corpo = ?
        WHERE id_servico = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $idEspecialidadePartesCorpo);
        $stm->bindValue(2, $this->_id_servico);
        if ($stm->execute()) {
            return "Success";
        } else {
            return "Erro ao atualizar - updateEspecialidadePartesCorpo";
        }

    }

}
