<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/routes/fachada.php";

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
    $_GET['id'],
    $_GET['nome'],
    $_GET['telefone'],
    $_GET['email'],
    $_GET['cartao_credito'],
    $usuarioId
);

$cliente->setEndereco(new Endereco($endereco_id, '', '', '', '', '', '', ''));

if ($clienteDao->altera($cliente)) {
    header("Location: " . BASE_URL . "/views/listagem/lista_clientes.php");
} else {
    header("Location: " . BASE_URL . "/views/cadastro/form_cliente.php?id=" . $_GET['id'] . "&erro=erro_alteracao");
}

exit;
?>
