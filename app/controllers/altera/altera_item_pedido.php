<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

$id = @$_GET["id"];
$pedidoId = trim((string)@$_GET["pedido_id"]);
$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);
$campos = ['pedido_id','produto_id','quantidade','preco'];
$dados = ['pedido_id' => $pedidoId, 'produto_id' => $produtoId, 'quantidade' => $quantidade, 'preco' => $preco];

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_item_pedido.php?id=" . $id . "&erro=campos_obrigatorios");
    exit;
}

$item = new ItemPedido($id, $quantidade, $preco);
if ($pedidoId !== "") {
    $item->setPedido(new Pedido($pedidoId, null, null, null, null));
}
if ($produtoId !== "") {
    $item->setProduto(new Produto($produtoId, '', '', null));
}

$dao = $factory->getItemPedidoDao();
$dao->altera($item);

if ($pedidoId !== "") {
    header("Location: " . BASE_URL . "/views/detalhes/mostra_pedido.php?id=" . $pedidoId);
} else {
    header("Location: " . BASE_URL . "/views/listagem/lista_pedidos.php");
}
exit;
?>
