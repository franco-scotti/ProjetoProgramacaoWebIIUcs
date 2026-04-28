<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getItemPedidoDao();
$dao->removePorId($id);

header("Location: " . BASE_URL . "/views/listagem/lista_itens_pedido.php");
exit;
?>
