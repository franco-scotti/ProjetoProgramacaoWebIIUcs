<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$id = @$_GET["id"];
$nome = trim((string)@$_GET["nome"]);
$telefone = trim((string)@$_GET["telefone"]);
$email = trim((string)@$_GET["email"]);
$cartao = trim((string)@$_GET["cartao_credito"]);

$cliente = new Cliente($id, $nome, $telefone, $email, $cartao);
$dao = $factory->getClienteDao();
$dao->altera($cliente);

header("Location: " . BASE_URL . "/views/listagem/lista_clientes.php");
exit;
?>
