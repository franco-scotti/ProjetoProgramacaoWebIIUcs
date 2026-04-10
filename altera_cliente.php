<?php
include_once "fachada.php";

$id = @$_GET["id"];
$nome = trim((string)@$_GET["nome"]);
$telefone = trim((string)@$_GET["telefone"]);
$email = trim((string)@$_GET["email"]);
$cartao = trim((string)@$_GET["cartao_credito"]);

$cliente = new Cliente($id, $nome, $telefone, $email, $cartao);
$dao = $factory->getClienteDao();
$dao->altera($cliente);

header("Location: clientes.php");
exit;
?>
