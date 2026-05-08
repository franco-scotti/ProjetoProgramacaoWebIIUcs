<?php
$page_title = "Demo : Alteracao de Produto";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getProdutoDao();
$produto = $dao->buscaPorId($id);
$fornecedorId = $produto->getFornecedor() ? $produto->getFornecedor()->getId() : '';
$fornecedorDao = $factory->getFornecedorDao();
$fornecedores = $fornecedorDao->buscaTodos();

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<form action="<?= BASE_URL ?>/app/controllers/altera/altera_produto.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Nome</td><td><input type='text' name='nome' value='<?php echo $produto->getNome();?>' class='form-control' /></td></tr>
        <tr><td>Descricao</td><td><input type='text' name='descricao' value='<?php echo $produto->getDescricao();?>' class='form-control' /></td></tr>
        <tr><td>Foto (texto/base64)</td><td><input type='text' name='foto' value='<?php echo $produto->getFoto();?>' class='form-control' /></td></tr>
        <tr><td>Fornecedor</td>
                <td>
                    <select name="fornecedor_id" class="form-control">
                        <option value="">Selecione um fornecedor</option>

                        <?php foreach ($fornecedores as $fornecedor) { ?>

                            <option value="<?= $fornecedor->getId() ?>"
                                <?= ($fornecedor->getId() == $fornecedorId) ? 'selected' : '' ?>>

                                <?= $fornecedor->getNome() ?>

                            </option>

                        <?php } ?>

                    </select>
                </td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='<?= BASE_URL ?>/views/listagem/lista_produtos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $produto->getId();?>'/>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
