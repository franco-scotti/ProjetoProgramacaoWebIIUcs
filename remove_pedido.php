<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getPedidoDao();
$dao->removePorId($id);

header("Location: pedidos.php");
exit;
?>
