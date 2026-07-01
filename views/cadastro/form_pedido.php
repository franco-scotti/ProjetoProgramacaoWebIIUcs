<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo = $_SESSION['usuario_tipo'] ?? '';
$fornecedorId = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                ? (int)$_SESSION['usuario_fornecedor_id'] : null;

if ($tipo !== 'admin' && $tipo !== 'fornecedor') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;
$erro   = $_GET['erro'] ?? '';
if ($tipo === 'fornecedor' && !$id) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$pedido = null;

$pedidoDao  = $factory->getPedidoDao();
$clienteDao = $factory->getClienteDao();
$clientes   = $clienteDao->buscaTodos();

if ($id) {
    $pedido     = $pedidoDao->buscaPorId($id);
    if ($tipo === 'fornecedor' && $fornecedorId !== null) {
        $pdo = $factory->getConnection();
        $stmt = $pdo->prepare(
            "SELECT 1 FROM item_pedido ip
             INNER JOIN produto pr ON pr.id = ip.produto_id
             WHERE ip.pedido_id = :pedido_id AND pr.fornecedor_id = :fornecedor_id
             LIMIT 1"
        );
        $stmt->bindValue(':pedido_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':fornecedor_id', $fornecedorId, PDO::PARAM_INT);
        $stmt->execute();
        if (!$stmt->fetchColumn()) {
            header('Location: ' . BASE_URL . '/public/index.php');
            exit;
        }
    }
    $page_title = "Demo : Alteração de Pedido";
    $action     = BASE_URL . "/app/controllers/altera/altera_pedido.php";
    $textoBotao = "Alterar";
    $method     = "post";
} else {
    $page_title = "Demo : Inserção de Pedido";
    $action     = BASE_URL . "/app/controllers/insere/insere_pedido.php";
    $textoBotao = "Inserir";
    $method     = "get";
}

$clienteAtualId = $pedido && $pedido->getCliente() ? $pedido->getCliente()->getId() : '';
$situacaoAtual  = $pedido ? $pedido->getSituacao() : 'NOVO';

include_once dirname(__DIR__) . "/layout/layout_header.php";

$erros = [
    'campos_obrigatorios' => 'Preencha todos os campos obrigatórios.',
];
if ($erro && isset($erros[$erro])) {
    echo "<div class='alert alert-danger'>" . $erros[$erro] . "</div>";
}
?>
<section>
<form action="<?= $action ?>" method="<?= $method ?>">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Número <span class="text-danger">*</span></td>
            <td><input type='text' name='numero' class='form-control'
                       value="<?= $pedido ? htmlspecialchars($pedido->getNumero()) : '' ?>"
                       <?= $pedido ? 'readonly' : '' ?> required /></td>
        </tr>
        <tr>
            <td>Data Pedido <span class="text-danger">*</span></td>
            <td><input type='date' name='data_pedido' class='form-control'
                       value="<?= $pedido ? htmlspecialchars($pedido->getDataPedido()) : date('Y-m-d') ?>"
                       <?= $tipo === 'fornecedor' ? 'readonly' : '' ?> required /></td>
        </tr>
        <tr>
            <td>Data Entrega <span class="text-danger">*</span></td>
            <td><input type='date' name='data_entrega' id='data_entrega' class='form-control'
                       value="<?= $pedido ? htmlspecialchars($pedido->getDataEntrega()) : '' ?>" required /></td>
        </tr>
        <tr id="row-data-cancelamento" style="display:<?= $situacaoAtual === 'CANCELADO' ? 'table-row' : 'none' ?>">
            <td>Data Cancelamento</td>
            <td><input type='date' name='data_cancelamento' id='data_cancelamento' class='form-control'
                       value="<?= ($pedido && $situacaoAtual === 'CANCELADO') ? htmlspecialchars($pedido->getDataEntrega()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Situação <span class="text-danger">*</span></td>
            <td>
                <select name='situacao' id='situacao' class='form-control' required>
                    <?php foreach (['NOVO', 'PREPARANDO PARA ENVIO', 'A CAMINHO', 'ENTREGUE', 'CANCELADO'] as $s): ?>
                        <option value='<?= $s ?>' <?= $situacaoAtual === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Cliente <span class="text-danger">*</span></td>
            <td>
                <?php if ($tipo === 'fornecedor'): ?>
                    <input type='hidden' name='cliente_id' value='<?= htmlspecialchars($clienteAtualId) ?>' />
                    <div class='form-control-static'><?= htmlspecialchars($pedido && $pedido->getCliente() ? $pedido->getCliente()->getNome() : 'Cliente não disponível') ?></div>
                <?php else: ?>
                    <select name='cliente_id' class='form-control' required>
                        <option value=''>Selecione um cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value='<?= $cliente->getId() ?>'
                                <?= ($cliente->getId() == $clienteAtualId) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cliente->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php if ($pedido): ?>
                    <input type="hidden" name="id" value="<?= $pedido->getId() ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>
                <a href="<?= BASE_URL ?>/views/listagem/lista_pedidos.php" class="btn btn-default left-margin">Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>

<script>
document.getElementById('situacao').addEventListener('change', function () {
    var s = this.value;
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('row-data-cancelamento').style.display = (s === 'CANCELADO') ? 'table-row' : 'none';
    if (s === 'ENTREGUE') { var de = document.getElementById('data_entrega'); if (!de.value) de.value = today; }
    if (s === 'CANCELADO') { var dc = document.getElementById('data_cancelamento'); if (!dc.value) dc.value = today; }
});
</script>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
