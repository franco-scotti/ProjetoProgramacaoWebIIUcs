<?php
$page_title = "Demo : Insercao de Produto";
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$fornecedorDao = $factory->getFornecedorDao();
$fornecedores = $fornecedorDao->buscaTodos();
?>

<section>
    <form action="<?= BASE_URL ?>/app/controllers/insere/insere_produto.php" method="get">
        <table class="table table-hover table-responsive table-bordered">
            <tr>
                <td>Nome</td>
                <td><input type="text" name="nome" class="form-control" /></td>
            </tr>
            <tr>
                <td>Descrição</td>
                <td><input type="text" name="descricao" class="form-control" /></td>
            </tr>
            <tr>
                <td>Foto (texto/base64)</td>
                <td><input type="text" name="foto" class="form-control" /></td>
            </tr>
            <tr>
                <td>Fornecedor</td>
                <td>
                    <select name="fornecedor_id" class="form-control">
                        <option value="">Selecione um fornecedor</option>

                        <?php foreach ($fornecedores as $fornecedor) { ?>
                            <option value="<?= $fornecedor->getId() ?>">
                                <?= $fornecedor->getNome() ?>
                            </option>
                        <?php } ?>

                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary">Inserir</button>
                    <a href="<?= BASE_URL ?>/views/listagem/lista_produtos.php" class="btn btn-primary left-margin">Cancelar</a>
                </td>
            </tr>
        </table>
    </form>
</section>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
