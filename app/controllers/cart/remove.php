<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

$produtoId = isset($_GET['produto_id']) ? (int)$_GET['produto_id'] : 0;
if ($produtoId && isset($_SESSION['cart'][$produtoId])) {
    unset($_SESSION['cart'][$produtoId]);
}

header('Location: ' . BASE_URL . '/public/carrinho.php');
exit;
