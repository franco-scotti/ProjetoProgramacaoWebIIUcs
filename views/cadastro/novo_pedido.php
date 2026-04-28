<?php
$page_title = "Demo : Insercao de Pedido";
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/insere/insere_pedido.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Numero</td><td><input type='text' name='numero' class='form-control' /></td></tr>
        <tr><td>Data Pedido</td><td><input type='date' name='data_pedido' class='form-control' /></td></tr>
        <tr><td>Data Entrega</td><td><input type='date' name='data_entrega' class='form-control' /></td></tr>
        <tr><td>Situacao</td><td><input type='text' name='situacao' value='NOVO' class='form-control' /></td></tr>
        <tr><td>Cliente ID</td><td><input type='text' name='cliente_id' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Inserir</button> <a href='<?= BASE_URL ?>/views/listagem/lista_pedidos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
