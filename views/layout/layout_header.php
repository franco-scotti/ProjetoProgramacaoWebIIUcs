<?php
include_once __DIR__ . "/../../app/controllers/login/comum.php";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

if (is_session_started() === FALSE) {
    session_start();
}
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/public/assets/css/custom2.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/custom.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/store.css" />
</head>
<?php
$paginaAtual  = basename($_SERVER['PHP_SELF']);
$logado       = isset($_SESSION['id_usuario']);
$tipoUsuario  = $_SESSION['usuario_tipo'] ?? null;   // 'admin' | 'cliente' | 'fornecedor' | 'usuario'
$isAdmin      = $tipoUsuario === 'admin';
$isFornecedor = ($tipoUsuario === 'fornecedor') || !empty($_SESSION['usuario_fornecedor_id']);
$isCliente    = ($tipoUsuario === 'cliente') || !empty($_SESSION['usuario_cliente_id']);

// --- Menu principal (esquerda) ---
$menuPrincipal = [
    '/ProjetoProgramacaoWebIIUcs/public/catalogo.php' => 'Catálogo',
];

if ($isAdmin ) {
    // Admin e fornecedor veem a gestão completa
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/public/index.php']                      = 'Home';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_produtos.php']    = 'Produtos';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_pedidos.php']     = 'Pedidos';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_clientes.php']    = 'Clientes';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_fornecedores.php']= 'Fornecedores';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_estoques.php']    = 'Estoque';
}elseif($isFornecedor) {
    // Fornecedores veem apenas seus produtos
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_produtos.php'] = 'Meus Produtos';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_pedidos.php'] = 'Meus Pedidos';
    $menuPrincipal['/ProjetoProgramacaoWebIIUcs/views/listagem/lista_estoques.php']    = 'Estoque';
}

// --- Menu secundário (direita) ---
$portalLink  = null;
$portalLabel = null;

if ($isAdmin) {
    $portalLink  = BASE_URL . '/public/portal_admin.php';
    $portalLabel = 'Portal Admin';
} elseif ($isFornecedor) {
    $portalLink  = BASE_URL . '/public/portal_fornecedor.php';
    $portalLabel = 'Portal Fornecedor';
} elseif ($isCliente) {
    $portalLink  = BASE_URL . '/public/meus_pedidos.php';
    $portalLabel = 'Meus Pedidos';
} elseif (!$logado && isset($_SESSION['checkout_cliente_id'])) {
    $portalLink  = BASE_URL . '/public/meus_pedidos.php';
    $portalLabel = 'Meus Pedidos';
}
?>
<body class="<?php echo $paginaAtual === 'index.php' ? 'home-page' : 'inner-page'; ?>">
    <div class="site-bg"></div>
    <header class="store-header">
        <div class="brand-row container">
            <a href="<?php echo BASE_URL; ?>/public/index.php" class="brand-block">
                <div class="brand-mark">
                    <img src="<?php echo BASE_URL; ?>/public/assets/images/LogoUCS.png" alt="Logo da loja" width="72" height="72" />
                </div>
                <div class="brand-copy">
                    <strong class="brand-name">UCS Commerce</strong>
                    <span class="brand-subtitle">Gestao de catalogo e pedidos</span>
                </div>
            </a>

            <div class="header-tools">
                <form method="GET" action="<?php echo BASE_URL; ?>/public/catalogo.php" class="search-form">
                    <input type="text" name="q" placeholder="Buscar produtos" class="search-input" />
                    <button type="submit" class="search-btn">Buscar</button>
                </form>

                <div class="cart-area">
                    <?php $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantidade')) : 0; ?>
                    <a href="<?php echo BASE_URL; ?>/public/carrinho.php" class="cart-link">Carrinho <span class="cart-count"><?php echo $cart_count; ?></span></a>
                </div>

                <div class="session-chip">
                    <?php
                    if ($logado) {
                        $roleLabel = $tipoUsuario ? ' (' . htmlspecialchars($tipoUsuario) . ')' : '';
                        $perfilLink = BASE_URL . '/views/cadastro/form_cliente.php';
                        if ($tipoUsuario === 'cliente' && !empty($_SESSION['usuario_cliente_id'])) {
                            $perfilLink .= '?id=' . (int)$_SESSION['usuario_cliente_id'];
                        }
                        echo "<a href='" . $perfilLink . "' class='session-link' style='color:inherit;text-decoration:none;'>" . htmlspecialchars($_SESSION['nome_usuario']) . $roleLabel . "</a>";
                        echo " <a href='" . BASE_URL . "/app/controllers/login/executa_logout.php' class='session-link'>Sair</a>";
                    } else {
                        echo "<a href='" . BASE_URL . "/public/login.php' class='session-link'>Entrar</a>";
                        echo " <a href='" . BASE_URL . "/public/cadastro.php' class='session-link'>Cadastrar</a>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <nav class="commerce-nav container">
            <div class="nav-group">
                <?php
                foreach ($menuPrincipal as $arquivo => $rotulo) {
                    $classeAtiva = $paginaAtual === basename($arquivo) ? 'nav-link is-active' : 'nav-link';
                    echo "<a href='{$arquivo}' class='{$classeAtiva}'>{$rotulo}</a>";
                }
                ?>
            </div>
            <div class="nav-group nav-group-secondary">
                <?php
                if ($portalLink && $portalLabel) {
                    $classeAtiva = basename($_SERVER['PHP_SELF']) === basename($portalLink) ? 'nav-link nav-link-secondary is-active' : 'nav-link nav-link-secondary';
                    echo "<a href='{$portalLink}' class='{$classeAtiva}'>{$portalLabel}</a>";
                }

                // Usuários só visível para admin
                if ($isAdmin) {
                    $classeAtiva = $paginaAtual === 'lista_usuarios.php' ? 'nav-link nav-link-secondary is-active' : 'nav-link nav-link-secondary';
                    echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php' class='{$classeAtiva}'>Usuários</a>";
                }
                ?>
            </div>
        </nav>
    </header>

    <?php
    $flashMessage = $_SESSION['flash_message'] ?? null;
    if ($flashMessage !== null) {
        unset($_SESSION['flash_message']);
        echo "<div class='container' style='margin-top: 20px;'>";
        echo "<div class='alert alert-success'>" . htmlspecialchars($flashMessage) . "</div>";
        echo "</div>";
    }
    ?>

    <main class="page-shell">
