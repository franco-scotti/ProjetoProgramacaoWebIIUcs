<?php
$page_title = "Carrinho - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/views/layout/layout_header.php";

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>
<section>
    <h2>Seu carrinho</h2>
    <?php if ($cart) { ?>
        <table class="table table-bordered">
            <thead><tr><th>Produto</th><th>Preco</th><th>Quantidade</th><th>Subtotal</th><th></th></tr></thead>
            <tbody>
                <?php $total = 0; foreach ($cart as $item) { $subtotal = $item['preco'] * $item['quantidade']; $total += $subtotal; ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nome']); ?></td>
                        <td>R$ <?php echo number_format($item['preco'],2,',','.'); ?></td>
                        <td><?php echo (int)$item['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($subtotal,2,',','.'); ?></td>
                        <td><a href="<?php echo BASE_URL; ?>/app/controllers/cart/remove.php?produto_id=<?php echo $item['id']; ?>" class="btn btn-danger">Remover</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><strong>Total: R$ <?php echo number_format($total,2,',','.'); ?></strong></p>
        <a href="<?php echo BASE_URL; ?>/public/checkout.php" class="btn btn-success">Finalizar compra</a>
    <?php } else { ?>
        <p>Seu carrinho esta vazio.</p>
        <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-primary">Ver catalogo</a>
    <?php } ?>
</section>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>
