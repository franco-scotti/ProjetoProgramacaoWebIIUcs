<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";
include_once dirname(__DIR__) . "/login/comum.php";
if (is_session_started() === FALSE) session_start();

$id = @$_GET["id"];
$pedidoId = trim((string)@$_GET["pedido_id"]);
$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);
$campos = ['pedido_id','produto_id','quantidade','preco'];
$dados = ['pedido_id' => $pedidoId, 'produto_id' => $produtoId, 'quantidade' => $quantidade, 'preco' => $preco];

$clienteId = $_SESSION['usuario_cliente_id'] ?? $_SESSION['checkout_cliente_id'] ?? null;
if (!$clienteId) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_item_pedido.php?id=" . $id . "&erro=campos_obrigatorios");
    exit;
}

$conn = $factory->getConnection();
$stmt = $conn->prepare(
    "SELECT ip.quantidade AS quantidade_atual,
            ip.produto_id,
            p.situacao,
            e.id AS estoque_id,
            e.quantidade AS estoque_quantidade
     FROM item_pedido ip
     INNER JOIN pedido p ON p.id = ip.pedido_id
     LEFT JOIN estoque e ON e.produto_id = ip.produto_id
     WHERE ip.id = :id AND ip.pedido_id = :pedido_id AND p.cliente_id = :cliente_id
     LIMIT 1"
);
$stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
$stmt->bindValue(':pedido_id', (int)$pedidoId, PDO::PARAM_INT);
$stmt->bindValue(':cliente_id', (int)$clienteId, PDO::PARAM_INT);
$stmt->execute();
$itemRow = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$itemRow) {
    header('Location: ' . BASE_URL . '/public/meus_pedidos.php');
    exit;
}

if (in_array($itemRow['situacao'], ['CANCELADO', 'ENTREGUE'], true)) {
    header('Location: ' . BASE_URL . '/public/pedido_detalhe.php?id=' . $pedidoId . '&erro=sem_permissao');
    exit;
}

$quantidadeAtual = (int)$itemRow['quantidade_atual'];
$quantidadeNova = max(1, (int)$quantidade);
$diff = $quantidadeNova - $quantidadeAtual;

if ($diff !== 0) {
    if (!$itemRow['estoque_id']) {
        header('Location: ' . BASE_URL . '/public/pedido_detalhe.php?id=' . $pedidoId . '&erro=estoque_insuficiente');
        exit;
    }

    $novaQuantidadeEstoque = (int)$itemRow['estoque_quantidade'] - $diff;
    if ($novaQuantidadeEstoque < 0) {
        header('Location: ' . BASE_URL . '/public/pedido_detalhe.php?id=' . $pedidoId . '&erro=estoque_insuficiente');
        exit;
    }

    $updateEstoque = $conn->prepare("UPDATE estoque SET quantidade = :quantidade WHERE id = :id");
    $updateEstoque->bindValue(':quantidade', $novaQuantidadeEstoque, PDO::PARAM_INT);
    $updateEstoque->bindValue(':id', (int)$itemRow['estoque_id'], PDO::PARAM_INT);
    $updateEstoque->execute();
}

$item = new ItemPedido($id, $quantidadeNova, $preco);
$item->setPedido(new Pedido($pedidoId, null, null, null, null));
$item->setProduto(new Produto($itemRow['produto_id'], '', '', null));

$dao = $factory->getItemPedidoDao();
$dao->altera($item);

header('Location: ' . BASE_URL . '/public/pedido_detalhe.php?id=' . $pedidoId);
exit;
?>
