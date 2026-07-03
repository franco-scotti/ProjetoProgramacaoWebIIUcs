<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo         = $_SESSION['usuario_tipo'] ?? '';
$fornecedorId = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                ? (int)$_SESSION['usuario_fornecedor_id']
                : null;
$isAdmin      = ($tipo === 'admin');

if (!$isAdmin && $tipo !== 'fornecedor') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$id         = isset($_GET['id']) ? (int)$_GET['id'] : null;
$erro       = $_GET['erro'] ?? '';
$produto    = null;
$prodFornId = $fornecedorId;

$produtoDao    = $factory->getProdutoDao();
$fornecedorDao = $factory->getFornecedorDao();

if ($id) {
    $produto    = $produtoDao->buscaPorId($id);
    $page_title = "Demo : Alteração de Produto";

    if ($produto && $produto->getFornecedor()) {
        $prodFornId = (int)$produto->getFornecedor()->getId();

        if (!$isAdmin && $fornecedorId !== null && $prodFornId !== $fornecedorId) {
            header('Location: ' . BASE_URL . '/views/listagem/lista_produtos.php?erro=sem_permissao');
            exit;
        }
    }

    $action     = BASE_URL . "/app/controllers/altera/altera_produto.php";
    $textoBotao = "Alterar";
} else {
    $page_title = "Demo : Inserção de Produto";
    $action     = BASE_URL . "/app/controllers/insere/insere_produto.php";
    $textoBotao = "Inserir";
}

$fornecedores = $isAdmin ? $fornecedorDao->buscaTodos() : [];

include_once dirname(__DIR__) . "/layout/layout_header.php";

$erros = [
    'campos_obrigatorios' => 'Preencha todos os campos obrigatórios.',
];
if ($erro && isset($erros[$erro])) {
    echo "<div class='alert alert-danger'>" . $erros[$erro] . "</div>";
}
?>

<section>
    <form action="<?= $action ?>" method="post" enctype="multipart/form-data">

        <table class="table table-hover table-responsive table-bordered">

            <tr>
                <td>Nome <span class="text-danger">*</span></td>
                <td>
                    <input type="text" name="nome" class="form-control"
                           value="<?= $produto ? htmlspecialchars($produto->getNome()) : '' ?>" required />
                </td>
            </tr>

            <tr>
                <td>Descrição <span class="text-danger">*</span></td>
                <td>
                    <input type="text" name="descricao" class="form-control"
                           value="<?= $produto ? htmlspecialchars($produto->getDescricao()) : '' ?>" required />
                </td>
            </tr>

            <tr>
                <td>Foto</td>
                <td>
                    <input type="file" name="foto" class="form-control" accept="image/*" <?= $produto ? '' : 'required' ?> />
                    <?php
                    $foto = $produto ? $produto->getFoto() : null;
                    if ($foto) {
                        if (is_resource($foto)) $foto = stream_get_contents($foto);
                        $srcFoto = strpos($foto, 'data:image') === 0
                            ? $foto
                            : 'data:image/jpeg;base64,' . base64_encode($foto);
                        echo "<img src='{$srcFoto}' style='max-width:300px;margin-top:8px;border:1px solid #ccc;padding:5px;'>";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td>Fornecedor <span class="text-danger">*</span></td>
                <td>
                    <?php if ($isAdmin): ?>
                        <!-- Admin escolhe livremente -->
                        <select name="fornecedor_id" class="form-control" required>
                            <option value="">Selecione um fornecedor</option>
                            <?php foreach ($fornecedores as $f): ?>
                                <option value="<?= $f->getId() ?>"
                                    <?= ($f->getId() == $prodFornId) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($f->getNome()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    <?php else: ?>
                        <input type="hidden" name="fornecedor_id" value="<?= $fornecedorId ?>">
                        <span class="form-control" style="background:#f5f5f5;cursor:default">
                            <?php
                            $fornecedorLogado = $fornecedorDao->buscaPorId($fornecedorId);
                            echo $fornecedorLogado ? htmlspecialchars($fornecedorLogado->getNome()) : "ID {$fornecedorId}";
                            ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <?php if ($produto): ?>
                        <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>

                    <a href="<?= BASE_URL ?>/views/listagem/lista_produtos.php" class="btn btn-default left-margin">
                        Cancelar
                    </a>
                </td>
            </tr>

        </table>
    </form>
</section>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
