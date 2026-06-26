<?php
$page_title = "Demo : Alteração de Estoque";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo = $_SESSION['usuario_tipo'] ?? '';
if (!in_array($tipo, ['admin', 'fornecedor'])) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id           = (int)($_GET['id'] ?? 0);
$dao          = $factory->getEstoqueDao();
$estoque      = $dao->buscaPorId($id);
$isFornecedor = ($tipo === 'fornecedor');
$fornecedorId = $isFornecedor ? (int)($_SESSION['usuario_fornecedor_id'] ?? 0) : null;

// Fornecedor: bloqueia se o produto não for dele
if ($isFornecedor) {
    $produtoCompleto = $estoque && $estoque->getProduto()
        ? $factory->getProdutoDao()->buscaPorId($estoque->getProduto()->getId())
        : null;

    if (!$produtoCompleto || $produtoCompleto->getFornecedor() === null
        || $produtoCompleto->getFornecedor()->getId() != $fornecedorId) {
        header('Location: ' . BASE_URL . '/views/listagem/lista_estoques.php?erro=sem_permissao');
        exit;
    }
}

$produtoId = $estoque->getProduto() ? $estoque->getProduto()->getId() : '';

// Admin pode trocar o produto; fornecedor não
if (!$isFornecedor) {
    $produtos = $factory->getProdutoDao()->buscaTodos();
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/altera/altera_estoque.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Produto</td>
            <td>
                <?php if ($isFornecedor): ?>
                    <!-- Fornecedor vê o produto mas não pode alterar -->
                    <input type="hidden" name="produto_id" value="<?= htmlspecialchars($produtoId) ?>">
                    <span class="form-control" style="background:#f5f5f5">
                        <?= $estoque->getProduto() ? htmlspecialchars($estoque->getProduto()->getNome()) : '—' ?>
                    </span>
                <?php else: ?>
                    <select name="produto_id" class="form-control">
                        <option value="">Selecione um produto</option>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto->getId() ?>"
                                <?= ($produto->getId() == $produtoId) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($produto->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Quantidade</td>
            <td><input type='number' name='quantidade' value='<?= $estoque->getQuantidade() ?>' min='0' class='form-control' /></td>
        </tr>
        <tr>
            <td>Preço</td>
            <td><input type='text' name='preco' value='<?= $estoque->getPreco() ?>' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Alterar</button>
                <a href='<?= BASE_URL ?>/views/listagem/lista_estoques.php' class='btn btn-default left-margin'>Cancelar</a>
            </td>
        </tr>
    </table>
    <input type='hidden' name='id' value='<?= $estoque->getId() ?>'/>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
