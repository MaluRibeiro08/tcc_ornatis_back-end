<?php

class ControllerContaAdministradora{

    private $_method;
    private $_model_admin;
    private $_model_empresa;
    private $_id_administrador;

    public function __construct($model_administrador, $model_empresa)
    {
        
        $this->_model_admin = $model_administrador;
        $this->_model_empresa = $model_empresa;
        $this->_method = $_SERVER['REQUEST_METHOD'];

        $this->_id_administrador = $_POST["id_administrador"] ?? null; 

    }

    function router(){

        switch ($this->_method) {
            case 'GET':
                
                return $this->_model_admin->findById();
                break;

            case 'POST':

                if ($this->_id_administrador) {
                    return $this->_model_admin->update();
                    break;
                } else {
                    return $this->_model_admin->create();
                    break;
                }

            case 'DELETE':
                return $this->_model_admin->delete();
                break;

            default:
                # code...
                break;
        }

    }

}

?>