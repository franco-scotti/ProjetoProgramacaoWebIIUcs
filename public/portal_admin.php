<?php
$page_title = "Portal Administrador - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section>
    <div class="section-heading">
        <h2>Portal do Administrador</h2>
        <p>Área interna para usuários administrativos.</p>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <h4>Gestão de Produtos</h4>
            <p>Acesse o catálogo administrativo para criar, alterar e remover produtos.</p>
            <a href="<?php echo BASE_URL; ?>/views/listagem/lista_produtos.php" class="btn btn-primary">Ver produtos</a>
        </div>
        <div class="feature-card">
            <h4>Pedidos</h4>
            <p>Veja todos os pedidos cadastrados e seu status.</p>
            <a href="<?php echo BASE_URL; ?>/views/listagem/lista_pedidos.php" class="btn btn-primary">Ver pedidos</a>
        </div>
        <div class="feature-card">
            <h4>Clientes</h4>
            <p>Gerencie sua base de clientes e informações de contato.</p>
            <a href="<?php echo BASE_URL; ?>/views/listagem/lista_clientes.php" class="btn btn-primary">Ver clientes</a>
        </div>
    </div>
</section>
<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>