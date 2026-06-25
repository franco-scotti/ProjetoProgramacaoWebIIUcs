<?php
$page_title = "Portal Fornecedor - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'fornecedor') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section>
    <div class="section-heading">
        <h2>Portal do Fornecedor</h2>
        <p>Acesse os produtos sob sua responsabilidade e acompanhe pedidos relacionados.</p>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <h4>Meus produtos</h4>
            <p>Veja ou cadastre produtos vinculados ao fornecedor.</p>
            <a href="<?php echo BASE_URL; ?>/views/listagem/lista_produtos.php" class="btn btn-primary">Ver produtos</a>
        </div>
        <div class="feature-card">
            <h4>Pedidos</h4>
            <p>Confira pedidos com items de seus produtos.</p>
            <a href="<?php echo BASE_URL; ?>/public/portal_fornecedor.php" class="btn btn-primary">Ver pedidos</a>
        </div>
    </div>
</section>
<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>