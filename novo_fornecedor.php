<?php
$page_title = "Demo : Insercao de Fornecedor";
include_once "layout_header.php";
?>
<section>
<form action="insere_fornecedor.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Nome</td>
            <td><input type='text' name='nome' class='form-control' /></td>
        </tr>
        <tr>
            <td>Descricao</td>
            <td><input type='text' name='descricao' class='form-control' /></td>
        </tr>
        <tr>
            <td>Telefone</td>
            <td><input type='text' name='telefone' class='form-control' /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type='text' name='email' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Inserir</button>
                <a href='fornecedores.php' class='btn btn-primary left-margin'>Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>
<?php
include_once "layout_footer.php";
?>
