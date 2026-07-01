<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];

$usuario = new Usuario($id,$login,$senha,$nome);
$dao = $factory->getUsuarioDao();
$removido = $dao->removePorId($id);

if (!$removido) {
    header("Location: " . BASE_URL . "/views/listagem/lista_usuarios.php?erro=dependencia");
    exit;
}

header("Location: " . BASE_URL . "/views/listagem/lista_usuarios.php");
exit;

?>