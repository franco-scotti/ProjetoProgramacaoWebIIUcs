<?php
include_once "fachada.php";

$nome = trim((string)@$_GET["nome"]);
$descricao = trim((string)@$_GET["descricao"]);
$telefone = trim((string)@$_GET["telefone"]);
$email = trim((string)@$_GET["email"]);

$fornecedor = new Fornecedor(null, $nome, $descricao, $telefone, $email);
$dao = $factory->getFornecedorDao();
$dao->insere($fornecedor);

header("Location: fornecedores.php");
exit;
?>
