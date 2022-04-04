<?php

class Connection{

    private $_dbHostname = "127.0.0.1";
    private $_dbName = "db_ornatis";
    private $_dbUsername = "root";
    private $_dbPassword = "12345678";
    //private $_dbPassword = "bcd127";
    private $_conexao;

    public function __construct() {

        try {
            
            $this-> _conexao = new PDO("mysql:host=$this->_dbHostname;
                                         dbname=$this->_dbName", 
                                         $this->_dbUsername,
                                         $this->_dbPassword);

        	$this->_conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // echo "Conectado com sucesso. <br />";
            // print_r($this->_con);

       } catch(PDOException $e) {
            
           echo "Connection failed: <br />";
           print_r($e->getMessage());

       }

    }

    public function returnConnection() {
        return $this->_conexao;
    }

}

?>