<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];
$pedidoId = trim((string)@$_GET["pedido_id"]);
$dao = $factory->getItemPedidoDao();
$dao->removePorId($id);

if ($pedidoId !== "") {
    header("Location: " . BASE_URL . "/views/detalhes/mostra_pedido.php?id=" . $pedidoId);
} else {
    header("Location: " . BASE_URL . "/views/listagem/lista_pedidos.php");
}
exit;
?>
