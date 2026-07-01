<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

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
    header("Location: " . BASE_URL . "/views/cadastro/form_cliente.php?erro=campos_obrigatorios");
    exit;
}

$clienteDao = $factory->getClienteDao();
$enderecoDao = $factory->getEnderecoDao();

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

if ($enderecoDao->insere($endereco)) {

    $endereco_id = $enderecoDao->ultimoId();

    $usuarioId = isset($_GET['usuario_id']) ? trim((string)$_GET['usuario_id']) : null;
    $usuarioId = $usuarioId !== '' ? $usuarioId : null;

    $cliente = new Cliente(
        null,
        $_GET['nome'],
        $_GET['telefone'],
        $_GET['email'],
        $_GET['cartao_credito'],
        $usuarioId
    );

    $cliente->setEndereco(new Endereco($endereco_id, '', '', '', '', '', '', ''));

    if ($clienteDao->insere($cliente)) {
        header("Location: " . BASE_URL . "/views/listagem/lista_clientes.php");
        exit;
    }
}

header("Location: " . BASE_URL . "/views/cadastro/form_cliente.php?erro=erro_insercao");
exit;
?>
