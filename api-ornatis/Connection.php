<?php

class Connection{

    private $_dbHostName = "localhost";
    private $_dbName = "db_ornatis";
    private $_userName = "root";
    private $_dbPassword = "12345678";
    private $_conexao;

    public function __construct()
    {
        
        try {
            
            $this-> _conexao = new PDO("mysql:host =$this->_dbHostName;dbname=$this->_dbName", 
                                    $this->_userName,
                                    $this->_dbPassword
        );

            //configurações
            $this->_conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            
            echo "Erro de conexão:" . $e->getMessage();

        }

    }

    public function returnConnection(){
        return $this->_conexao;
    }

}

?>