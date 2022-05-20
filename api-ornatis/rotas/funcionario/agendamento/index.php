<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../../Connection.php");
include("../../../model/ModelAgendamento.php");
include("../../../controller/ControllerAgendamentoFuncionario.php");

$conexao = new Connection();

$model_agendamento = new ModelAgendamento($conexao->returnConnection());

$controller = new ControllerAgendamentoFuncionario($model_agendamento);

$dados = $controller->router();
 
echo json_encode(array("status"=>"Success","data"=>$dados));

?>