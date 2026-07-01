<?php
$page_title = "Portal Cliente - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

include_once dirname(__DIR__) . "/views/layout/layout_header.php";
?>
<section>
    <div class="section-heading">
        <h2>Portal do Cliente</h2>
        <p>Veja seus pedidos e continue comprando.</p>
    </div>

    <div class="feature-grid">
        <div class="feature-card">
            <h4>Meu catálogo</h4>
            <p>Veja o catálogo completo e adicione novos produtos ao carrinho.</p>
            <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-primary">Ir ao catálogo</a>
        </div>
        <div class="feature-card">
            <h4>Meus pedidos</h4>
            <p>Confira os pedidos feitos pelo seu cliente.</p>
            <a href="<?php echo BASE_URL; ?>/public/meus_pedidos.php" class="btn btn-primary">Ver meus pedidos</a>
        </div>
        <div class="feature-card">
            <h4>Meus dados</h4>
            <p>Atualize seu nome, telefone, e-mail, cartão e endereço cadastrado.</p>
            <a href="<?php echo BASE_URL; ?>/views/cadastro/form_cliente.php" class="btn btn-primary">Editar meus dados</a>
        </div>
        <div class="feature-card">
            <h4>Meu carrinho</h4>
            <p>Revisar itens antes de finalizar a compra.</p>
            <a href="<?php echo BASE_URL; ?>/public/carrinho.php" class="btn btn-primary">Ir ao carrinho</a>
        </div>
    </div>
</section>
<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>