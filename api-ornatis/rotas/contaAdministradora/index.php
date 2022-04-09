<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: aplication/json");

include("../../Connection.php");
include("../../model/ModelAdministrador.php");
include("../../model/ModelEmpresa.php");
include("../../controller/ControllerContaAdministradora.php");

$conexao = new Connection();

$model_administrador = new ModelAdministrador($conexao->returnConnection());
$model_empresa = new ModelEmpresa($conexao->returnConnection());

$controller = new ControllerContaAdministradora($model_administrador, $model_empresa);

$dados = $controller->router();
 
echo json_encode(array("status"=>"Success","data"=>$dados))

?>