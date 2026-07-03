<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido. Use GET.']);
    exit;
}

include_once dirname(__DIR__) . "/routes/fachada.php";

$pdo = $factory->getConnection();

function rowToPedido(array $row): array {
    return [
        'id'           => (int)$row['id'],
        'numero'       => (int)$row['numero'],
        'data_pedido'  => $row['data_pedido'],
        'data_entrega' => $row['data_entrega'],
        'situacao'     => $row['situacao'],
        'cliente'      => $row['cliente_id'] ? [
            'id'    => (int)$row['cliente_id'],
            'nome'  => $row['cliente_nome'],
            'email' => $row['cliente_email'],
        ] : null,
    ];
}

function itensDoPedido(PDO $pdo, int $pedidoId): array {
    $stmt = $pdo->prepare(
        "SELECT ip.quantidade, ip.preco,
                pr.id AS produto_id, pr.nome AS produto_nome, pr.descricao
         FROM item_pedido ip
         LEFT JOIN produto pr ON pr.id = ip.produto_id
         WHERE ip.pedido_id = :pid
         ORDER BY ip.id ASC"
    );
    $stmt->bindValue(':pid', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();
    $itens = [];
    $total = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $subtotal = (float)$row['preco'] * (int)$row['quantidade'];
        $total   += $subtotal;
        $itens[]  = [
            'produto_id'   => (int)$row['produto_id'],
            'produto_nome' => $row['produto_nome'],
            'descricao'    => $row['descricao'],
            'quantidade'   => (int)$row['quantidade'],
            'preco'        => (float)$row['preco'],
            'subtotal'     => $subtotal,
        ];
    }
    return ['itens' => $itens, 'total' => $total];
}

$baseSelect = "SELECT
    p.id, p.numero, p.data_pedido, p.data_entrega, p.situacao, p.cliente_id,
    c.nome AS cliente_nome, c.email AS cliente_email
  FROM pedido p
  LEFT JOIN cliente c ON c.id = p.cliente_id";


if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare($baseSelect . " WHERE p.id = :id LIMIT 1");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        http_response_code(404);
        echo json_encode(['erro' => 'Pedido não encontrado']);
        exit;
    }
    $pedido          = rowToPedido($row);
    $detalhes        = itensDoPedido($pdo, $id);
    $pedido['itens'] = $detalhes['itens'];
    $pedido['total'] = $detalhes['total'];
    echo json_encode(['pedido' => $pedido]);
    exit;
}

if (isset($_GET['numero'])) {
    $stmt = $pdo->prepare($baseSelect . " WHERE CAST(p.numero AS TEXT) ILIKE :n ORDER BY p.id DESC");
    $stmt->bindValue(':n', '%' . $_GET['numero'] . '%');
    $stmt->execute();
    $pedidos = array_map('rowToPedido', $stmt->fetchAll(PDO::FETCH_ASSOC));
    echo json_encode(['pedidos' => $pedidos, 'total' => count($pedidos)]);
    exit;
}

if (isset($_GET['cliente'])) {
    $stmt = $pdo->prepare($baseSelect . " WHERE c.nome ILIKE :n ORDER BY p.id DESC");
    $stmt->bindValue(':n', '%' . $_GET['cliente'] . '%');
    $stmt->execute();
    $pedidos = array_map('rowToPedido', $stmt->fetchAll(PDO::FETCH_ASSOC));
    echo json_encode(['pedidos' => $pedidos, 'total' => count($pedidos)]);
    exit;
}

$limit  = max(1, min(100, (int)($_GET['limit']  ?? 20)));
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$offset = ($pagina - 1) * $limit;

$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM pedido");
$stmtCount->execute();
$totalPedidos = (int)$stmtCount->fetchColumn();

$stmt = $pdo->prepare($baseSelect . " ORDER BY p.id DESC LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', $limit,  PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$pedidos = array_map('rowToPedido', $stmt->fetchAll(PDO::FETCH_ASSOC));

echo json_encode([
    'pedidos'       => $pedidos,
    'total'         => $totalPedidos,
    'pagina'        => $pagina,
    'total_paginas' => max(1, (int)ceil($totalPedidos / $limit)),
    'limit'         => $limit,
]);
