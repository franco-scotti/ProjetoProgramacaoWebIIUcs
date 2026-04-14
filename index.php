<?php
$page_title = "Painel da Loja";
include_once "fachada.php";

$totalProdutos = 0;
$totalClientes = 0;
$totalPedidos = 0;
$produtosDestaque = array();

try {
    $totalProdutos = $factory->getProdutoDao()->contaTodos();
    $totalClientes = count($factory->getClienteDao()->buscaTodos());
    $totalPedidos = count($factory->getPedidoDao()->buscaTodos());
    $produtosDestaque = $factory->getProdutoDao()->buscaTodos(4, 0);
} catch (Throwable $e) {
    $produtosDestaque = array();
}

include_once "layout_header.php";
?>
<section class="hero-panel">
    <div class="hero-copy">
        <span class="eyebrow">Dashboard comercial</span>
        <h2>Uma vitrine mais forte para administrar seu e-commerce.</h2>
        <p>Centralize o catalogo, acompanhe a operacao e acesse rapidamente clientes, pedidos e estoque a partir de uma home com cara de loja online.</p>
        <div class="hero-actions">
            <a href="produtos.php" class="btn btn-primary btn-lg">Ver catalogo</a>
            <a href="pedidos.php" class="btn btn-default btn-lg">Acompanhar pedidos</a>
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
        <strong><?php echo (int)$totalProdutos; ?></strong>
        <p>Itens cadastrados no catalogo.</p>
    </article>
    <article class="stat-card">
        <span class="stat-label">Clientes</span>
        <strong><?php echo (int)$totalClientes; ?></strong>
        <p>Base ativa para relacionamento e vendas.</p>
    </article>
    <article class="stat-card">
        <span class="stat-label">Pedidos</span>
        <strong><?php echo (int)$totalPedidos; ?></strong>
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
            <?php if ($produtosDestaque) { ?>
                <?php foreach ($produtosDestaque as $produto) { ?>
                    <div class="feature-card">
                        <span class="feature-badge">Produto</span>
                        <h4><?php echo htmlspecialchars($produto->getNome()); ?></h4>
                        <p><?php echo htmlspecialchars($produto->getDescricao() ?: "Descricao nao informada."); ?></p>
                        <a href="mostra_produto.php?id=<?php echo $produto->getId(); ?>">Abrir cadastro</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="feature-card feature-card-empty">
                    <span class="feature-badge">Catalogo</span>
                    <h4>Nenhum produto em destaque ainda</h4>
                    <p>Cadastre produtos para transformar esta home em uma vitrine operacional.</p>
                    <a href="novo_produto.php">Cadastrar primeiro produto</a>
                </div>
            <?php } ?>
        </div>
    </article>

    <aside class="quick-panel">
        <div class="section-heading">
            <span class="eyebrow">Operacao</span>
            <h3>Acessos rapidos</h3>
        </div>
        <a href="novo_produto.php" class="quick-link">
            <strong>Novo produto</strong>
            <span>Adicione itens ao catalogo com rapidez.</span>
        </a>
        <a href="clientes.php" class="quick-link">
            <strong>Base de clientes</strong>
            <span>Visualize quem compra e mantenha os dados organizados.</span>
        </a>
        <a href="estoques.php" class="quick-link">
            <strong>Controle de estoque</strong>
            <span>Acompanhe disponibilidade e precificacao.</span>
        </a>
        <a href="fornecedores.php" class="quick-link">
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
        <a href="produtos.php">Catalogo completo</a>
        <a href="pedidos.php">Status dos pedidos</a>
        <a href="clientes.php">Relacionamento</a>
    </div>
</section>
<?php include_once "layout_footer.php"; ?>
