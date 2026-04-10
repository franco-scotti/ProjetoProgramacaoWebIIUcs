<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEnderecoDao();
$dao->removePorId($id);

header("Location: enderecos.php");
exit;
?>
