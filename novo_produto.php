<?php
$page_title = "Demo : Insercao de Produto";
include_once "layout_header.php";
?>
<section>
<form action="insere_produto.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Nome</td><td><input type='text' name='nome' class='form-control' /></td></tr>
        <tr><td>Descricao</td><td><input type='text' name='descricao' class='form-control' /></td></tr>
        <tr><td>Foto (texto/base64)</td><td><input type='text' name='foto' class='form-control' /></td></tr>
        <tr><td>Fornecedor ID</td><td><input type='text' name='fornecedor_id' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Inserir</button> <a href='produtos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
