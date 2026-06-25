<?php
$page_title = "UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/app/controllers/login/comum.php";
if (is_session_started() === FALSE) {
    session_start();
}

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

$usuarioLogado = isset($_SESSION["nome_usuario"]);
$produtosDestaque = [];
$estoques = [];
if (!$usuarioLogado) {
    $produtosDestaque = $factory->getProdutoDao()->buscaTodos(8, 0);
    foreach ($factory->getEstoqueDao()->buscaTodos() as $e) {
        if ($e->getProduto()) {
            $estoques[$e->getProduto()->getId()] = $e;
        }
    }
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<?php if ($usuarioLogado) { ?>
<section class="hero-panel">
    <div class="hero-copy">
        <span class="eyebrow">Dashboard comercial</span>
        <h2>Uma vitrine mais forte para administrar seu e-commerce.</h2>
        <p>Centralize o catalogo, acompanhe a operacao e acesse rapidamente clientes, pedidos e estoque a partir de uma home com cara de loja online.</p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/public/catalogo.php" class="btn btn-primary btn-lg">Ver catálogo</a>
            <a href="<?= BASE_URL ?>/views/listagem/lista_pedidos.php" class="btn btn-default btn-lg">Acompanhar pedidos</a>
        </div>
    </div>
    <div class="hero-highlight">
        <div class="highlight-card">
            <span class="highlight-label">Campanha da semana</span>
            <strong>Frete visualmente mais claro, catalogo em destaque e fluxo administrativo mais rapido.</strong>
            <p>O foco agora e apresentar o sistema como uma operacao de e-commerce, nao mais como um layout de demonstracao.</p>
        </div>
    </div>
</section>

<section class="stats-grid">
    <article class="stat-card">
        <span class="stat-label">Produtos</span>
        <strong><?php echo (int)$factory->getProdutoDao()->contaTodos(); ?></strong>
        <p>Itens cadastrados no catalogo.</p>
    </article>
    <article class="stat-card">
        <span class="stat-label">Clientes</span>
        <strong><?php echo count($factory->getClienteDao()->buscaTodos()); ?></strong>
        <p>Base ativa para relacionamento e vendas.</p>
    </article>
    <article class="stat-card">
        <span class="stat-label">Pedidos</span>
        <strong><?php echo count($factory->getPedidoDao()->buscaTodos()); ?></strong>
        <p>Pedidos registrados na operacao.</p>
    </article>
    <article class="stat-card stat-card-accent">
        <span class="stat-label">Rota rapida</span>
        <strong>Gestao em 1 clique</strong>
        <p>Acesse estoque, fornecedores e clientes sem perder contexto.</p>
    </article>
</section>

<section class="content-split">
    <article class="showcase-panel">
        <div class="section-heading">
            <span class="eyebrow">Destaques do catalogo</span>
            <h3>Produtos em evidencia</h3>
        </div>
        <div class="feature-grid">
            <?php $produtosDestaque = $factory->getProdutoDao()->buscaTodos(4, 0); ?>
            <?php if ($produtosDestaque) { ?>
                <?php foreach ($produtosDestaque as $produto) { ?>
                    <div class="feature-card">
                        <span class="feature-badge">Produto</span>
                        <h4><?php echo htmlspecialchars($produto->getNome()); ?></h4>
                        <p><?php echo htmlspecialchars($produto->getDescricao() ?: "Descricao nao informada."); ?></p>
                        <a href="<?= BASE_URL ?>/public/produto.php?id=<?php echo $produto->getId(); ?>">Ver detalhes</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="feature-card feature-card-empty">
                    <span class="feature-badge">Catalogo</span>
                    <h4>Nenhum produto em destaque ainda</h4>
                    <p>Cadastre produtos para transformar esta home em uma vitrine operacional.</p>
                    <a href="<?= BASE_URL ?>/views/cadastro/novo_produto.php">Cadastrar primeiro produto</a>
                </div>
            <?php } ?>
        </div>
    </article>

    <aside class="quick-panel">
        <div class="section-heading">
            <span class="eyebrow">Operacao</span>
            <h3>Acessos rapidos</h3>
        </div>
        <a href="<?= BASE_URL ?>/views/cadastro/novo_produto.php" class="quick-link">
            <strong>Novo produto</strong>
            <span>Adicione itens ao catalogo com rapidez.</span>
        </a>
        <a href="<?= BASE_URL ?>/views/listagem/lista_clientes.php" class="quick-link">
            <strong>Base de clientes</strong>
            <span>Visualize quem compra e mantenha os dados organizados.</span>
        </a>
        <a href="<?= BASE_URL ?>/views/listagem/lista_estoques.php" class="quick-link">
            <strong>Controle de estoque</strong>
            <span>Acompanhe disponibilidade e precificacao.</span>
        </a>
        <a href="<?= BASE_URL ?>/views/listagem/lista_fornecedores.php" class="quick-link">
            <strong>Rede de fornecedores</strong>
            <span>Mantenha origem dos produtos e contatos em dia.</span>
        </a>
    </aside>
</section>

<section class="commerce-banner">
    <div>
        <span class="eyebrow">Fluxo comercial</span>
        <h3>Do cadastro ao pedido, tudo com visual de loja online.</h3>
    </div>
    <div class="banner-links">
        <a href="<?= BASE_URL ?>/public/catalogo.php">Catálogo completo</a>
        <a href="<?= BASE_URL ?>/views/listagem/lista_pedidos.php">Status dos pedidos</a>
        <a href="<?= BASE_URL ?>/views/listagem/lista_clientes.php">Relacionamento</a>
    </div>
</section>
<?php } else { ?>
<section class="hero-panel">
    <div class="hero-copy">
        <span class="eyebrow">Bem-vindo à UCS Commerce</span>
        <h2>Compre online com facilidade e veja nossos melhores produtos.</h2>
        <p>Explore o catálogo, confira disponibilidade em tempo real e adicione itens ao carrinho sem acesso ao painel administrativo.</p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/public/catalogo.php" class="btn btn-primary btn-lg">Ver catálogo</a>
            <a href="<?= BASE_URL ?>/public/login.php" class="btn btn-default btn-lg">Entrar</a>
        </div>
    </div>
    <div class="hero-highlight">
        <div class="highlight-card">
            <span class="highlight-label">Promoção</span>
            <strong>Encontre ofertas e novidades direto no catálogo de produtos.</strong>
            <p>O acesso ao painel administrativo é reservado apenas para usuários logados com permissão.</p>
        </div>
    </div>
</section>

<section class="product-grid home-product-grid">
    <?php if ($produtosDestaque) {
        foreach ($produtosDestaque as $produto) {
            $id = $produto->getId();
            $nome = htmlspecialchars($produto->getNome());
            $descricao = htmlspecialchars($produto->getDescricao() ?: 'Descrição não disponível');
            $foto = productImageSrc($produto->getFoto());
            $estoque = isset($estoques[$id]) ? $estoques[$id] : null;
            $preco = $estoque ? number_format($estoque->getPreco(), 2, ',', '.') : '0,00';
            $quantidade = $estoque ? (int)$estoque->getQuantidade() : 0;
            ?>
            <article class="product-card">
                <div class="product-media">
                    <?php if ($foto) { ?>
                        <img src="<?php echo $foto; ?>" alt="<?php echo $nome; ?>" />
                    <?php } else { ?>
                        <div class="product-placeholder">Sem imagem</div>
                    <?php } ?>
                </div>
                <div class="product-body">
                    <h3><?php echo $nome; ?></h3>
                    <p class="product-description"><?php echo $descricao; ?></p>
                    <p class="product-stock">Disponível: <?php echo $quantidade; ?></p>
                    <div class="product-actions">
                        <strong class="price">R$ <?php echo $preco; ?></strong>
                        <a href="<?= BASE_URL ?>/public/produto.php?id=<?php echo $id; ?>" class="btn btn-default btn-sm">Ver detalhes</a>
                    </div>
                </div>
            </article>
        <?php }
    } else { ?>
        <div class="empty-state">
            <p>Nenhum produto publicado no catálogo.</p>
            <a href="<?= BASE_URL ?>/public/catalogo.php" class="btn btn-primary">Ir para catálogo</a>
        </div>
    <?php } ?>
</section>
<?php } ?>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>
