<?php
$page_title = "Demo : Listagem de Pedidos";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo = $_SESSION['usuario_tipo'] ?? '';

if ($tipo === 'cliente') {
    header('Location: ' . BASE_URL . '/public/meus_pedidos.php');
    exit;
}

$fornecedorId     = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                    ? (int)$_SESSION['usuario_fornecedor_id']
                    : null;
$filtraFornecedor = ($fornecedorId !== null);
$isFornecedor     = ($tipo === 'fornecedor');

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$dao = $factory->getPedidoDao();

$pedidos = $filtraFornecedor
    ? $dao->buscaPorFornecedorId($fornecedorId)
    : $dao->buscaTodos();

include_once dirname(__DIR__) . "/layout/layout_header.php";

echo "<section>";
echo "<h2>" . ($filtraFornecedor ? "Pedidos com meus produtos" : "Todos os Pedidos") . "</h2>";

if ($pedidos) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Número</th><th>Data Pedido</th><th>Data Entrega</th><th>Situação</th><th>Cliente</th><th>Ações</th></tr>";

    foreach ($pedidos as $pedido) {
        $clienteNome = $pedido->getCliente() ? htmlspecialchars($pedido->getCliente()->getNome()) : '—';
        echo "<tr>";
        echo "<td>{$pedido->getId()}</td>";
        echo "<td>{$pedido->getNumero()}</td>";
        echo "<td>{$pedido->getDataPedido()}</td>";
        echo "<td>{$pedido->getDataEntrega()}</td>";
        echo "<td>{$pedido->getSituacao()}</td>";
        echo "<td>{$clienteNome}</td>";
        echo "<td>";
        echo "<a href='" . BASE_URL . "/views/detalhes/mostra_pedido.php?id={$pedido->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        if (!$isFornecedor) {
            echo "<a href='" . BASE_URL . "/views/altera/modifica_pedido.php?id={$pedido->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
            echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_pedido.php?id={$pedido->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        }
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<div class='alert alert-info'>Nenhum pedido encontrado.</div>";
}

if (!$isFornecedor) {
    echo "<a href='" . BASE_URL . "/views/cadastro/novo_pedido.php' class='btn btn-primary left-margin'>Novo</a>";
}

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
