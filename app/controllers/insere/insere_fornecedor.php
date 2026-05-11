<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$fornecedorDao = $factory->getFornecedorDao();
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

    $fornecedor = new Fornecedor(
        null,
        $_GET['nome'],
        $_GET['descricao'],
        $_GET['telefone'],
        $_GET['email']
    );

    $fornecedor->setEndereco(new Endereco($endereco_id, '', '', '', '', '', '', ''));

    if ($fornecedorDao->insere($fornecedor)) {
        header("Location: " . BASE_URL . "/views/listagem/lista_fornecedores.php");
        exit;
    }
}

header("Location: " . BASE_URL . "/views/cadastro/form_fornecedor.php?erro=erro_insercao");
exit;
?>
