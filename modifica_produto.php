<?php
$page_title = "Demo : Alteracao de Produto";

include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getProdutoDao();
$produto = $dao->buscaPorId($id);
$fornecedorId = $produto->getFornecedor() ? $produto->getFornecedor()->getId() : '';

include_once "layout_header.php";
?>
<section>
<form action="altera_produto.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Nome</td><td><input type='text' name='nome' value='<?php echo $produto->getNome();?>' class='form-control' /></td></tr>
        <tr><td>Descricao</td><td><input type='text' name='descricao' value='<?php echo $produto->getDescricao();?>' class='form-control' /></td></tr>
        <tr><td>Foto (texto/base64)</td><td><input type='text' name='foto' value='<?php echo $produto->getFoto();?>' class='form-control' /></td></tr>
        <tr><td>Fornecedor ID</td><td><input type='text' name='fornecedor_id' value='<?php echo $fornecedorId;?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='produtos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $produto->getId();?>'/>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
