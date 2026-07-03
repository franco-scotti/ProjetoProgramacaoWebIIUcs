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
                    ? (int)$_SESSION['usuario_fornecedor_id'] : null;
$filtraFornecedor = ($fornecedorId !== null);
$isFornecedor     = ($tipo === 'fornecedor');
$isAdmin          = ($tipo === 'admin');

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
$dao   = $factory->getPedidoDao();
$termo = trim((string)($_GET['pesquisa'] ?? ''));

function linhasPedidos($pedidos, $isFornecedor, $isAdmin) {
    if (!$pedidos) {
        echo "<tr><td colspan='7'>Nenhum pedido encontrado.</td></tr>";
        return;
    }
    foreach ($pedidos as $pedido) {
        $clienteNome = $pedido->getCliente() ? htmlspecialchars($pedido->getCliente()->getNome()) : '—';
        echo "<tr>";
        echo "<td>{$pedido->getNumero()}</td>";
        echo "<td>{$pedido->getDataPedido()}</td>";
        echo "<td>{$pedido->getDataEntrega()}</td>";
        $sit = htmlspecialchars($pedido->getSituacao());
        $badge = ['NOVO' => 'info', 'PREPARANDO PARA ENVIO' => 'warning', 'A CAMINHO' => 'primary', 'ENTREGUE' => 'success', 'CANCELADO' => 'danger'][$sit] ?? 'default';
        echo "<td><span class='label label-{$badge}'>{$sit}</span></td>";
        echo "<td>{$clienteNome}</td>";
        echo "<td>";
        echo "<a href='" . BASE_URL . "/pedido/{$pedido->getId()}' class='btn btn-primary btn-xs left-margin'><span class='glyphicon glyphicon-list'></span> Detalhe</a>";
        if ($isAdmin || $isFornecedor) {
            echo "<a href='" . BASE_URL . "/views/cadastro/form_pedido.php?id={$pedido->getId()}' class='btn btn-info btn-xs left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        }
        if ($isAdmin) {
            echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_pedido.php?id={$pedido->getId()}' class='btn btn-danger btn-xs left-margin' onclick=\"return confirm('Excluir este pedido?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        }
        echo "</td></tr>";
    }
}

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    if ($termo !== '') {
        $pedidos = $dao->buscaPorNumeroOuCliente($termo);
    } elseif ($filtraFornecedor) {
        $pedidos = $dao->buscaPorFornecedorId($fornecedorId);
    } else {
        $pedidos = $dao->buscaTodos();
    }
    linhasPedidos($pedidos, $isFornecedor, $isAdmin);
    exit;
}

$itensPorPagina = 10;
$paginaAtual    = max(1, (int)($_GET['pagina'] ?? 1));

if ($termo !== '') {
    $pedidos      = $dao->buscaPorNumeroOuCliente($termo);
    $totalPaginas = 1;
} elseif ($filtraFornecedor) {
    $pedidos      = $dao->buscaPorFornecedorId($fornecedorId);
    $totalPaginas = 1;
} else {
    $total        = (int)$dao->contaTodos();
    $totalPaginas = max(1, (int)ceil($total / $itensPorPagina));
    if ($paginaAtual > $totalPaginas) $paginaAtual = $totalPaginas;
    $offset  = ($paginaAtual - 1) * $itensPorPagina;
    $pedidos = $dao->buscaTodos($itensPorPagina, $offset);
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<h2><?= $filtraFornecedor ? 'Pedidos com meus produtos' : 'Pedidos' ?></h2>

<div class="form-group" style="max-width:400px">
    <input type="text" id="pesquisaPedido" class="form-control"
           placeholder="Buscar por número do pedido ou nome do cliente"
           value="<?= htmlspecialchars($termo) ?>" />
</div>

<table class="table table-hover table-responsive table-bordered">
    <thead>
        <tr>
            <th>Número</th><th>Data Pedido</th><th>Data Entrega</th>
            <th>Situação</th><th>Cliente</th><th>Ações</th>
        </tr>
    </thead>
    <tbody id="resultadoPedidos">
        <?php linhasPedidos($pedidos, $isFornecedor, $isAdmin); ?>
    </tbody>
</table>

<div id="paginacaoPedidos">
<?php if ($totalPaginas > 1 && $termo === '' && !$filtraFornecedor): ?>
    <p>Página <?= $paginaAtual ?> de <?= $totalPaginas ?></p>
    <nav>
        <?php if ($paginaAtual > 1): ?>
            <a href="?pagina=<?= $paginaAtual - 1 ?>" class="btn btn-default left-margin">Anterior</a>
        <?php endif; ?>
        <?php if ($paginaAtual < $totalPaginas): ?>
            <a href="?pagina=<?= $paginaAtual + 1 ?>" class="btn btn-default left-margin">Próxima</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
</div>

<?php if (!$isFornecedor): ?>
    <a href="<?= BASE_URL ?>/views/cadastro/form_pedido.php" class="btn btn-primary left-margin">Novo</a>
<?php endif; ?>
</section>

<script>
document.getElementById('pesquisaPedido').addEventListener('keyup', function () {
    var termo = this.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById('resultadoPedidos').innerHTML = this.responseText;
            document.getElementById('paginacaoPedidos').style.display =
                termo.trim() !== '' ? 'none' : 'block';
        }
    };
    xhttp.open('GET', 'lista_pedidos.php?ajax=1&pesquisa=' + encodeURIComponent(termo), true);
    xhttp.send();
});
</script>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
