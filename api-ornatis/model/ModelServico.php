<?php

class ModelServico
{

    private $_conexao;
    private $_method;

    private $_id_servico;
    private $_id_empresa;
    private $_id_funcionario;

    private $_dados_servico;

    private $_nome_servico;
    private $_tempo_duracao;
    private $_intervalo;
    private $_valor_fixo;
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

                break;
            
            default:
                # code...
                break;
        }

        $this->_conexao = $conexao;

    }

}

?>