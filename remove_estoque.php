<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEstoqueDao();
$dao->removePorId($id);

header("Location: estoques.php");
exit;
?>
