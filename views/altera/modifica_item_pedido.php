<?php
$page_title = "Demo : Alteracao de Item de Pedido";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getItemPedidoDao();
$item = $dao->buscaPorId($id);
$pedidoId = $item->getPedido() ? $item->getPedido()->getId() : '';
$produtoId = $item->getProduto() ? $item->getProduto()->getId() : '';
$pedidoDao = $factory->getPedidoDao();
$produtoDao = $factory->getProdutoDao();
$pedidos = $pedidoDao->buscaTodos();
$produtos = $produtoDao->buscaTodos();

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/altera/altera_item_pedido.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Pedido</td><td><select name='pedido_id' class='form-control'>
            <option value="">Selecione um pedido</option>

            <?php foreach ($pedidos as $pedido): ?>
                <option value="<?= $pedido->getId() ?>"
                                <?= ($pedido->getId() == $pedidoId) ? 'selected' : '' ?>>

                                <?= $pedido->getNumero() ?>

                            </option>
            <?php endforeach; ?>
        </select></td></tr>
        <tr><td>Produto</td><td><select name='produto_id' class='form-control'>
            <option value="">Selecione um produto</option>
            <?php foreach ($produtos as $produto): ?>
                <option value='<?= $produto->getId() ?>'
                        <?= ($produto->getId() == $produtoId) ? 'selected' : '' ?>>

                        <?= $produto->getNome() ?>

                    </option>
            <?php endforeach; ?>
        </select></td></tr>
        <tr><td>Quantidade</td><td><input type='text' name='quantidade' value='<?php echo $item->getQuantidade();?>' class='form-control' /></td></tr>
        <tr><td>Preco</td><td><input type='text' name='preco' value='<?php echo $item->getPreco();?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='<?= BASE_URL ?>/views/detalhes/mostra_pedido.php?id=<?php echo $pedidoId ?>' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $item->getId();?>'/>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
