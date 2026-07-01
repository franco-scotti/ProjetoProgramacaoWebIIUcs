<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$id   = isset($_GET['id']) ? (int)$_GET['id'] : null;
$erro = $_GET['erro'] ?? '';
$item = null;

$itemDao    = $factory->getItemPedidoDao();
$pedidoDao  = $factory->getPedidoDao();
$produtoDao = $factory->getProdutoDao();

$pedidos  = $pedidoDao->buscaTodos();
$produtos = $produtoDao->buscaTodos();

if ($id) {
    $item       = $itemDao->buscaPorId($id);
    $page_title = "Demo : Alteração de Item de Pedido";
    $action     = BASE_URL . "/app/controllers/altera/altera_item_pedido.php";
    $textoBotao = "Alterar";
    $cancelUrl  = BASE_URL . "/views/detalhes/mostra_pedido.php?id=" . ($item->getPedido() ? $item->getPedido()->getId() : '');
} else {
    $page_title = "Demo : Inserção de Item de Pedido";
    $action     = BASE_URL . "/app/controllers/insere/insere_item_pedido.php";
    $textoBotao = "Inserir";
    $cancelUrl  = BASE_URL . "/views/listagem/lista_pedidos.php";
}

$pedidoAtualId  = $item && $item->getPedido()  ? $item->getPedido()->getId()  : '';
$produtoAtualId = $item && $item->getProduto() ? $item->getProduto()->getId() : '';

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
            <td>Pedido <span class="text-danger">*</span></td>
            <td>
                <select name='pedido_id' class='form-control' required>
                    <option value=''>Selecione um pedido</option>
                    <?php foreach ($pedidos as $pedido): ?>
                        <option value='<?= $pedido->getId() ?>'
                            <?= ($pedido->getId() == $pedidoAtualId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pedido->getNumero()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Produto <span class="text-danger">*</span></td>
            <td>
                <select name='produto_id' class='form-control' required>
                    <option value=''>Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value='<?= $produto->getId() ?>'
                            <?= ($produto->getId() == $produtoAtualId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($produto->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Quantidade <span class="text-danger">*</span></td>
            <td><input type='number' name='quantidade' min='1' class='form-control' required
                       value="<?= $item ? $item->getQuantidade() : '' ?>" /></td>
        </tr>
        <tr>
            <td>Preço <span class="text-danger">*</span></td>
            <td><input type='text' name='preco' class='form-control' required
                       value="<?= $item ? $item->getPreco() : '' ?>" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php if ($item): ?>
                    <input type="hidden" name="id" value="<?= $item->getId() ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>
                <a href="<?= $cancelUrl ?>" class="btn btn-primary left-margin">Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
