<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$id      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$usuario = null;
$erro    = $_GET['erro'] ?? '';

$dao = $factory->getUsuarioDao();

if ($id) {
    $usuario    = $dao->buscaPorId($id);
    $page_title = "Demo : Alteração de Usuário";
    $action     = BASE_URL . "/app/controllers/altera/altera_usuario.php";
    $textoBotao = "Alterar";
} else {
    $page_title = "Demo : Inserção de Usuário";
    $action     = BASE_URL . "/app/controllers/insere/insere_usuario.php";
    $textoBotao = "Inserir";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

$erros = [
    'login_duplicado'    => 'Esse login já existe. Escolha outro.',
    'campos_obrigatorios'=> 'Preencha login, senha e nome.',
    'erro_insercao'      => 'Não foi possível inserir o usuário.',
];
if ($erro && isset($erros[$erro])) {
    echo "<div class='alert alert-danger'>" . $erros[$erro] . "</div>";
}
?>
<section>
<form action="<?= $action ?>" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Login <span class="text-danger">*</span></td>
            <td><input type='text' name='login' class='form-control'
                       value="<?= $usuario ? htmlspecialchars($usuario->getLogin()) : '' ?>" required /></td>
        </tr>
        <tr>
            <td>Senha <span class="text-danger">*</span></td>
            <td><input type='password' name='senha' class='form-control'
                       value="<?= $usuario ? htmlspecialchars($usuario->getSenha()) : '' ?>" required /></td>
        </tr>
        <tr>
            <td>Nome <span class="text-danger">*</span></td>
            <td><input type='text' name='nome' class='form-control'
                       value="<?= $usuario ? htmlspecialchars($usuario->getNome()) : '' ?>" required /></td>
        </tr>
        <tr>
            <td>Admin</td>
            <td><input type='checkbox' name='admin' value='1'
                       <?= ($usuario && $usuario->isAdmin()) ? 'checked' : '' ?> /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php if ($usuario): ?>
                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>
                <a href="<?= BASE_URL ?>/views/listagem/lista_usuarios.php" class="btn btn-primary left-margin">Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
