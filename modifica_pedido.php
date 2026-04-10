<?php
$page_title = "Demo : Alteracao de Pedido";

include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getPedidoDao();
$pedido = $dao->buscaPorId($id);
$clienteId = $pedido->getCliente() ? $pedido->getCliente()->getId() : '';

include_once "layout_header.php";
?>
<section>
<form action="altera_pedido.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Numero</td><td><input type='text' name='numero' value='<?php echo $pedido->getNumero();?>' class='form-control' /></td></tr>
        <tr><td>Data Pedido</td><td><input type='date' name='data_pedido' value='<?php echo $pedido->getDataPedido();?>' class='form-control' /></td></tr>
        <tr><td>Data Entrega</td><td><input type='date' name='data_entrega' value='<?php echo $pedido->getDataEntrega();?>' class='form-control' /></td></tr>
        <tr><td>Situacao</td><td><input type='text' name='situacao' value='<?php echo $pedido->getSituacao();?>' class='form-control' /></td></tr>
        <tr><td>Cliente ID</td><td><input type='text' name='cliente_id' value='<?php echo $clienteId;?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='pedidos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $pedido->getId();?>'/>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
