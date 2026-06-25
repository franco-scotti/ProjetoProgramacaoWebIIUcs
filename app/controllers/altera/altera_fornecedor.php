<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$fornecedorDao = $factory->getFornecedorDao();
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

$fornecedor = new Fornecedor(
    $_GET['id'],
    $_GET['nome'],
    $_GET['descricao'],
    $_GET['telefone'],
    $_GET['email'],
    $usuarioId
);

$fornecedor->setEndereco(new Endereco($endereco_id, '', '', '', '', '', '', ''));

if ($fornecedorDao->altera($fornecedor)) {
    header("Location: " . BASE_URL . "/views/listagem/lista_fornecedores.php");
} else {
    header("Location: " . BASE_URL . "/views/cadastro/form_fornecedor.php?id=" . $_GET['id'] . "&erro=erro_alteracao");
}

exit;
?>
