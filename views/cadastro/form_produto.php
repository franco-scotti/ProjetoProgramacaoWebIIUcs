<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = isset($_GET["id"]) ? $_GET["id"] : null;

$produto = null;
$fornecedorId = '';

$produtoDao = $factory->getProdutoDao();
$fornecedorDao = $factory->getFornecedorDao();

if ($id) {
    $produto = $produtoDao->buscaPorId($id);
    $page_title = "Demo : Alteracao de Produto";

    if ($produto && $produto->getFornecedor()) {
        $fornecedorId = $produto->getFornecedor()->getId();
    }

    $action = BASE_URL . "/app/controllers/altera/altera_produto.php";
    $textoBotao = "Alterar";
} else {
    $page_title = "Demo : Insercao de Produto";

    $action = BASE_URL . "/app/controllers/insere/insere_produto.php";
    $textoBotao = "Inserir";
}

$fornecedores = $fornecedorDao->buscaTodos();

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>

<section>
    <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
        <table class="table table-hover table-responsive table-bordered">

            <tr>
                <td>Nome</td>
                <td>
                    <input type="text" name="nome" class="form-control"
                           value="<?= $produto ? $produto->getNome() : '' ?>" />
                </td>
            </tr>

            <tr>
                <td>Descrição</td>
                <td>
                    <input type="text" name="descricao" class="form-control"
                           value="<?= $produto ? $produto->getDescricao() : '' ?>" />
                </td>
            </tr>

            <tr>
                <td>Foto</td>
                <td>
                    <input type="file" name="foto" class="form-control" />

                    <?php $foto = $produto->getFoto();
                        if ($foto) {
                            if (strpos($foto, 'data:image') === 0) {
                                $srcFoto = $foto;
                            } else {
                                $srcFoto = 'data:image/jpeg;base64,' . $foto;
                            }
                            echo "<img src='" . $srcFoto . "' 
                                    style='max-width:300px; border:1px solid #ccc; padding:5px;'>";
                        } ?>
                </td>
            </tr>

            <tr>
                <td>Fornecedor</td>
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
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <?php if ($produto) { ?>
                        <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                    <?php } ?>

                    <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>

                    <a href="<?= BASE_URL ?>/views/listagem/lista_produtos.php" class="btn btn-primary left-margin">
                        Cancelar
                    </a>
                </td>
            </tr>

        </table>
    </form>
</section>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>