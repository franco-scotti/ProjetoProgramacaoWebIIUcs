<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

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

header("Location: " . BASE_URL . "/views/listagem/lista_pedidos.php");
exit;
?>
