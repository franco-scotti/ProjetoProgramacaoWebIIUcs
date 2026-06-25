<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = isset($_GET["id"]) ? $_GET["id"] : null;

$fornecedor = null;
$endereco = null;

$fornecedorDao = $factory->getFornecedorDao();
$usuarioDao = $factory->getUsuarioDao();
$usuarios = $usuarioDao->buscaTodos();

if ($id) {
    $fornecedor = $fornecedorDao->buscaPorId($id);
    $page_title = "Demo : Alteracao de Fornecedor";

    if ($fornecedor && $fornecedor->getEndereco()) {
        $endereco = $fornecedor->getEndereco();
    }

    $action = BASE_URL . "/app/controllers/altera/altera_fornecedor.php";
    $textoBotao = "Alterar";
} else {
    $page_title = "Demo : Insercao de Fornecedor";

    $action = BASE_URL . "/app/controllers/insere/insere_fornecedor.php";
    $textoBotao = "Inserir";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>

<section>

<form action="<?= $action ?>" method="get">

    <h3>Dados do Fornecedor</h3>

    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Nome</td>
            <td>
                <input type='text' name='nome' class='form-control'
                       value="<?= $fornecedor ? $fornecedor->getNome() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Descricao</td>
            <td>
                <input type='text' name='descricao' class='form-control'
                       value="<?= $fornecedor ? $fornecedor->getDescricao() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Telefone</td>
            <td>
                <input type='text' name='telefone' class='form-control'
                       value="<?= $fornecedor ? $fornecedor->getTelefone() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Email</td>
            <td>
                <input type='text' name='email' class='form-control'
                       value="<?= $fornecedor ? $fornecedor->getEmail() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Usuário</td>
            <td>
                <select name='usuario_id' class='form-control'>
                    <option value=''>Nenhum usuário</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario->getId() ?>"
                            <?= ($fornecedor && $fornecedor->getUsuarioId() == $usuario->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario->getLogin()) ?> - <?= htmlspecialchars($usuario->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

    </table>

    <h3>Endereco</h3>

    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Rua</td>
            <td>
                <input type='text' name='rua' class='form-control'
                       value="<?= $endereco ? $endereco->getRua() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Numero</td>
            <td>
                <input type='text' name='numero' class='form-control'
                       value="<?= $endereco ? $endereco->getNumero() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Complemento</td>
            <td>
                <input type='text' name='complemento' class='form-control'
                       value="<?= $endereco ? $endereco->getComplemento() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Bairro</td>
            <td>
                <input type='text' name='bairro' class='form-control'
                       value="<?= $endereco ? $endereco->getBairro() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>CEP</td>
            <td>
                <input type='text' name='cep' class='form-control'
                       value="<?= $endereco ? $endereco->getCep() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Cidade</td>
            <td>
                <input type='text' name='cidade' class='form-control'
                       value="<?= $endereco ? $endereco->getCidade() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Estado</td>
            <td>
                <input type='text' name='estado' class='form-control'
                       value="<?= $endereco ? $endereco->getEstado() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <?php if ($fornecedor) { ?>
                    <input type="hidden" name="id" value="<?= $fornecedor->getId() ?>">
                <?php } ?>

                <?php if ($endereco) { ?>
                    <input type="hidden" name="endereco_id" value="<?= $endereco->getId() ?>">
                <?php } ?>

                <button type="submit" class="btn btn-primary">
                    <?= $textoBotao ?>
                </button>

                <a href="<?= BASE_URL ?>/views/listagem/lista_fornecedores.php"
                   class="btn btn-primary left-margin">
                    Cancela
                </a>
            </td>
        </tr>

    </table>

</form>

</section>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>