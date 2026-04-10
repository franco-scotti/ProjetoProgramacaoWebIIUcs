<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getPedidoDao();
$pedido = $dao->buscaPorId($id);

if($pedido) {
    $page_title = "Demo : Exibindo Pedido";
} else {
    $page_title = "Demo : Pedido nao encontrado!";
}

include_once "layout_header.php";

if($pedido) {
    $clienteId = $pedido->getCliente() ? $pedido->getCliente()->getId() : '';
    echo "<section>";
    echo "<h1> Pedido Numero : " . $pedido->getNumero() . "</h1>";
    echo "<p>Id : " . $pedido->getId() . "</p>";
    echo "<p>Data Pedido : " . $pedido->getDataPedido() . "</p>";
    echo "<p>Data Entrega : " . $pedido->getDataEntrega() . "</p>";
    echo "<p>Situacao : " . $pedido->getSituacao() . "</p>";
    echo "<p>Cliente ID : " . $clienteId . "</p>";
    echo "<a href='pedidos.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
