<?php
include_once "fachada.php";

$id = @$_GET["id"];
$nome = trim((string)@$_GET["nome"]);
$descricao = trim((string)@$_GET["descricao"]);
$telefone = trim((string)@$_GET["telefone"]);
$email = trim((string)@$_GET["email"]);

$fornecedor = new Fornecedor($id, $nome, $descricao, $telefone, $email);
$dao = $factory->getFornecedorDao();
$dao->altera($fornecedor);

header("Location: fornecedores.php");
exit;
?>
