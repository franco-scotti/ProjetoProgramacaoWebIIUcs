<?php
$page_title = "Confirmacao de Pedido - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";

if (!isset($_SESSION)) {
    session_start();
}

$pedidoId = isset($_GET['pedido_id']) ? (int)$_GET['pedido_id'] : 0;
$summary = isset($_SESSION['checkout_summary']) ? $_SESSION['checkout_summary'] : array();

$order = null;
$items = array();
$total = 0;
$clienteNome = $summary['nome'] ?? null;
$clienteEmail = $summary['email'] ?? null;
$endereco = $summary['endereco'] ?? null;

if ($pedidoId) {
    $pdo = $factory->getConnection();
    $stmt = $pdo->prepare("SELECT id, numero, data_pedido, situacao FROM pedido WHERE id = :id LIMIT 1");
    $stmt->bindValue(':id', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare(
        "SELECT ip.quantidade, ip.preco, pr.nome AS produto_nome, pr.descricao AS produto_descricao
        FROM item_pedido ip
        LEFT JOIN produto pr ON pr.id = ip.produto_id
        WHERE ip.pedido_id = :pedido_id"
    );
    $stmt->bindValue(':pedido_id', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $subtotal = (float)$row['preco'] * (int)$row['quantidade'];
        $total += $subtotal;
        $items[] = array(
            'nome' => $row['produto_nome'],
            'descricao' => $row['produto_descricao'],
            'preco' => (float)$row['preco'],
            'quantidade' => (int)$row['quantidade'],
            'subtotal' => $subtotal
        );
    }
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section>
    <div class="section-heading">
        <h2>Pedido confirmado</h2>
        <p>Obrigado pela compra! Seu pedido foi registrado com sucesso.</p>
    </div>

    <?php if ($order) { ?>
        <div class="confirmation-card">
            <p><strong>Numero do pedido:</strong> <?php echo htmlspecialchars($order['numero']); ?></p>
            <p><strong>Data:</strong> <?php echo htmlspecialchars($order['data_pedido']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['situacao']); ?></p>
            <?php if ($clienteNome || $clienteEmail) { ?>
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($clienteNome ?: '---'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($clienteEmail ?: '---'); ?></p>
            <?php } ?>
            <?php if ($endereco) { ?>
                <p><strong>Entrega para:</strong> <?php echo nl2br(htmlspecialchars($endereco)); ?></p>
            <?php } ?>
        </div>

        <?php if ($items) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preco</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nome']); ?></td>
                            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo $item['quantidade']; ?></td>
                            <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p><strong>Total pago:</strong> R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
        <?php } else { ?>
            <p>Nenhum item encontrado para este pedido.</p>
        <?php } ?>
    <?php } else { ?>
        <p>Pedido nao encontrado. Caso a compra tenha sido concluida, retorne ao catalogo ou verifique seus pedidos.</p>
    <?php } ?>

    <div class="confirmation-actions">
        <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-primary">Continuar comprando</a>
        <?php if (isset($_SESSION['checkout_cliente_id']) || (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'cliente')) { ?>
            <a href="<?php echo BASE_URL; ?>/public/meus_pedidos.php" class="btn btn-default">Ver meu pedido</a>
        <?php } else { ?>
            <a href="<?php echo BASE_URL; ?>/views/listagem/lista_pedidos.php" class="btn btn-default">Ver pedidos</a>
        <?php } ?>
    </div>
</section>

<?php
unset($_SESSION['checkout_summary']);
include_once dirname(__DIR__) . "/views/layout/layout_footer.php";
?>
