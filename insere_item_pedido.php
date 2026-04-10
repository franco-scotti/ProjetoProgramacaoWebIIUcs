<?php
include_once "fachada.php";

$pedidoId = trim((string)@$_GET["pedido_id"]);
$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);

$item = new ItemPedido(null, $quantidade, $preco);
if ($pedidoId !== "") {
    $item->setPedido(new Pedido($pedidoId, null, null, null, null));
}
if ($produtoId !== "") {
    $item->setProduto(new Produto($produtoId, '', '', null));
}

$dao = $factory->getItemPedidoDao();
$dao->insere($item);

header("Location: itens_pedido.php");
exit;
?>
