<?php
// Endpoint AJAX — retorna o total do carrinho em JSON
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
if (is_session_started() === FALSE) session_start();

header('Content-Type: application/json; charset=utf-8');

$cart  = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
$count = 0;
$itens = [];

foreach ($cart as $item) {
    $subtotal = (float)$item['preco'] * (int)$item['quantidade'];
    $total   += $subtotal;
    $count   += (int)$item['quantidade'];
    $itens[]  = [
        'nome'      => $item['nome'],
        'preco'     => (float)$item['preco'],
        'quantidade'=> (int)$item['quantidade'],
        'subtotal'  => $subtotal,
    ];
}

echo json_encode([
    'itens'       => $itens,
    'total'       => $total,
    'total_fmt'   => 'R$ ' . number_format($total, 2, ',', '.'),
    'quantidade'  => $count,
]);
