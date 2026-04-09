<?php
include_once "fachada.php";

$login = @$_GET["login"];
$senha = @$_GET["senha"];
$nome = @$_GET["nome"];

$login = trim((string)$login);
$senha = trim((string)$senha);
$nome = trim((string)$nome);

$dao = $factory->getUsuarioDao();

if ($login === "" || $senha === "" || $nome === "") {
    header("Location: novo_usuario.php?erro=campos_obrigatorios");
    exit;
}

if ($dao->buscaPorLogin($login) !== null) {
    header("Location: novo_usuario.php?erro=login_duplicado");
    exit;
}

$usuario = new Usuario(null,$login,$senha,$nome);
$ok = $dao->insere($usuario);

if(!$ok){
    header("Location: novo_usuario.php?erro=erro_insercao");
    exit;
}

header("Location: usuarios.php");
exit;

?>
