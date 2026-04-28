<?php
$page_title = "Demo : Insercao de Estoque";
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/insere/insere_estoque.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Produto ID</td><td><input type='text' name='produto_id' class='form-control' /></td></tr>
        <tr><td>Quantidade</td><td><input type='text' name='quantidade' class='form-control' /></td></tr>
        <tr><td>Preco</td><td><input type='text' name='preco' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Inserir</button> <a href='<?= BASE_URL ?>/views/listagem/lista_estoques.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
