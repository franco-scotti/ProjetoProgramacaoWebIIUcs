<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getProdutoDao();
$dao->removePorId($id);

header("Location: produtos.php");
exit;
?>
