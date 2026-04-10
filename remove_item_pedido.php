<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getItemPedidoDao();
$dao->removePorId($id);

header("Location: itens_pedido.php");
exit;
?>
