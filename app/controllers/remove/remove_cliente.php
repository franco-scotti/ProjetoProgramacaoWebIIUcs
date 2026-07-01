<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];

$clienteDao = $factory->getClienteDao();
$enderecoDao = $factory->getEnderecoDao();

$cliente = $clienteDao->buscaPorId($id);

$enderecoId = null;

if ($cliente && $cliente->getEndereco()) {
    $enderecoId = $cliente->getEndereco()->getId();
}

$removido = $clienteDao->removePorId($id);

if (!$removido) {
    header("Location: " . BASE_URL . "/views/listagem/lista_clientes.php?erro=dependencia");
    exit;
}

if ($enderecoId) {
    $enderecoDao->removePorId($enderecoId);
}

header("Location: " . BASE_URL . "/views/listagem/lista_clientes.php");
exit;
?>
