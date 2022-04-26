<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../../Connection.php");
include("../../../model/ModelFuncionario.php");
include("../../../controller/ControllerAdmFuncionario.php");

$conexao = new Connection();

$model_funcionario = new ModelFuncionario($conexao->returnConnection());

$controller = new ControllerAdmFuncionario($model_funcionario);

$dados = $controller->router();
 
echo json_encode(array("status"=>"Success","data"=>$dados));


?>