<?php
$page_title = "Demo : Listagem de Itens de Pedido";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

echo "<section>";

$dao = $factory->getItemPedidoDao();
$itens = $dao->buscaTodos();

if($itens) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>PedidoId</th><th>ProdutoId</th><th>Quantidade</th><th>Preco</th><th>Acoes</th></tr>";

    foreach ($itens as $item) {
        $pedidoNumero = $item->getPedido() ? $item->getPedido()->getNumero() : '';
        $produtoNome = $item->getProduto() ? $item->getProduto()->getNome() : '';

        echo "<tr>";
        echo "<td>{$item->getId()}</td>";
        echo "<td>{$pedidoNumero}</td>";
        echo "<td>{$produtoNome}</td>";
        echo "<td>{$item->getQuantidade()}</td>";
        echo "<td>{$item->getPreco()}</td>";
        echo "<td>";
        echo "<a href='" . BASE_URL . "/views/detalhes/mostra_item_pedido.php?id={$item->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='" . BASE_URL . "/views/altera/modifica_item_pedido.php?id={$item->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_item_pedido.php?id={$item->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='" . BASE_URL . "/views/cadastro/novo_item_pedido.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
