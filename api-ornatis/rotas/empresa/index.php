<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../Connection.php");
include("../../model/ModelEmpresa.php");
include("../../controller/ControllerEmpresa.php");

$conexao = new Connection();

$model = new ModelEmpresa($conexao->returnConnection());

$controller = new ControllerEmpresa($model);

$dados = $controller->router();

echo json_encode(array("status"=>"Success","data"=>$dados))


?>