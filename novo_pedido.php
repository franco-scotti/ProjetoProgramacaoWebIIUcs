<?php
$page_title = "Demo : Insercao de Pedido";
include_once "layout_header.php";
?>
<section>
<form action="insere_pedido.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Numero</td><td><input type='text' name='numero' class='form-control' /></td></tr>
        <tr><td>Data Pedido</td><td><input type='date' name='data_pedido' class='form-control' /></td></tr>
        <tr><td>Data Entrega</td><td><input type='date' name='data_entrega' class='form-control' /></td></tr>
        <tr><td>Situacao</td><td><input type='text' name='situacao' value='NOVO' class='form-control' /></td></tr>
        <tr><td>Cliente ID</td><td><input type='text' name='cliente_id' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Inserir</button> <a href='pedidos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
