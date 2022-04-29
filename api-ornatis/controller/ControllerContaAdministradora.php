<?php

class ControllerContaAdministradora
{

    private $_method;

    private $_model_admin;
    private $_model_empresa;

    private $_id_administrador;
    private $_id_empresa;

    private $_array_funcionamento;
    private $_array_forma_pagamento;
    private $_array_taxas_cancelamento;
    private $_array_imagens_espaco_salao;

    private $_dados_requisicao;

    private $_flag;

    public function __construct($model_administrador, $model_empresa)
    {

        $this->_model_admin = $model_administrador;
        $this->_model_empresa = $model_empresa;
        $this->_method = $_SERVER['REQUEST_METHOD'];

        //PEGANDO DADOS DA REQ
        $json = file_get_contents("php://input");
        $dados_requisicao = json_decode($json);
        $this->_dados_requisicao = json_decode($json);

        $this->_flag =  $_GET["acao"] ?? $dados_requisicao->acao ?? $_POST["acao"] ?? null;

        //ID_EMPRESA
        $this->_id_empresa =  $_GET["id_empresa"] ?? $dados_requisicao->id_empresa ?? $_POST["id_empresa"] ?? null;

        //ID_ADM
        $this->_id_administrador = $_GET["id_administrador"] ?? $dados_requisicao->id_administrador ?? $_POST["id_administrador"] ?? null;
    
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

                //PEGANDO DADOS DA REQ

                if ($this->_flag == "create") {

                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];

                        if ($envio_form == "true") {
                            if (isset($_FILES["imagem_perfil_adm"])) {
                                if ($_FILES["imagem_perfil_adm"]["error"] == 4) {
                                    return ("chegou a req sem imagem do adm");
                                } else {
                                    // return ("chegou a req com imagem do adm");
                                    //atualiza a img
                                   return $this->_model_empresa->updateEmpresa($this->_id_empresa);
                                }
                            } else if (isset($_FILES["imagem_perfil_salao"])) {
                                if ($_FILES["imagem_perfil_salao"]["error"] == 4) {
                                    return ("chegou a req sem imagem do salao");
                                } else {
                                    // return ("chegou a req com imagem do salao");
                                    //atualiza a img
                                    return $this->_model_empresa->updateEmpresa($this->_id_empresa);
                                }
                            }
                        }
                    } else {
                        // return $this->_model_empresa->createEmpresa();
                        $dados_empresa["dados_empresa"] = $this->_model_empresa->createEmpresa();
                        // return $dados_empresa["dados_empresa"]["lastInsertId"];
                        $idEmpresaCriada = $dados_empresa["dados_empresa"]["lastInsertId"];

                        $dados_empresa["dados_endereco_empresa"] = $this->_model_empresa->createEnderecoEmpresa($idEmpresaCriada);

                        $this->_array_funcionamento = $this->_dados_requisicao->dados_funcionamento;
                        // var_dump($this->_array_funcionamento);
                        $dados_empresa["dados_funcionamento"] = $this->_model_empresa->createFuncionamento($this->_array_funcionamento, $idEmpresaCriada);

                        $this->_array_forma_pagamento = $this->_dados_requisicao->dados_formas_pagamento;
                        $dados_empresa["dados_pagamento"] = $this->_model_empresa->createFormasPagamento($this->_array_forma_pagamento, $idEmpresaCriada);
                        // var_dump($this->_array_forma_pagamento);

                        if ($dados_empresa["dados_empresa"]["taxa_unica_cancelamento"] == null) {

                            $this->_array_taxas_cancelamento = $this->_dados_requisicao->dados_taxa_cancelamento;
                            $dados_empresa["dados_taxas_cancelamento"] = $this->_model_empresa->createTaxasCancelamento($this->_array_taxas_cancelamento, $idEmpresaCriada);
                        }

                        // $this->_array_imagens_espaco_salao = $this->_dados_requisicao->dados_imagens_estabelecimento;
                        // $dados_empresa["dados_imagens_estabelecimento"] = $this->_model_empresa->createImagensEstabelecimento($this->_array_imagens_espaco_salao, $idEmpresaCriada);

                        $dados_administrador["dados_administrador"] = $this->_model_admin->createAdministrador($idEmpresaCriada);

                        return array_merge($dados_empresa, $dados_administrador);
                    }
                } elseif ($this->_flag == "updateContaAdministradora") {


                    if (isset($_POST["envio_form"])) {

                        $envio_form = $_POST["envio_form"];

                        if ($envio_form == "true") {
                            if ($_FILES["imagem_perfil_salao"]["error"] == 4) {
                            } else {
                                //atualiza a img
                                 return $this->_model_empresa->updateEmpresa($this->_id_empresa);
                            }
                        }
                    } else {

                        // return "esse return é só uma garantia de que nada vai mudar no bd. Estou no else do case POST";

                        //RESUMO:
                        /*JÁ ESTAMOS CONSEGUINDO PEGAR OS DADOS DE REGRA DE CANCELAMENTO (E DOS OUTROS TBM). MUDAMOS A FORMA DE PEGA-LOS (DE INDICE ([]) PARA OBJETO (->))
                            PROBLEMAS COM ADM RESOLVIDOS

                            RESOLVER UPLOAD DE IMAGEM PARA A PASTA
                        */

                        // só chega aqui se a variavel $_POST["envio_form"] não existir, ou seja, quando for uma req fetch (só dados)
                                                
                        $dados_empresa["dados_empresa"] = $this->_model_empresa->updateEmpresa($this->_id_empresa);
                        $dados_empresa["dados_endereco_empresa"] = $this->_model_empresa->updateEnderecoEmpresa($this->_id_empresa);

                        $this->_model_empresa->limparFuncionamento($this->_id_empresa);
                        $this->_array_funcionamento = $this->_dados_requisicao->dados_funcionamento;
                        $dados_empresa["dados_funcionamento"] = $this->_model_empresa->createFuncionamento($this->_array_funcionamento, $this->_id_empresa);

                        $this->_model_empresa->limparFormasPagamento($this->_id_empresa);
                        $this->_array_forma_pagamento = $this->_dados_requisicao->dados_formas_pagamento;
                        $dados_empresa["dados_pagamento"] = $this->_model_empresa->createFormasPagamento($this->_array_forma_pagamento, $this->_id_empresa);

                        $this->_model_empresa->limparTaxasCancelamento($this->_id_empresa);
                        if ($dados_empresa["dados_empresa"]["taxa_unica_cancelamento"] == null) {

                            $this->_array_taxas_cancelamento = $this->_dados_requisicao->dados_taxa_cancelamento;
                            $dados_empresa["dados_taxas_cancelamento"] = $this->_model_empresa->createTaxasCancelamento($this->_array_taxas_cancelamento, $this->_id_empresa);
                            // return $this->_model_empresa->createTaxasCancelamento($this->_array_taxas_cancelamento, $this->_id_empresa);

                        }


                        $dados_administrador = $this->_model_admin->findIdByEmpresa($this->_id_empresa);
                        $this->_id_administrador = $dados_administrador[0]["id_administrador"];
                        // $dados_administrador["id_administrador"] = $this->_model_admin->findIdByEmpresa($this->_id_empresa);
                        $dados_administrador["dados_administrador"] = $this->_model_admin->updateAdministrador($this->_id_empresa, $this->_id_administrador);

                        // return array_merge($dados_empresa, $dados_administrador);
                    }
                } elseif ($this->_flag == "updateRedesSociais") {

                    $redesSociais = $this->_model_empresa->updateRedesSociais();
                    return $redesSociais;
                } 

            case 'DELETE':

                if ($this->_flag == "desabilitarEmpresa") {
                    $this->_model_empresa->desabilitarEmpresa($this->_id_empresa);
                } elseif ($this->_flag == "deleteRedesSociais") {
                    $this->_model_empresa->deleteRedesSociais($this->_id_empresa);
                } 
                break;

            default:
                # code...
                break;
        }
    }
}
