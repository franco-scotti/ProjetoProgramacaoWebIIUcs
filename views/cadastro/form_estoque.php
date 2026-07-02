<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo         = $_SESSION['usuario_tipo'] ?? '';
$fornecedorId = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                ? (int)$_SESSION['usuario_fornecedor_id'] : null;
$isAdmin      = ($tipo === 'admin');
$isFornecedor = ($tipo === 'fornecedor');

if (!$isAdmin && !$isFornecedor) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$erro    = $_GET['erro'] ?? '';
$estoque = null;

$estoqueDao = $factory->getEstoqueDao();
$produtoDao = $factory->getProdutoDao();

if ($id) {
    $estoque    = $estoqueDao->buscaPorId($id);
    $page_title = "Demo : Alteração de Estoque";
    $action     = BASE_URL . "/app/controllers/altera/altera_estoque.php";
    $textoBotao = "Alterar";

    // Fornecedor só pode editar estoque dos seus produtos
    if ($isFornecedor && $estoque && $estoque->getProduto()) {
        $prodCompleto = $produtoDao->buscaPorId($estoque->getProduto()->getId());
        if (!$prodCompleto || $prodCompleto->getFornecedor() === null
            || $prodCompleto->getFornecedor()->getId() != $fornecedorId) {
            header('Location: ' . BASE_URL . '/views/listagem/lista_estoques.php?erro=sem_permissao');
            exit;
        }
    }
} else {
    $page_title = "Demo : Inserção de Estoque";
    $action     = BASE_URL . "/app/controllers/insere/insere_estoque.php";
    $textoBotao = "Inserir";
}

// Lista de produtos: fornecedor vê apenas os seus
$produtos = ($fornecedorId !== null)
    ? $produtoDao->buscaPorFornecedorId($fornecedorId)
    : $produtoDao->buscaTodos();

$produtoAtualId = $estoque && $estoque->getProduto() ? $estoque->getProduto()->getId() : '';

include_once dirname(__DIR__) . "/layout/layout_header.php";

$erros = [
    'campos_obrigatorios' => 'Preencha todos os campos obrigatórios.',
];
if ($erro && isset($erros[$erro])) {
    echo "<div class='alert alert-danger'>" . $erros[$erro] . "</div>";
}
?>
<section>
<form action="<?= $action ?>" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Produto <span class="text-danger">*</span></td>
            <td>
                <?php if ($isFornecedor && $id): ?>
                    <!-- Fornecedor não troca o produto na edição -->
                    <input type="hidden" name="produto_id" value="<?= htmlspecialchars($produtoAtualId) ?>">
                    <span class="form-control" style="background:#f5f5f5">
                        <?= $estoque->getProduto() ? htmlspecialchars($estoque->getProduto()->getNome()) : '—' ?>
                    </span>
                <?php else: ?>
                    <select name="produto_id" class="form-control" required>
                        <option value="">Selecione um produto</option>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto->getId() ?>"
                                <?= ($produto->getId() == $produtoAtualId) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($produto->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Quantidade <span class="text-danger">*</span></td>
            <td><input type='number' name='quantidade' min='0' class='form-control' required
                       value="<?= $estoque ? $estoque->getQuantidade() : '' ?>" /></td>
        </tr>
        <tr>
            <td>Preço <span class="text-danger">*</span></td>
            <td><input type='text' name='preco' class='form-control' required
                       value="<?= $estoque ? $estoque->getPreco() : '' ?>" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php if ($estoque): ?>
                    <input type="hidden" name="id" value="<?= $estoque->getId() ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>
                <a href="<?= BASE_URL ?>/views/listagem/lista_estoques.php" class="btn btn-primary left-margin">Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>
<script>
(function(){
    var preco = document.querySelector('input[name="preco"]');
    if (!preco) return;

    // Prevent typing commas or other non-digit/dot characters
    preco.addEventListener('keydown', function(e){
        var allowed = ['Backspace','Tab','ArrowLeft','ArrowRight','Delete','Enter'];
        if (allowed.indexOf(e.key) !== -1) return;
        if (e.key === ',') { e.preventDefault(); return; }
        if (e.key === '.') {
            if (this.value.indexOf('.') !== -1) e.preventDefault();
            return;
        }
        if (!/^\d$/.test(e.key)) e.preventDefault();
    });

    // Clean pasted content
    preco.addEventListener('paste', function(e){
        var paste = (e.clipboardData || window.clipboardData).getData('text');
        if (!/^[0-9.]+$/.test(paste) || (paste.match(/\./g) || []).length > 1) {
            e.preventDefault();
        }
    });

    // Keep only digits and a single dot while typing
    preco.addEventListener('input', function(){
        var v = this.value;
        v = v.replace(/[^0-9.]/g, '');
        var parts = v.split('.');
        if (parts.length > 2) {
            v = parts.shift() + '.' + parts.join('');
        }
        this.value = v;
    });

    // On blur, format to two decimals using dot as decimal separator
    preco.addEventListener('blur', function(){
        var v = this.value.trim();
        if (v === '') return;
        // Ensure only one dot and valid numeric string
        v = v.replace(/,/g, '.');
        v = v.replace(/[^0-9.]/g, '');
        var parts = v.split('.');
        var intPart = parts.shift() || '0';
        var decPart = parts.join('').slice(0,2);
        var combined = intPart + (decPart !== '' ? '.' + decPart : '');
        var n = parseFloat(combined);
        if (isNaN(n)) {
            this.value = '';
        } else {
            this.value = n.toFixed(2);
        }
    });

})();
</script>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
