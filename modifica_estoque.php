<?php
$page_title = "Demo : Alteracao de Estoque";

include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEstoqueDao();
$estoque = $dao->buscaPorId($id);
$produtoId = $estoque->getProduto() ? $estoque->getProduto()->getId() : '';

include_once "layout_header.php";
?>
<section>
<form action="altera_estoque.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Produto ID</td><td><input type='text' name='produto_id' value='<?php echo $produtoId;?>' class='form-control' /></td></tr>
        <tr><td>Quantidade</td><td><input type='text' name='quantidade' value='<?php echo $estoque->getQuantidade();?>' class='form-control' /></td></tr>
        <tr><td>Preco</td><td><input type='text' name='preco' value='<?php echo $estoque->getPreco();?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='estoques.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $estoque->getId();?>'/>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
