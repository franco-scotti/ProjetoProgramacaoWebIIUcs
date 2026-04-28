<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];

$usuario = new Usuario($id,$login,$senha,$nome);
$dao = $factory->getUsuarioDao();
$dao->removePorId($id);

header("Location: " . BASE_URL . "/views/listagem/lista_usuarios.php");
exit;

?>