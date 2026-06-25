<?php
$page_title = "Catálogo - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";

function productImageSrc($foto) {
    if (!$foto) return '';
    if (is_resource($foto)) $foto = stream_get_contents($foto);
    if (strpos($foto, 'data:image') === 0) return $foto;
    return 'data:image/jpeg;base64,' . base64_encode($foto);
}

$search     = trim((string)($_GET['q'] ?? ''));
$produtoDao = $factory->getProdutoDao();
$estoqueDao = $factory->getEstoqueDao();

$produtos = $search !== ''
    ? $produtoDao->buscaPorCodigoNome($search)
    : $produtoDao->buscaTodos(100, 0);

// Monta mapa produto_id → estoque
$estoques = [];
foreach ($estoqueDao->buscaTodos() as $e) {
    if ($e->getProduto()) {
        $estoques[$e->getProduto()->getId()] = $e;
    }
}

// Quantidade já reservada no carrinho por produto
$cartQtd = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartQtd[(int)$item['id']] = (int)$item['quantidade'];
    }
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section class="catalog-page">
    <div class="page-heading">
        <div>
            <span class="eyebrow">Vitrine</span>
            <h2>Catálogo de produtos</h2>
            <p>Produtos disponíveis para compra com atendimento de catálogo visual.</p>
        </div>
        <form method="GET" action="<?php echo BASE_URL; ?>/public/catalogo.php" class="catalog-search">
            <input type="text" name="q" class="search-input" placeholder="Buscar por nome ou código"
                   value="<?php echo htmlspecialchars($search); ?>" />
            <button type="submit" class="search-btn">Buscar</button>
        </form>
    </div>

    <div class="product-grid">
        <?php if ($produtos):
            foreach ($produtos as $produto):
                $id       = $produto->getId();
                $nome     = htmlspecialchars($produto->getNome());
                $descricao= htmlspecialchars($produto->getDescricao());
                $foto     = productImageSrc($produto->getFoto());
                $estoque  = $estoques[$id] ?? null;
                $preco    = $estoque ? number_format($estoque->getPreco(), 2, ',', '.') : '0,00';

                // Estoque real menos o que já está no carrinho
                $estoqueTotal    = $estoque ? (int)$estoque->getQuantidade() : 0;
                $noCarrinho      = $cartQtd[$id] ?? 0;
                $estoqueDisponivel = max(0, $estoqueTotal - $noCarrinho);
                $esgotado        = $estoqueDisponivel <= 0;
        ?>
        <article class="product-card<?= $esgotado ? ' product-card--esgotado' : '' ?>">
            <div class="product-media">
                <?php if ($foto): ?>
                    <img src="<?= $foto ?>" alt="<?= $nome ?>" />
                <?php else: ?>
                    <div class="product-placeholder">Sem imagem</div>
                <?php endif; ?>
                <?php if ($esgotado): ?>
                    <span class="badge-esgotado">Esgotado</span>
                <?php endif; ?>
            </div>
            <div class="product-body">
                <h3><?= $nome ?></h3>
                <p class="product-description"><?= $descricao ?: 'Descrição não disponível' ?></p>
                <p class="product-meta">Fornecedor: <?= $produto->getFornecedor() ? htmlspecialchars($produto->getFornecedor()->getNome()) : 'Indisponível' ?></p>

                <?php if ($esgotado): ?>
                    <p class="product-stock product-stock--esgotado">&#10007; Sem estoque</p>
                <?php else: ?>
                    <p class="product-stock">Disponível: <?= $estoqueDisponivel ?></p>
                <?php endif; ?>

                <div class="product-actions">
                    <strong class="price">R$ <?= $preco ?></strong>

                    <?php if ($esgotado): ?>
                        <button class="btn btn-default" disabled title="Produto esgotado">Esgotado</button>
                    <?php else: ?>
                        <form method="POST" action="<?= BASE_URL ?>/app/controllers/cart/add.php" class="add-form">
                            <input type="hidden" name="produto_id" value="<?= $id ?>" />
                            <input type="number" name="quantidade" value="1"
                                   min="1" max="<?= $estoqueDisponivel ?>"
                                   class="qty-input" />
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </form>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>/public/produto.php?id=<?= $id ?>" class="btn btn-default btn-sm">Ver detalhes</a>
                </div>
            </div>
        </article>
        <?php endforeach;
        else: ?>
            <div class="empty-state">Nenhum produto encontrado para <strong><?= htmlspecialchars($search) ?></strong>.</div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Produto esgotado */
.product-card--esgotado { opacity: .7; }
.product-card--esgotado .product-media { position: relative; }

.badge-esgotado {
    position: absolute;
    top: 10px; left: 10px;
    background: #c0392b;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 3px;
}

.product-stock--esgotado {
    color: #c0392b;
    font-weight: 600;
}

.product-card--esgotado .price { color: #999; }
</style>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>