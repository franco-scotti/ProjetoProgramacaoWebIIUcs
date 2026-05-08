<?php
$page_title = "Demo : Listagem de Pedidos";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

echo "<section>";

$dao = $factory->getPedidoDao();
$pedidos = $dao->buscaTodos();

if($pedidos) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Numero</th><th>Data Pedido</th><th>Data Entrega</th><th>Situacao</th><th>ClienteId</th><th>Acoes</th></tr>";

    foreach ($pedidos as $pedido) {
        $clienteId = $pedido->getCliente() ? $pedido->getCliente()->getNome(): '';
        echo "<tr>";
        echo "<td>{$pedido->getId()}</td>";
        echo "<td>{$pedido->getNumero()}</td>";
        echo "<td>{$pedido->getDataPedido()}</td>";
        echo "<td>{$pedido->getDataEntrega()}</td>";
        echo "<td>{$pedido->getSituacao()}</td>";
        echo "<td>{$clienteId}</td>";
        echo "<td>";
        echo "<a href='" . BASE_URL . "/views/detalhes/mostra_pedido.php?id={$pedido->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='" . BASE_URL . "/views/altera/modifica_pedido.php?id={$pedido->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_pedido.php?id={$pedido->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='" . BASE_URL . "/views/cadastro/novo_pedido.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
