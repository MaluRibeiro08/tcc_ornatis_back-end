<?php

class ControllerContaAdministradora{

    private $_method;
    private $_model_admin;
    private $_model_empresa;
    private $_id_administrador;
    private $_id_empresa;
    private $_flag;

    public function __construct($model_administrador, $model_empresa)
    {
        
        $this->_model_admin = $model_administrador;
        $this->_model_empresa = $model_empresa;
        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $dados_requisiscao = json_decode($json);
        $this->_flag = $dados_requisiscao->flag ?? $_POST["flag"] ?? null;;

        //ID_EMPRESA
        $this->_id_empresa = $dados_requisiscao->id_empresa ?? $_POST["id_empresa"] ?? null;

        //ID_ADM
        $this->_id_administrador = $dados_requisiscao->id_administrador ?? $_POST["id_administrador"] ?? null; 

    }

    function router(){

        switch ($this->_method) {
            case 'GET':
                if($this->_id_empresa != null)
                {
                    //buscando os dados do adm
                    $dados_administrador["dados_administrador"]= $this->_model_admin->findByEmpresa();

                    //buscando os dados da empresa
                    //$dados_empresa["dados_empresa"] = $this->_model_empresa->getInformacoesContaEmpresa();
                    $dados_empresa["dados_empresa"] = $this->_model_empresa->getInformacoesEmpresa();
                    $dados_empresa["dados_endereco_empresa"] = $this->_model_empresa->getEnderecoEmpresa();
                    $dados_empresa["dados_funcionamento"] = $this->_model_empresa->getFuncionamento();
                    $dados_empresa["dados_pagamento"] = $this->_model_empresa->getFormasPagamento();
                    $dados_empresa["taxa_cancelamento_empresa"] = $this->_model_empresa->getTaxasCancelamento();

                    //dados de login
                    $_id_administrador = $dados_administrador["dados_administrador"][0]["id_administrador"];
                    $dados_login["dados_login"] = $this->_model_admin->getLogin($_id_administrador);

                    return (array_merge($dados_administrador,$dados_empresa, $dados_login));
                }
                else
                {
                    return "Deu erro no router da controllerContaAdm";
                };
                
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