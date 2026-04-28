<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];
$login = @$_GET["login"];
$senha = @$_GET["senha"];
$nome = @$_GET["nome"];

$usuario = new Usuario($id,$login,$senha,$nome);
$dao = $factory->getUsuarioDao();

$usuario->setSenha(md5($usuario->getLogin().$usuario->getSenha()));

$dao->altera($usuario);

header("Location: " . BASE_URL . "/views/listagem/lista_usuarios.php");
exit;

?>
