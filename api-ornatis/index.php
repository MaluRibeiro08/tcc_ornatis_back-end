<?php

require("Connection.php");
require("model/ModelEmpresa.php");

$conexao = new Connection();

// $model_empresa = new ModelEmpresa($conexao->returnConnection());

// $dados = $model_empresa->findAll();

echo '<pre>';
var_dump($conexao);
echo '</pre>';

?>