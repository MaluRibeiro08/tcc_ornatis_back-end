<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../../Connection.php");
include("../../../model/ModelServico.php");
include("../../../controller/ControllerAdmServico.php");

$conexao = new Connection();

$model_servico = new ModelServico($conexao->returnConnection());

$controller = new ControllerAdmServico($model_servico);

$dados = $controller->router();
 
echo json_encode(array("status"=>"Success","data"=>$dados));

return;

?>