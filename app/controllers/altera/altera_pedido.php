<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
if (is_session_started() === FALSE) session_start();

$tipo = $_SESSION['usuario_tipo'] ?? '';
$fornecedorId = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                ? (int)$_SESSION['usuario_fornecedor_id'] : null;
if ($tipo !== 'admin' && $tipo !== 'fornecedor') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$id         = (int)($_POST['id'] ?? 0);
$situacao   = trim((string)($_POST['situacao']   ?? 'NOVO'));
$clienteId  = trim((string)($_POST['cliente_id'] ?? ''));

$pedidoOriginal = $factory->getPedidoDao()->buscaPorId($id);
if (!$pedidoOriginal) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

if ($tipo === 'fornecedor' && $fornecedorId !== null) {
    $stmt = $factory->getConnection()->prepare(
        "SELECT 1 FROM item_pedido ip
         INNER JOIN produto pr ON pr.id = ip.produto_id
         WHERE ip.pedido_id = :pedido_id AND pr.fornecedor_id = :fornecedor_id
         LIMIT 1"
    );
    $stmt->bindValue(':pedido_id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':fornecedor_id', $fornecedorId, PDO::PARAM_INT);
    $stmt->execute();
    if (!$stmt->fetchColumn()) {
        header('Location: ' . BASE_URL . '/public/index.php');
        exit;
    }
    $numero     = $pedidoOriginal->getNumero();
    $dataPedido = $pedidoOriginal->getDataPedido();
    $clienteId  = $pedidoOriginal->getCliente() ? $pedidoOriginal->getCliente()->getId() : '';
} else {
    $numero     = trim((string)($_POST['numero']     ?? ''));
    $dataPedido = trim((string)($_POST['data_pedido']?? ''));
}

// Situações válidas
$situacoesValidas = ['NOVO', 'PREPARANDO PARA ENVIO', 'A CAMINHO', 'ENTREGUE', 'CANCELADO'];
if (!in_array($situacao, $situacoesValidas)) $situacao = 'NOVO';

// Data de entrega: vem do campo data_entrega (entregue) ou data_cancelamento (cancelado)
$dataEntrega = '';
if ($situacao === 'ENTREGUE') {
    $dataEntrega = trim((string)($_POST['data_entrega'] ?? ''));
} elseif ($situacao === 'CANCELADO') {
    $dataEntrega = trim((string)($_POST['data_cancelamento'] ?? ''));
} else {
    $dataEntrega = trim((string)($_POST['data_entrega'] ?? ''));
}

$pedido = new Pedido($id, $numero, $dataPedido, $dataEntrega ?: null, $situacao);
if ($clienteId !== '') {
    $pedido->setCliente(new Cliente($clienteId, '', '', '', ''));
}

$factory->getPedidoDao()->altera($pedido);

header('Location: ' . BASE_URL . '/views/listagem/lista_pedidos.php');
exit;
?>
