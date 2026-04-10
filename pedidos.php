<?php
$page_title = "Demo : Listagem de Pedidos";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getPedidoDao();
$pedidos = $dao->buscaTodos();

if($pedidos) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Numero</th><th>Data Pedido</th><th>Data Entrega</th><th>Situacao</th><th>ClienteId</th><th>Acoes</th></tr>";

    foreach ($pedidos as $pedido) {
        $clienteId = $pedido->getCliente() ? $pedido->getCliente()->getId() : '';
        echo "<tr>";
        echo "<td>{$pedido->getId()}</td>";
        echo "<td>{$pedido->getNumero()}</td>";
        echo "<td>{$pedido->getDataPedido()}</td>";
        echo "<td>{$pedido->getDataEntrega()}</td>";
        echo "<td>{$pedido->getSituacao()}</td>";
        echo "<td>{$clienteId}</td>";
        echo "<td>";
        echo "<a href='mostra_pedido.php?id={$pedido->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='modifica_pedido.php?id={$pedido->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='remove_pedido.php?id={$pedido->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='novo_pedido.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
