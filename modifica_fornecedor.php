<?php
$page_title = "Demo : Alteracao de Fornecedor";

include_once "fachada.php";

$id = @$_GET["id"];

$dao = $factory->getFornecedorDao();
$fornecedor = $dao->buscaPorId($id);

include_once "layout_header.php";
?>
<section>
<form action="altera_fornecedor.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Nome</td>
            <td><input type='text' name='nome' value='<?php echo $fornecedor->getNome();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>Descricao</td>
            <td><input type='text' name='descricao' value='<?php echo $fornecedor->getDescricao();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>Telefone</td>
            <td><input type='text' name='telefone' value='<?php echo $fornecedor->getTelefone();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type='text' name='email' value='<?php echo $fornecedor->getEmail();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Alterar</button>
                <a href='fornecedores.php' class='btn btn-primary left-margin'>Cancela</a>
            </td>
        </tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $fornecedor->getId();?>'/>
</form>
</section>
<?php
include_once "layout_footer.php";
?>
