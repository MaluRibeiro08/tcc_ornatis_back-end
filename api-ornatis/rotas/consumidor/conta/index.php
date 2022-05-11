<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../../Connection.php");
include("../../../model/ModelConsumidor.php");
include("../../../controller/ControllerConsumidor.php");

$conexao = new Connection();

$model_consumidor = new ModelConsumidor($conexao->returnConnection());

$controller = new ControllerConsumidor($model_consumidor);

$dados = $controller->router();
 
echo json_encode(array("status"=>"Success","data"=>$dados));


?>