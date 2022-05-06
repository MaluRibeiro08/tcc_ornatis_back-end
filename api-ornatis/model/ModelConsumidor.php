<?php

class ModelConsumidor
{
    private $_conexao;
    private $_method;

    private $_id_consumidor;
    private $_dados_consumidor;

    private $_id_genero;

    private $_nome_consumidor;
    private $_data_nascimento;
    private $_cpf_consumidor;
    private $_telefone;

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

                $this->_id_genero = $_POST["id_genero"] ?? $this->_dados_consumidor->id_genero ?? null;
                
                break;

            default:
                
                $this->_id_consumidor = $_GET["id_consumidor"] ?? $this->_dados_consumidor->id_consumidor ?? null;
                $this->_id_genero = $_GET["id_genero"] ?? $this->_dados_consumidor->id_genero ?? null;
                
                break;
        }

        $this->_conexao = $conexao;
    }
}
