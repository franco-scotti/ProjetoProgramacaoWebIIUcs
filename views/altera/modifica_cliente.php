<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

$page_title = "Demo : Alteração de Cliente";

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getClienteDao();
$cliente = $dao->buscaPorId($id);

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>

<section>
<form action="<?= BASE_URL ?>/app/controllers/altera/altera_cliente.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Nome</td><td><input type='text' name='nome' value='<?php echo $cliente->getNome();?>' class='form-control' /></td></tr>
        <tr><td>Telefone</td><td><input type='text' name='telefone' value='<?php echo $cliente->getTelefone();?>' class='form-control' /></td></tr>
        <tr><td>Email</td><td><input type='text' name='email' value='<?php echo $cliente->getEmail();?>' class='form-control' /></td></tr>
        <tr><td>Cartao Credito</td><td><input type='text' name='cartao_credito' value='<?php echo $cliente->getCartaoCredito();?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='<?= BASE_URL ?>/views/listagem/lista_clientes.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $cliente->getId();?>'/>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
