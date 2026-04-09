<?php
include_once "fachada.php";

$id = @$_GET["id"];
$login = @$_GET["login"];
$senha = @$_GET["senha"];
$nome = @$_GET["nome"];

$usuario = new Usuario($id,$login,$senha,$nome);
$dao = $factory->getUsuarioDao();

$usuario->setSenha(md5($usuario->getLogin().$usuario->getSenha()));

$dao->altera($usuario);

header("Location: usuarios.php");
exit;

?>
