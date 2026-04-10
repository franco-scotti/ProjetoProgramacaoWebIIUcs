<?php
include_once "fachada.php";

$numero = trim((string)@$_GET["numero"]);
$dataPedido = trim((string)@$_GET["data_pedido"]);
$dataEntrega = trim((string)@$_GET["data_entrega"]);
$situacao = trim((string)@$_GET["situacao"]);
$clienteId = trim((string)@$_GET["cliente_id"]);

$pedido = new Pedido(null, $numero, $dataPedido, $dataEntrega, $situacao);
if ($clienteId !== "") {
    $pedido->setCliente(new Cliente($clienteId, '', '', '', ''));
}

$dao = $factory->getPedidoDao();
$dao->insere($pedido);

header("Location: pedidos.php");
exit;
?>
