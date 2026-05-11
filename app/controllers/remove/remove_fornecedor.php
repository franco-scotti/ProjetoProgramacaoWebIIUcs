<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];

$fornecedorDao = $factory->getFornecedorDao();
$enderecoDao = $factory->getEnderecoDao();

$fornecedor = $fornecedorDao->buscaPorId($id);

$enderecoId = null;

if ($fornecedor && $fornecedor->getEndereco()) {
    $enderecoId = $fornecedor->getEndereco()->getId();
}

$fornecedorDao->removePorId($id);

if ($enderecoId) {
    $enderecoDao->removePorId($enderecoId);
}

header("Location: " . BASE_URL . "/views/listagem/lista_fornecedores.php");
exit;
?>
