<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

include_once dirname(__DIR__) . "/valida_campos.php";

$numero = trim((string)@$_GET["numero"]);
$dataPedido = trim((string)@$_GET["data_pedido"]);
$dataEntrega = trim((string)@$_GET["data_entrega"]);
$situacao = trim((string)@$_GET["situacao"]);
$clienteId = trim((string)@$_GET["cliente_id"]);
$campos = ['numero','data_pedido','data_entrega','situacao','cliente_id'];
$dados = ['numero' => $numero, 'data_pedido' => $dataPedido, 'data_entrega' => $dataEntrega, 'situacao' => $situacao, 'cliente_id' => $clienteId];

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_pedido.php?erro=campos_obrigatorios");
    exit;
}

$pedido = new Pedido(null, $numero, $dataPedido, $dataEntrega, $situacao);
if ($clienteId !== "") {
    $pedido->setCliente(new Cliente($clienteId, '', '', '', ''));
}

$dao = $factory->getPedidoDao();
$dao->insere($pedido);

header("Location: " . BASE_URL . "/views/listagem/lista_pedidos.php");
exit;
?>
