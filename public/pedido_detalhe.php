<?php
$page_title = "Detalhes do Pedido - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";

if (!isset($_SESSION)) {
    session_start();
}

$pedidoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$clienteId = $_SESSION['usuario_cliente_id'] ?? $_SESSION['checkout_cliente_id'] ?? null;

if (!$pedidoId || !$clienteId) {
    header('Location: ' . BASE_URL . '/public/meus_pedidos.php');
    exit;
}

$pdo = $factory->getConnection();
$stmt = $pdo->prepare("SELECT id, numero, data_pedido, data_entrega, situacao, cliente_id FROM pedido WHERE id = :id AND cliente_id = :cliente_id LIMIT 1");
$stmt->bindValue(':id', $pedidoId, PDO::PARAM_INT);
$stmt->bindValue(':cliente_id', $clienteId, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header('Location: ' . BASE_URL . '/public/meus_pedidos.php');
    exit;
}

$items = [];
$stmt = $pdo->prepare(
    "SELECT ip.quantidade, ip.preco, pr.nome AS produto_nome, pr.descricao AS produto_descricao
     FROM item_pedido ip
     LEFT JOIN produto pr ON pr.id = ip.produto_id
     WHERE ip.pedido_id = :pedido_id"
);
$stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $items[] = $row;
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section>
    <div class="section-heading">
        <h2>Detalhes do Pedido #<?php echo htmlspecialchars($pedido['numero']); ?></h2>
        <p>Informações completas deste pedido.</p>
    </div>

    <div class="confirmation-card">
        <p><strong>Data do pedido:</strong> <?php echo htmlspecialchars($pedido['data_pedido']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($pedido['situacao']); ?></p>
    </div>

    <?php if ($items) { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) {
                    $subtotal = $item['quantidade'] * $item['preco'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
                        <td><?php echo htmlspecialchars($item['produto_descricao']); ?></td>
                        <td><?php echo (int)$item['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><strong>Total do pedido:</strong>
            R$ <?php echo number_format(array_reduce($items, function ($carry, $item) {
                return $carry + ($item['quantidade'] * $item['preco']);
            }, 0), 2, ',', '.'); ?>
        </p>
    <?php } else { ?>
        <p>Este pedido não contém itens.</p>
    <?php } ?>

    <a href="<?php echo BASE_URL; ?>/public/meus_pedidos.php" class="btn btn-default">Voltar aos meus pedidos</a>
</section>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>
