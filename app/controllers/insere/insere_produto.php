<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

if (is_session_started() === FALSE) session_start();

$tipo         = $_SESSION['usuario_tipo'] ?? '';
$isAdmin      = ($tipo === 'admin');
$fornecedorId = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                ? (int)$_SESSION['usuario_fornecedor_id']
                : null;

if (!$isAdmin && $tipo !== 'fornecedor') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$nome      = trim((string)($_POST['nome']      ?? ''));
$descricao = trim((string)($_POST['descricao'] ?? ''));
$campos = ['nome','descricao'];
$dados = ['nome' => $nome, 'descricao' => $descricao];

if (!empty(camposObrigatorios($campos, $dados))) {
    header('Location: ' . BASE_URL . '/views/cadastro/form_produto.php?erro=campos_obrigatorios');
    exit;
}

if ($fornecedorId !== null) {
    $fId = $fornecedorId;
} else {
    $fId = trim((string)($_POST['fornecedor_id'] ?? ''));
    $fId = $fId !== '' ? (int)$fId : null;
}

$fotoData = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fotoData = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
}

$produto = new Produto(null, $nome, $descricao, $fotoData);
if ($fId) {
    $produto->setFornecedor(new Fornecedor($fId, '', '', '', ''));
}

$dao = $factory->getProdutoDao();
$dao->insere($produto);

header('Location: ' . BASE_URL . '/views/listagem/lista_produtos.php');
exit;
?>
