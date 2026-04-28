<?php
$page_title = "Demo : Insercao de Cliente";
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/insere/insere_cliente.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Nome</td><td><input type='text' name='nome' class='form-control' /></td></tr>
        <tr><td>Telefone</td><td><input type='text' name='telefone' class='form-control' /></td></tr>
        <tr><td>Email</td><td><input type='text' name='email' class='form-control' /></td></tr>
        <tr><td>Cartao Credito</td><td><input type='text' name='cartao_credito' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Inserir</button> <a href='<?= BASE_URL ?>/views/listagem/lista_clientes.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
