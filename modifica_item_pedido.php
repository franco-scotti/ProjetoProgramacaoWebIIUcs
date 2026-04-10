<?php
$page_title = "Demo : Alteracao de Item de Pedido";

include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getItemPedidoDao();
$item = $dao->buscaPorId($id);
$pedidoId = $item->getPedido() ? $item->getPedido()->getId() : '';
$produtoId = $item->getProduto() ? $item->getProduto()->getId() : '';

include_once "layout_header.php";
?>
<section>
<form action="altera_item_pedido.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Pedido ID</td><td><input type='text' name='pedido_id' value='<?php echo $pedidoId;?>' class='form-control' /></td></tr>
        <tr><td>Produto ID</td><td><input type='text' name='produto_id' value='<?php echo $produtoId;?>' class='form-control' /></td></tr>
        <tr><td>Quantidade</td><td><input type='text' name='quantidade' value='<?php echo $item->getQuantidade();?>' class='form-control' /></td></tr>
        <tr><td>Preco</td><td><input type='text' name='preco' value='<?php echo $item->getPreco();?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='itens_pedido.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $item->getId();?>'/>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
