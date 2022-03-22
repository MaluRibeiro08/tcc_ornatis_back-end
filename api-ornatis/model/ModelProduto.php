<?php

class ModelProduto{

    private $_conexao;

    private $_idProduto;
    private $_nome;
    private $_descricao;
    private $_preco;
    private $_qtdeEstoque;
    private $_volume;
    private $_retirada;
    private $_entrega;
    private $_status;
    private $_desconto;

    //private $_idEmpresa;

    public function __construct($conexao)
    {
        
        //receber método depois

        $json = file_get_contents("php://input");
        $dadosProduto = json_decode($json);

        $this->_idProduto = $dadosProduto->idProduto ?? null;
        $this->_nome = $dadosProduto->nome ?? null;
        $this->_descricao = $dadosProduto->descricao ?? null;
        $this->_preco = $dadosProduto->preco ?? null;
        $this->_qtdeEstoque = $dadosProduto->qtdeEstoque ?? null;
        $this->_volume = $dadosProduto->volume ?? null;
        $this->_retirada = $dadosProduto->retirada ?? null;
        $this->_entrega = $dadosProduto->entrega ?? null;
        $this->_status = $dadosProduto->status ?? null;
        $this->_desconto = $dadosProduto->desconto ?? null;
        //$this->_idEmpresa = $dadosProduto->idEmpresa ?? null;
        
        $this->_conexao = $conexao;

    }

    public function findAll(){

        $sql = "SELECT * FROM tbl_produto";

        $stm = $this->_conexao->prepare($sql);

        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);

    }

}

?>