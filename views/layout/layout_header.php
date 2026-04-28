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
    <link rel="stylesheet" type="text/css" href="/ProjetoProgramacaoWebIIUcs/public/assets/css/custom2.css">
    <link rel="stylesheet" href="/ProjetoProgramacaoWebIIUcs/public/assets/css/custom.css" />
</head>
<?php
$paginaAtual = basename($_SERVER['PHP_SELF']);
$menuPrincipal = array(
    '/ProjetoProgramacaoWebIIUcs/public/index.php' => 'Home',
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_produtos.php' => 'Produtos',
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_pedidos.php' => 'Pedidos',
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_clientes.php' => 'Clientes',
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_fornecedores.php' => 'Fornecedores',
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_estoques.php' => 'Estoque'
);

$menuGestao = array(
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_enderecos.php' => 'Enderecos',
    '/ProjetoProgramacaoWebIIUcs/views/listagem/lista_itens_pedido.php' => 'Itens Pedido'
);
?>
<body class="<?php echo $paginaAtual === '/ProjetoProgramacaoWebIIUcs/public/index.php' ? 'home-page' : 'inner-page'; ?>">
    <div class="site-bg"></div>
    <header class="store-header">
        <div class="brand-row">
            <a href="<?php echo BASE_URL; ?>/public/index.php" class="brand-block">
                <div class="brand-mark">
                    <img src="/ProjetoProgramacaoWebIIUcs/public/assets/images/LogoUCS.png" alt="Logo da loja" width="80" height="80" />
                </div>
                <div class="brand-copy">
                    <span class="brand-kicker">Loja Digital</span>
                    <strong class="brand-name">UCS Commerce</strong>
                    <span class="brand-subtitle">Gestao de catalogo, clientes e pedidos em um unico lugar.</span>
                </div>
            </a>
            <div class="header-tools">
                <div class="page-title-block">
                    <span class="page-title-label">Pagina atual</span>
                    <h1><?php echo $page_title; ?></h1>
                </div>
                <div class="session-chip">
                    <?php
                    if (isset($_SESSION["nome_usuario"])) {
                        echo "<span>Conta ativa: " . htmlspecialchars($_SESSION["nome_usuario"]) . "</span>";
                        echo "<a href='" . BASE_URL . "/app/controllers/executa_logout.php' class='session-link'>Sair</a>";
                    } else {
                        echo "<span>Ambiente administrativo</span>";
                        echo "<a href='" . BASE_URL . "/public/login.php' class='session-link'>Entrar</a>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <nav class="commerce-nav">
            <div class="nav-group">
                <?php
                foreach ($menuPrincipal as $arquivo => $rotulo) {
                    $classeAtiva = $paginaAtual === $arquivo ? 'nav-link is-active' : 'nav-link';
                    echo "<a href='{$arquivo}' class='{$classeAtiva}'>{$rotulo}</a>";
                }
                ?>
            </div>
            <div class="nav-group nav-group-secondary">
                <?php
                foreach ($menuGestao as $arquivo => $rotulo) {
                    $classeAtiva = $paginaAtual === $arquivo ? 'nav-link nav-link-secondary is-active' : 'nav-link nav-link-secondary';
                    echo "<a href='{$arquivo}' class='{$classeAtiva}'>{$rotulo}</a>";
                }

                if (isset($_SESSION["nome_usuario"])) {
                    $classeAtiva = $paginaAtual === 'lista_usuarios.php' ? 'nav-link nav-link-secondary is-active' : 'nav-link nav-link-secondary';
                    echo "<a href='lista_usuarios.php' class='{$classeAtiva}'>Usuarios</a>";
                }
                ?>
            </div>
        </nav>
    </header>

    <main class="page-shell">
