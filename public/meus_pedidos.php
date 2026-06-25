<?php
$page_title = "Meus Pedidos - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";

if (!isset($_SESSION)) {
    session_start();
}

$clienteId = $_SESSION['usuario_cliente_id'] ?? $_SESSION['checkout_cliente_id'] ?? null;
if (!$clienteId) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$pdo = $factory->getConnection();
$stmt = $pdo->prepare(
    "SELECT p.id, p.numero, p.data_pedido, p.data_entrega, p.situacao, c.nome as cliente_nome
     FROM pedido p
     LEFT JOIN cliente c ON c.id = p.cliente_id
     WHERE p.cliente_id = :cliente_id
     ORDER BY p.data_pedido DESC"
);
$stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section>
    <div class="section-heading">
        <h2>Meus Pedidos</h2>
        <p>Aqui estão os pedidos registrados para o seu cliente.</p>
    </div>

    <?php if ($pedidos) { ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Cliente</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['numero']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['data_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['situacao']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['cliente_nome'] ?? 'Cliente'); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>/public/pedido_detalhe.php?id=<?php echo (int)$pedido['id']; ?>" class="btn btn-default btn-sm">Detalhes</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="empty-state">
            <p>Nenhum pedido encontrado para o seu cliente.</p>
            <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-primary">Voltar ao catálogo</a>
        </div>
    <?php } ?>
</section>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>
