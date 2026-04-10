<?php
$page_title = "Demo : Listagem de Itens de Pedido";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getItemPedidoDao();
$itens = $dao->buscaTodos();

if($itens) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>PedidoId</th><th>ProdutoId</th><th>Quantidade</th><th>Preco</th><th>Acoes</th></tr>";

    foreach ($itens as $item) {
        $pedidoId = $item->getPedido() ? $item->getPedido()->getId() : '';
        $produtoId = $item->getProduto() ? $item->getProduto()->getId() : '';

        echo "<tr>";
        echo "<td>{$item->getId()}</td>";
        echo "<td>{$pedidoId}</td>";
        echo "<td>{$produtoId}</td>";
        echo "<td>{$item->getQuantidade()}</td>";
        echo "<td>{$item->getPreco()}</td>";
        echo "<td>";
        echo "<a href='mostra_item_pedido.php?id={$item->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='modifica_item_pedido.php?id={$item->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='remove_item_pedido.php?id={$item->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='novo_item_pedido.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
