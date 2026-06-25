<?php
$page_title = "Demo : Alteração de Usuário";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$id = @$_GET["id"];

$dao = $factory->getUsuarioDao();
$usuario = $dao->buscaPorId($id);

include_once dirname(__DIR__) . "/layout/layout_header.php";
 ?>
 <section>
<form action="<?= BASE_URL ?>/app/controllers/altera/altera_usuario.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
         <tr>
            <td>Login</td>
            <td><input type='text' name='login' value='<?php echo $usuario->getLogin();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>Nome</td>
            <td><input type='text' name='nome' value='<?php echo $usuario->getNome();?>'class='form-control' /></td>
        </tr>
        <tr>
            <td>Senha</td>
            <td><input type='password' name='senha' value='<?php echo $usuario->getSenha();?>' class='form-control' /></td>
        </tr>
        <tr>
            <td>Admin</td>
            <td><input type='checkbox' name='admin' value='1' <?= $usuario->isAdmin() ? 'checked' : '' ?> /></td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                <button type="submit" class="btn btn-primary">Alterar</button>
                <a href='<?= BASE_URL ?>/views/listagem/lista_usuarios.php' class='btn btn-primary left-margin'>Cancela</a>
            </td>
        </tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $usuario->getId();?>'/>
</form>
</section>
<?php
include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>


