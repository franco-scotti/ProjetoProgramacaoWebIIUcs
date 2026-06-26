<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

if (is_session_started() === FALSE) session_start();

// Requer login com permissão de estoque
$tipo = $_SESSION['usuario_tipo'] ?? '';
if (!in_array($tipo, ['admin', 'fornecedor'])) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$id         = (int)($_GET['id'] ?? 0);
$produtoId  = trim((string)($_GET['produto_id'] ?? ''));
$quantidade = trim((string)($_GET['quantidade'] ?? ''));
$preco      = trim((string)($_GET['preco'] ?? ''));

// Fornecedor: verifica se o estoque pertence a um produto seu
if ($tipo === 'fornecedor') {
    $fornecedorId = (int)($_SESSION['usuario_fornecedor_id'] ?? 0);
    $estoqueAtual = $factory->getEstoqueDao()->buscaPorId($id);

    if (!$estoqueAtual || !$estoqueAtual->getProduto()) {
        header('Location: ' . BASE_URL . '/views/listagem/lista_estoques.php?erro=nao_encontrado');
        exit;
    }

    // Confere se o produto do estoque pertence ao fornecedor logado
    $produtoCompleto = $factory->getProdutoDao()->buscaPorId($estoqueAtual->getProduto()->getId());
    if (!$produtoCompleto || $produtoCompleto->getFornecedor() === null
        || $produtoCompleto->getFornecedor()->getId() != $fornecedorId) {
        header('Location: ' . BASE_URL . '/views/listagem/lista_estoques.php?erro=sem_permissao');
        exit;
    }

    // Fornecedor não pode alterar o produto vinculado, apenas quantidade e preço
    $produtoId = (string)$estoqueAtual->getProduto()->getId();
}

$estoque = new Estoque($id, $quantidade, $preco);
if ($produtoId !== '') {
    $estoque->setProduto(new Produto($produtoId, '', '', null));
}

$factory->getEstoqueDao()->altera($estoque);

header('Location: ' . BASE_URL . '/views/listagem/lista_estoques.php');
exit;
?>
