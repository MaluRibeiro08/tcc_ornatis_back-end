<?php

class ControllerContaAdministradora
{

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

        $this->_flag =  $_GET["acao"] ?? $dados_requisiscao->acao ?? $_POST["acao"] ?? null;;

        //ID_EMPRESA
        $this->_id_empresa =  $_GET["id_empresa"] ?? $dados_requisiscao->id_empresa ?? $_POST["id_empresa"] ?? null;

        //ID_ADM
        $this->_id_administrador = $_GET["id_administrador"] ?? $dados_requisiscao->id_administrador ?? $_POST["id_administrador"] ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':
                if ($this->_id_empresa != null && $this->_flag == "carregarDadosConta") {

                    //buscando os dados do adm
                    $dados_administrador["dados_administrador"] = $this->_model_admin->findByEmpresa();

                    //buscando os dados da empresa
                    //$dados_empresa["dados_empresa"] = $this->_model_empresa->getInformacoesContaEmpresa();
                    $dados_empresa["dados_empresa"] = $this->_model_empresa->getInformacoesEmpresa();
                    $dados_empresa["dados_endereco_empresa"] = $this->_model_empresa->getEnderecoEmpresa();
                    $dados_empresa["dados_funcionamento"] = $this->_model_empresa->getFuncionamento();
                    $dados_empresa["dados_pagamento"] = $this->_model_empresa->getInformacoesPagamento();
                    $dados_empresa["taxa_cancelamento_empresa"] = $this->_model_empresa->getTaxasCancelamento();

                    //dados de login
                    $_id_administrador = $dados_administrador["dados_administrador"][0]["id_administrador"];
                    $dados_login["dados_login"] = $this->_model_admin->getLogin($_id_administrador);

                    return array_merge($dados_administrador, $dados_empresa, $dados_login);
                } else if ($this->_id_empresa != null && $this->_flag == 'carregarPerfil') {
                    //dados da empresa
                    //INFORMAÇÕES FALTANTES: tipo de atendimento (relacionado a servicos),  funcionários (relacionado a funcionários), 

                    //DADOS DO SALAO (nome, foto de perfil, biografia, contato, taxa única)
                    $dados_empresa["dados_empresa"] = $this->_model_empresa->getInformacoesEmpresa();
                    //IMAGENS DO ESTABELECIMENTO (nome, foto de perfil, biografia, contato, taxa única)
                    $dados_empresa["imagens_estabelecimento"] = $this->_model_empresa->getImagensEstabelecimento();
                    //LOCALIZACAO (endereco)
                    $dados_empresa["dados_endereco_empresa"] = $this->_model_empresa->getEnderecoEmpresa();
                    //FUNCIONAMENTO
                    $dados_empresa["dados_funcionamento"] = $this->_model_empresa->getFuncionamento();
                    //PAGAMENTO (formas aceitas, observacoes de pagamento)
                    $dados_empresa["dados_pagamento"] = $this->_model_empresa->getInformacoesPagamento();
                    //CANCELAMENTO (taxa unica ou taxas de cobrança)
                    $dados_empresa["taxa_cancelamento_empresa"] = $this->_model_empresa->getTaxasCancelamento();

                    return array_merge($dados_empresa);
                } else {
                    return "Não foi possível realizar ação! Verifique as informações de requeisição (ids, flags)";
                };

                break;

            case 'POST':

                $dados_empresa["dados_empresa"] = $this->_model_empresa->createEmpresa();
                $idEmpresaCriada = $dados_empresa["dados_empresa"]["lastInsertId"];

                $dados_empresa["dados_endereco_empresa"] = $this->_model_empresa->createEnderecoEmpresa($idEmpresaCriada);
                $dados_empresa["dados_funcionamento"] = $this->_model_empresa->createFuncionamento($idEmpresaCriada);
                $dados_empresa["dados_pagamento"] = $this->_model_empresa->createFormasPagamento($idEmpresaCriada);
                if ($dados_empresa["dados_empresa"]["taxa_unica_cancelamento"] == null) {
                    $dados_empresa["dados_taxas_cancelamento"] = $this->_model_empresa->createTaxasCancelamento($idEmpresaCriada);
                }
                $dados_empresa["dados_imagem_estabelecimento"] = $this->_model_empresa->createImagensEstabelecimento($idEmpresaCriada);

                $dados_administrador["dados_administrador"] = $this->_model_admin->createAdministrador($idEmpresaCriada);

                return array_merge($dados_empresa, $dados_administrador);

            case 'DELETE':
                return $this->_model_admin->delete();
                break;

            default:
                # code...
                break;
        }
    }
}
