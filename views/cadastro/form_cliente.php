<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

$id = isset($_GET["id"]) ? $_GET["id"] : null;
$tipoUsuario = $_SESSION['usuario_tipo'] ?? null;
$clienteLogadoId = isset($_SESSION['usuario_cliente_id']) ? (int)$_SESSION['usuario_cliente_id'] : 0;

if ($tipoUsuario === 'cliente') {
    if ($id === null || $id === '') {
        $id = $clienteLogadoId ?: null;
    }
    if ($id !== null && (int)$id !== $clienteLogadoId) {
        header('Location: ' . BASE_URL . '/public/portal_cliente.php');
        exit;
    }
} elseif ($tipoUsuario !== 'admin') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$erro = $_GET['erro'] ?? '';
$cliente = null;
$endereco = null;

$clienteDao = $factory->getClienteDao();
$usuarioDao = $factory->getUsuarioDao();
$usuarios = $usuarioDao->buscaTodos();

if ($id) {
    $cliente = $clienteDao->buscaPorId($id);
    $page_title = "Demo : Alteracao de Cliente";

    if ($cliente && $cliente->getEndereco()) {
        $endereco = $cliente->getEndereco();
    }

    $action = BASE_URL . "/app/controllers/altera/altera_cliente.php";
    $textoBotao = "Alterar";
} else {
    $page_title = "Demo : Insercao de Cliente";

    $action = BASE_URL . "/app/controllers/insere/insere_cliente.php";
    $textoBotao = "Inserir";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

$erros = [
    'campos_obrigatorios' => 'Preencha todos os campos obrigatórios.',
    'erro_insercao' => 'Não foi possível salvar o cliente.',
    'erro_alteracao' => 'Não foi possível alterar o cliente.',
];
if ($erro && isset($erros[$erro])) {
    echo "<div class='alert alert-danger'>" . $erros[$erro] . "</div>";
}
?>

<section>

<form action="<?= $action ?>" method="get">

    <h3>Dados do Cliente</h3>

    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Nome <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='nome' class='form-control' required
                       value="<?= $cliente ? $cliente->getNome() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Telefone <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='telefone' class='form-control' required
                       value="<?= $cliente ? $cliente->getTelefone() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Email <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='email' class='form-control' required
                       value="<?= $cliente ? $cliente->getEmail() : '' ?>" />
            </td>
        </tr>

        <?php if ($tipoUsuario === 'cliente'): ?>
        <tr>
            <td>Usuário</td>
            <td>
                <input type='text' class='form-control' value="<?= htmlspecialchars($_SESSION['nome_usuario'] ?? '') ?>" disabled />
                <input type='hidden' name='usuario_id' value="<?= htmlspecialchars((string)($_SESSION['id_usuario'] ?? '')) ?>" />
            </td>
        </tr>
        <?php else: ?>
        <tr>
            <td>Usuário</td>
            <td>
                <select name='usuario_id' class='form-control'>
                    <option value=''>Nenhum usuário</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario->getId() ?>"
                            <?= ($cliente && $cliente->getUsuarioId() == $usuario->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario->getLogin()) ?> - <?= htmlspecialchars($usuario->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <?php endif; ?>

        <tr>
            <td>Cartao Credito <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='cartao_credito' class='form-control' required
                       value="<?= $cliente ? $cliente->getCartaoCredito() : '' ?>" />
            </td>
        </tr>

    </table>

    <h3>Endereco</h3>

    <table class='table table-hover table-responsive table-bordered'>

        <tr>
            <td>Rua <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='rua' class='form-control' required
                       value="<?= $endereco ? $endereco->getRua() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Numero <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='numero' class='form-control' required
                       value="<?= $endereco ? $endereco->getNumero() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Complemento <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='complemento' class='form-control' required
                       value="<?= $endereco ? $endereco->getComplemento() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Bairro <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='bairro' class='form-control' required
                       value="<?= $endereco ? $endereco->getBairro() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>CEP <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='cep' class='form-control' required
                       value="<?= $endereco ? $endereco->getCep() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Cidade <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='cidade' class='form-control' required
                       value="<?= $endereco ? $endereco->getCidade() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td>Estado <span class="text-danger">*</span></td>
            <td>
                <input type='text' name='estado' class='form-control' required
                       value="<?= $endereco ? $endereco->getEstado() : '' ?>" />
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <?php if ($cliente) { ?>
                    <input type="hidden" name="id" value="<?= $cliente->getId() ?>">
                <?php } ?>

                <?php if ($endereco) { ?>
                    <input type="hidden" name="endereco_id" value="<?= $endereco->getId() ?>">
                <?php } ?>

                <button type="submit" class="btn btn-primary">
                    <?= $textoBotao ?>
                </button>

                <a href="<?= $tipoUsuario === 'cliente' ? BASE_URL . '/public/portal_cliente.php' : BASE_URL . '/views/listagem/lista_clientes.php' ?>"
                   class="btn btn-primary left-margin">
                    Cancela
                </a>
            </td>
        </tr>

    </table>

</form>

</section>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>