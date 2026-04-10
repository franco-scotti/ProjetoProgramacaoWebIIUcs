<?php
include_once "fachada.php";

$id = @$_GET["id"];
$numero = trim((string)@$_GET["numero"]);
$dataPedido = trim((string)@$_GET["data_pedido"]);
$dataEntrega = trim((string)@$_GET["data_entrega"]);
$situacao = trim((string)@$_GET["situacao"]);
$clienteId = trim((string)@$_GET["cliente_id"]);

$pedido = new Pedido($id, $numero, $dataPedido, $dataEntrega, $situacao);
if ($clienteId !== "") {
    $pedido->setCliente(new Cliente($clienteId, '', '', '', ''));
}

$dao = $factory->getPedidoDao();
$dao->altera($pedido);

header("Location: pedidos.php");
exit;
?>
