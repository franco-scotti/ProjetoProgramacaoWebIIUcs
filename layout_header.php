<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="libs/css/custom2.css">
    <link rel="stylesheet" href="libs/css/custom.css" />
</head>
<?php
include_once "comum.php";

if (is_session_started() === FALSE) {
    session_start();
}

$paginaAtual = basename($_SERVER['PHP_SELF']);
$menuPrincipal = array(
    'index.php' => 'Home',
    'produtos.php' => 'Produtos',
    'pedidos.php' => 'Pedidos',
    'clientes.php' => 'Clientes',
    'fornecedores.php' => 'Fornecedores',
    'estoques.php' => 'Estoque'
);

$menuGestao = array(
    'enderecos.php' => 'Enderecos',
    'itens_pedido.php' => 'Itens Pedido'
);
?>
<body class="<?php echo $paginaAtual === 'index.php' ? 'home-page' : 'inner-page'; ?>">
    <div class="site-bg"></div>
    <header class="store-header">
        <div class="brand-row">
            <a href="index.php" class="brand-block">
                <div class="brand-mark">
                    <img src="images/LogoUCS.png" alt="Logo da loja" width="80" height="80" />
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
                        echo "<a href='executa_logout.php' class='session-link'>Sair</a>";
                    } else {
                        echo "<span>Ambiente administrativo</span>";
                        echo "<a href='login.php' class='session-link'>Entrar</a>";
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
                    $classeAtiva = $paginaAtual === 'usuarios.php' ? 'nav-link nav-link-secondary is-active' : 'nav-link nav-link-secondary';
                    echo "<a href='usuarios.php' class='{$classeAtiva}'>Usuarios</a>";
                }
                ?>
            </div>
        </nav>
    </header>

    <main class="page-shell">
