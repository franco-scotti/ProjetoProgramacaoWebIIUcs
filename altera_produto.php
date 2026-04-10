<?php
include_once "fachada.php";

$id = @$_GET["id"];
$nome = trim((string)@$_GET["nome"]);
$descricao = trim((string)@$_GET["descricao"]);
$foto = trim((string)@$_GET["foto"]);
$fornecedorId = trim((string)@$_GET["fornecedor_id"]);

$produto = new Produto($id, $nome, $descricao, $foto);
if ($fornecedorId !== "") {
    $produto->setFornecedor(new Fornecedor($fornecedorId, '', '', '', ''));
}

$dao = $factory->getProdutoDao();
$dao->altera($produto);

header("Location: produtos.php");
exit;
?>
