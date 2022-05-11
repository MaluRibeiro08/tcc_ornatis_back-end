<?php

class ControllerConsumidor
{

    private $_method;

    private $_flag;

    private $_model_consumidor;

    private $_id_consumidor;

    public function __construct($model_consumidor)
    {
        $this->_model_consumidor = $model_consumidor;

        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $this->_dados_requisicao->acao ?? $_POST["acao"] ?? null;
        $this->_id_consumidor =  $_GET["id_consumidor"] ?? $this->_dados_requisicao->id_consumidor ?? $_POST["id_consumidor"] ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':

                if ($this->_flag == "listarCoresCabelo") {
                    return $this->_model_consumidor->getCoresCabelo();
                } elseif ($this->_flag == "listarGeneros") {
                    return $this->_model_consumidor->getGeneros();
                }

                break;

            case 'POST':

                if ($this->_flag == "createConsumidor") {

                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];

                        if ($envio_form == "true") {
                            if (isset($_FILES["foto_perfil_consumidor"])) {
                                if ($_FILES["foto_perfil_consumidor"]["error"] == 4) {
                                    return ("chegou a req sem foto consumidor");
                                } else {
                                    return $this->_model_consumidor->updateConsumidor($this->_id_consumidor);
                                }
                            }
                        }
                    } else {

                        return $this->_id_consumidor = $this->_model_consumidor->createConsumidor();
                    }
                } elseif ($this->_flag == "updateConsumidor") {
                    
                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];

                        if ($envio_form == "true") {
                            if (isset($_FILES["foto_perfil_consumidor"])) {
                                if ($_FILES["foto_perfil_consumidor"]["error"] == 4) {
                                    return ("chegou a req sem foto consumidor");
                                } else {
                                    return $this->_model_consumidor->updateConsumidor($this->_id_consumidor);
                                }
                            }
                        }
                    } else {
                        return $this->_model_consumidor->updateConsumidor($this->_id_consumidor);
                    }
                }

                break;

            case 'DELETE':

                if ($this->_flag == "desabilitarConsumidor") {
                    return $this->_model_consumidor->desabilitarConsumidor();
                }

                break;

            default:
                # code...
                break;
        }
    }
}
