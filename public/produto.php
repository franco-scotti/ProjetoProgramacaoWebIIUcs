<?php
$page_title = "Produto - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";

function productImageSrc($foto) {
    if (!$foto) {
        return '';
    }
    if (is_resource($foto)) {
        $foto = stream_get_contents($foto);
    }
    if (strpos($foto, 'data:image') === 0) {
        return $foto;
    }
    return 'data:image/jpeg;base64,' . base64_encode($foto);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produto = null;
$preco = '0,00';
$estoqueDisponivel = 0;
if ($id) {
    $produto = $factory->getProdutoDao()->buscaPorId($id);
    $estoques = $factory->getEstoqueDao()->buscaTodos();
    foreach ($estoques as $e) {
        if ($e->getProduto() && $e->getProduto()->getId() == $id) {
            $preco = number_format($e->getPreco(), 2, ',', '.');
            $estoqueDisponivel = $e->getQuantidade();
            break;
        }
    }
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section class="product-page">
    <?php if ($produto) {
        $imgSrc = productImageSrc($produto->getFoto());
    ?>
        <div class="product-detail">
            <div class="product-media">
                <?php if ($imgSrc) { ?>
                    <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($produto->getNome()); ?>" />
                <?php } else { ?>
                    <div class="product-placeholder">Sem imagem disponível</div>
                <?php } ?>
            </div>
            <div class="product-info">
                <span class="eyebrow">Produto</span>
                <h2><?php echo htmlspecialchars($produto->getNome()); ?></h2>
                <p class="product-description"><?php echo htmlspecialchars($produto->getDescricao()); ?></p>
                <p class="product-meta">Fornecedor: <?php echo htmlspecialchars($produto->getFornecedor() ? $produto->getFornecedor()->getNome() : 'Não informado'); ?></p>
                <p class="product-stock">Disponível: <?php echo (int)$estoqueDisponivel; ?></p>
                <strong class="price">R$ <?php echo $preco; ?></strong>
                <form method="POST" action="<?php echo BASE_URL; ?>/app/controllers/cart/add.php" class="detail-add-form">
                    <input type="hidden" name="produto_id" value="<?php echo $produto->getId(); ?>" />
                    <label class="qty-label">Quantidade</label>
                    <input type="number" name="quantidade" value="1" min="1" class="qty-input" />
                    <button class="btn btn-primary" type="submit">Adicionar ao carrinho</button>
                </form>
                <div class="detail-actions">
                    <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-default">Voltar ao catálogo</a>
                    <a href="<?php echo BASE_URL; ?>/public/carrinho.php" class="btn btn-secondary">Ir para carrinho</a>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="empty-state">
            <p>Produto não encontrado.</p>
            <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-primary">Voltar ao catálogo</a>
        </div>
    <?php } ?>
</section>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>
