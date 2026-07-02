<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
if (is_session_started() === FALSE) session_start();

header('Content-Type: application/json; charset=utf-8');

$pedidoId    = (int)($_GET['pedido_id'] ?? 0);
$itensPorPag = max(1, (int)($_GET['limit']  ?? 5));
$paginaAtual = max(1, (int)($_GET['pagina'] ?? 1));
$offset      = ($paginaAtual - 1) * $itensPorPag;

if (!$pedidoId) { echo json_encode(['erro' => 'pedido_id obrigatorio']); exit; }

$pdo = $factory->getConnection();

$tipo = $_SESSION['usuario_tipo'] ?? '';
$fornecedorId = ($tipo === 'fornecedor' && isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null)
                ? (int)$_SESSION['usuario_fornecedor_id']
                : null;

$filtroForn      = $fornecedorId !== null ? " AND pr.fornecedor_id = :fid" : "";
$filtroFornFotos = $fornecedorId !== null ? " AND pr.fornecedor_id = :fid" : "";

$stmtCount = $pdo->prepare(
    "SELECT COUNT(*)
     FROM item_pedido ip
     LEFT JOIN produto pr ON pr.id = ip.produto_id
     WHERE ip.pedido_id = :pid" . $filtroForn
);
$stmtCount->bindValue(':pid', $pedidoId, PDO::PARAM_INT);
if ($fornecedorId !== null) {
    $stmtCount->bindValue(':fid', $fornecedorId, PDO::PARAM_INT);
}
$stmtCount->execute();
$total = (int)$stmtCount->fetchColumn();

$stmt = $pdo->prepare(
    "SELECT ip.id, ip.quantidade, ip.preco,
            pr.id AS produto_id, pr.nome AS produto_nome,
            pr.descricao AS produto_descricao, pr.foto AS produto_foto
     FROM item_pedido ip
     LEFT JOIN produto pr ON pr.id = ip.produto_id
     WHERE ip.pedido_id = :pid" . $filtroForn . "
     ORDER BY ip.id ASC
     LIMIT :lim OFFSET :off"
);
$stmt->bindValue(':pid', $pedidoId, PDO::PARAM_INT);
if ($fornecedorId !== null) {
    $stmt->bindValue(':fid', $fornecedorId, PDO::PARAM_INT);
}
$stmt->bindValue(':lim', $itensPorPag, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();

$itens = [];
$total_valor = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $subtotal     = (float)$row['preco'] * (int)$row['quantidade'];
    $total_valor += $subtotal;
    $foto = null;
    if ($row['produto_foto']) {
        $raw  = is_resource($row['produto_foto']) ? stream_get_contents($row['produto_foto']) : $row['produto_foto'];
        $foto = strpos($raw, 'data:image') === 0 ? $raw : 'data:image/jpeg;base64,' . base64_encode($raw);
    }
    $itens[] = [
        'produto_nome'      => $row['produto_nome'],
        'produto_descricao' => $row['produto_descricao'],
        'foto'              => $foto,
        'quantidade'        => (int)$row['quantidade'],
        'preco_fmt'         => 'R$ ' . number_format((float)$row['preco'], 2, ',', '.'),
        'subtotal_fmt'      => 'R$ ' . number_format($subtotal, 2, ',', '.'),
    ];
}

$fotos = [];
if ($paginaAtual === 1) {
    $stmtF = $pdo->prepare(
        "SELECT pr.foto, pr.nome FROM item_pedido ip
         LEFT JOIN produto pr ON pr.id = ip.produto_id
         WHERE ip.pedido_id = :pid AND pr.foto IS NOT NULL" . $filtroFornFotos
    );
    $stmtF->bindValue(':pid', $pedidoId, PDO::PARAM_INT);
    if ($fornecedorId !== null) {
        $stmtF->bindValue(':fid', $fornecedorId, PDO::PARAM_INT);
    }
    $stmtF->execute();
    while ($rowF = $stmtF->fetch(PDO::FETCH_ASSOC)) {
        $raw = is_resource($rowF['foto']) ? stream_get_contents($rowF['foto']) : $rowF['foto'];
        if ($raw) {
            $fotos[] = [
                'src'  => strpos($raw, 'data:image') === 0 ? $raw : 'data:image/jpeg;base64,' . base64_encode($raw),
                'nome' => $rowF['nome'],
            ];
        }
    }
}

$totalLabel = $fornecedorId !== null ? 'Total dos meus produtos' : 'Total';

echo json_encode([
    'itens'         => $itens,
    'total_fmt'     => 'R$ ' . number_format($total_valor, 2, ',', '.'),
    'total_label'   => $totalLabel,
    'total_itens'   => $total,
    'pagina_atual'  => $paginaAtual,
    'total_paginas' => max(1, (int)ceil($total / $itensPorPag)),
    'fotos'         => $fotos,
]);
