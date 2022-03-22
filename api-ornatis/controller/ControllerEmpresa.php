<?php

class ControllerEmpresa{

    private $_method;
    private $_model_empresa;
    private $_id_empresa;

    public function __construct($model)
    {

        $this->_model_empresa = $model;
        $this->_method = $_SERVER['REQUEST_METHOD'];

        $json = file_get_contents("php://input");
        $dados_empresa = json_decode($json);

        $this->_id_empresa = $dados_empresa->id_empresa ?? $_POST["id_empresa"] ?? null;

    }

    function router(){

        switch ($this->_method) {
            case 'GET':
                
                if (isset($this->_id_empresa)) {
                    return $this->_model_empresa->findById();
                }

                return $this->_model_empresa->findAll();
                break;
            
            case 'POST':

                if ($this->_id_empresa) {
                    return $this->_model_empresa->update();
                    break;
                } else {
                    return $this->_model_empresa->create();
                }

                break;

            case 'DELETE':
                echo $this->_id_empresa;
                return $this->_model_empresa->delete();
                break;

            default:
                # code...
                break;
        }

    }

}

?>