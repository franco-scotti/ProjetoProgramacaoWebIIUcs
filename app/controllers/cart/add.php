<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

$produtoId = isset($_POST['produto_id']) ? (int)$_POST['produto_id'] : 0;
$quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 1;
if ($quantidade < 1) $quantidade = 1;

$referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/public/catalogo.php';

if ($produtoId > 0) {
    $produto = $factory->getProdutoDao()->buscaPorId($produtoId);

    // Busca estoque e preço do produto
    $preco = 0.0;
    $estoqueTotal = 0;
    foreach ($factory->getEstoqueDao()->buscaTodos() as $e) {
        if ($e->getProduto() && $e->getProduto()->getId() == $produtoId) {
            $preco        = (float)$e->getPreco();
            $estoqueTotal = (int)$e->getQuantidade();
            break;
        }
    }

    // Quanto já está no carrinho para esse produto
    $noCarrinho = 0;
    if (isset($_SESSION['cart'][$produtoId])) {
        $noCarrinho = (int)$_SESSION['cart'][$produtoId]['quantidade'];
    }

    // Estoque disponível real
    $estoqueDisponivel = max(0, $estoqueTotal - $noCarrinho);

    // Se não há nada disponível, ignora a adição
    if ($estoqueDisponivel <= 0) {
        header('Location: ' . $referer);
        exit;
    }

    // Limita a quantidade solicitada ao que ainda está disponível
    $quantidade = min($quantidade, $estoqueDisponivel);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$produtoId])) {
        $_SESSION['cart'][$produtoId]['quantidade'] += $quantidade;
    } else {
        $_SESSION['cart'][$produtoId] = [
            'id'         => $produtoId,
            'nome'       => $produto ? $produto->getNome() : 'Produto',
            'preco'      => $preco,
            'quantidade' => $quantidade,
        ];
    }
}

header('Location: ' . $referer);
exit;