<?php
$page_title = "Demo : Alteracao de Estoque";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEstoqueDao();
$estoque = $dao->buscaPorId($id);
$produtoId = $estoque->getProduto() ? $estoque->getProduto()->getId() : '';

include_once dirname(__DIR__) . "/layout/layout_header.php";
    ?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/altera/altera_estoque.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Produto ID</td><td><input type='text' name='produto_id' value='<?php echo $produtoId;?>' class='form-control' /></td></tr>
        <tr><td>Quantidade</td><td><input type='text' name='quantidade' value='<?php echo $estoque->getQuantidade();?>' class='form-control' /></td></tr>
        <tr><td>Preco</td><td><input type='text' name='preco' value='<?php echo $estoque->getPreco();?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='<?= BASE_URL ?>/views/listagem/lista_estoques.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $estoque->getId();?>'/>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
