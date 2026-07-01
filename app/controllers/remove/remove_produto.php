<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getProdutoDao();
$removido = $dao->removePorId($id);

if (!$removido) {
    header("Location: " . BASE_URL . "/views/listagem/lista_produtos.php?erro=dependencia");
    exit;
}

header("Location: " . BASE_URL . "/views/listagem/lista_produtos.php");
exit;
?>
