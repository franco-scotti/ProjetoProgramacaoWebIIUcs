<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getItemPedidoDao();
$item = $dao->buscaPorId($id);

if($item) {
    $page_title = "Demo : Exibindo Item de Pedido";
} else {
    $page_title = "Demo : Item de Pedido nao encontrado!";
}

include_once "layout_header.php";

if($item) {
    $pedidoId = $item->getPedido() ? $item->getPedido()->getId() : '';
    $produtoId = $item->getProduto() ? $item->getProduto()->getId() : '';
    echo "<section>";
    echo "<h1> Item ID : " . $item->getId() . "</h1>";
    echo "<p>Pedido ID : " . $pedidoId . "</p>";
    echo "<p>Produto ID : " . $produtoId . "</p>";
    echo "<p>Quantidade : " . $item->getQuantidade() . "</p>";
    echo "<p>Preco : " . $item->getPreco() . "</p>";
    echo "<a href='itens_pedido.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
