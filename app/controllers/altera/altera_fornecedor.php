<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$fornecedorDao = $factory->getFornecedorDao();
$enderecoDao = $factory->getEnderecoDao();

$endereco_id = isset($_GET['endereco_id']) ? $_GET['endereco_id'] : null;

$numero = isset($_GET['numero']) ? trim((string)$_GET['numero']) : (isset($_GET['numero_end']) ? trim((string)$_GET['numero_end']) : '');

if ($endereco_id) {
    $endereco = new Endereco(
        $endereco_id,
        isset($_GET['rua']) ? trim((string)$_GET['rua']) : '',
        $numero,
        isset($_GET['complemento']) ? trim((string)$_GET['complemento']) : '',
        isset($_GET['bairro']) ? trim((string)$_GET['bairro']) : '',
        isset($_GET['cep']) ? trim((string)$_GET['cep']) : '',
        isset($_GET['cidade']) ? trim((string)$_GET['cidade']) : '',
        isset($_GET['estado']) ? trim((string)$_GET['estado']) : ''
    );

    $enderecoDao->altera($endereco);
} else {
    $endereco = new Endereco(
        null,
        isset($_GET['rua']) ? trim((string)$_GET['rua']) : '',
        $numero,
        isset($_GET['complemento']) ? trim((string)$_GET['complemento']) : '',
        isset($_GET['bairro']) ? trim((string)$_GET['bairro']) : '',
        isset($_GET['cep']) ? trim((string)$_GET['cep']) : '',
        isset($_GET['cidade']) ? trim((string)$_GET['cidade']) : '',
        isset($_GET['estado']) ? trim((string)$_GET['estado']) : ''
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
