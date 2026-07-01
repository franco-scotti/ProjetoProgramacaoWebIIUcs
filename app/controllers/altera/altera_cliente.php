<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

if (is_session_started() === FALSE) {
    session_start();
}

$tipoUsuario = $_SESSION['usuario_tipo'] ?? null;
$clienteLogadoId = isset($_SESSION['usuario_cliente_id']) ? (int)$_SESSION['usuario_cliente_id'] : 0;
$clienteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($tipoUsuario === 'cliente') {
    if ($clienteId === 0) {
        $clienteId = $clienteLogadoId;
    }
    if ($clienteId !== $clienteLogadoId) {
        header('Location: ' . BASE_URL . '/public/portal_cliente.php');
        exit;
    }
} elseif ($tipoUsuario !== 'admin') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

$campos = ['rua','numero','complemento','bairro','cep','cidade','estado','nome','telefone','email','cartao_credito'];
$dados = [
    'rua' => trim((string)($_GET['rua'] ?? '')),
    'numero' => trim((string)($_GET['numero'] ?? '')),
    'complemento' => trim((string)($_GET['complemento'] ?? '')),
    'bairro' => trim((string)($_GET['bairro'] ?? '')),
    'cep' => trim((string)($_GET['cep'] ?? '')),
    'cidade' => trim((string)($_GET['cidade'] ?? '')),
    'estado' => trim((string)($_GET['estado'] ?? '')),
    'nome' => trim((string)($_GET['nome'] ?? '')),
    'telefone' => trim((string)($_GET['telefone'] ?? '')),
    'email' => trim((string)($_GET['email'] ?? '')),
    'cartao_credito' => trim((string)($_GET['cartao_credito'] ?? '')),
];

if (!empty(camposObrigatorios($campos, $dados))) {
    $redirect = $tipoUsuario === 'cliente'
        ? BASE_URL . '/public/portal_cliente.php'
        : BASE_URL . '/views/cadastro/form_cliente.php?id=' . $clienteId;
    header('Location: ' . $redirect . '&erro=campos_obrigatorios');
    exit;
}

$clienteDao = $factory->getClienteDao();
$enderecoDao = $factory->getEnderecoDao();

$endereco_id = isset($_GET['endereco_id']) ? $_GET['endereco_id'] : null;

if ($endereco_id) {
    $endereco = new Endereco(
        $endereco_id,
        $_GET['rua'],
        $_GET['numero'],
        $_GET['complemento'],
        $_GET['bairro'],
        $_GET['cep'],
        $_GET['cidade'],
        $_GET['estado']
    );

    $enderecoDao->altera($endereco);
} else {
    $endereco = new Endereco(
        null,
        $_GET['rua'],
        $_GET['numero'],
        $_GET['complemento'],
        $_GET['bairro'],
        $_GET['cep'],
        $_GET['cidade'],
        $_GET['estado']
    );

    $enderecoDao->insere($endereco);
    $endereco_id = $enderecoDao->ultimoId();
}

$usuarioId = isset($_GET['usuario_id']) ? trim((string)$_GET['usuario_id']) : null;
$usuarioId = $usuarioId !== '' ? $usuarioId : null;

$cliente = new Cliente(
    $clienteId,
    $_GET['nome'],
    $_GET['telefone'],
    $_GET['email'],
    $_GET['cartao_credito'],
    $usuarioId
);

$cliente->setEndereco(new Endereco($endereco_id, '', '', '', '', '', '', ''));

if ($clienteDao->altera($cliente)) {
    $_SESSION['flash_message'] = 'Seus dados foram atualizados com sucesso!';
    if ($tipoUsuario === 'cliente') {
        header("Location: " . BASE_URL . "/public/portal_cliente.php");
    } else {
        header("Location: " . BASE_URL . "/views/listagem/lista_clientes.php");
    }
} else {
    header("Location: " . BASE_URL . "/views/cadastro/form_cliente.php?id=" . $clienteId . "&erro=erro_alteracao");
}

exit;
?>
