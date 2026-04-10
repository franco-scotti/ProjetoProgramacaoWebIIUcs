<?php
include_once "fachada.php";

$id = @$_GET["id"];

$dao = $factory->getFornecedorDao();
$dao->removePorId($id);

header("Location: fornecedores.php");
exit;
?>
