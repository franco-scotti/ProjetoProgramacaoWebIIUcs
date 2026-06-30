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

    <?php if ($cart): ?>
        <table class="table table-bordered" id="cart-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço unit.</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php foreach ($cart as $item):
                    $subtotal = $item['preco'] * $item['quantidade'];
                ?>
                    <tr data-id="<?= (int)$item['id'] ?>">
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td><?= (int)$item['quantidade'] ?></td>
                        <td class="item-subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/app/controllers/cart/remove.php?produto_id=<?= (int)$item['id'] ?>"
                               class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div id="cart-total-box" style="font-size:1.2em;margin:12px 0 20px">
            <strong>Total: <span id="cart-total">carregando…</span></strong>
            <small id="cart-loading" style="color:#888;margin-left:8px">⟳</small>
        </div>

        <a href="<?= BASE_URL ?>/public/checkout.php" class="btn btn-success btn-lg">Finalizar compra</a>

    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
        <a href="<?= BASE_URL ?>/public/catalogo.php" class="btn btn-primary">Ver catálogo</a>
    <?php endif; ?>
</section>

<script>
(function () {
    var totalSpan   = document.getElementById('cart-total');
    var loadingIcon = document.getElementById('cart-loading');
    if (!totalSpan) return;

    function fetchTotal() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?= BASE_URL ?>/app/controllers/api/total.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            loadingIcon.style.display = 'none';
            if (xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    totalSpan.textContent = data.total_fmt;
                } catch (e) {
                    totalSpan.textContent = 'Erro ao calcular';
                }
            } else {
                totalSpan.textContent = 'Erro ao calcular';
            }
        };
        xhr.send();
    }

    // Chama ao carregar a página
    fetchTotal();
}());
</script>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>
