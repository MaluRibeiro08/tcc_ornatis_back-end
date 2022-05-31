<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../../Connection.php");
include("../../../model/ModelAgendamento.php");
include("../../../model/ModelServico.php");
include("../../../model/ModelFuncionario.php");
include("../../../model/ModelEmpresa.php");
include("../../../controller/ControllerAgendamentoConsumidor.php");

$conexao = new Connection();

$model_agendamento = new ModelAgendamento($conexao->returnConnection());
$model_servico = new ModelServico($conexao->returnConnection());
$model_funcionario = new ModelFuncionario($conexao->returnConnection());
$model_empresa = new ModelEmpresa($conexao->returnConnection());

$controller = new ControllerAgendamentoConsumidor($model_agendamento, $model_servico, $model_funcionario, $model_empresa);

$dados = $controller->router();
 
echo json_encode(array("status"=>"Success","data"=>$dados));

?>