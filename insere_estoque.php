<?php
include_once "fachada.php";

$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);

$estoque = new Estoque(null, $quantidade, $preco);
if ($produtoId !== "") {
    $estoque->setProduto(new Produto($produtoId, '', '', null));
}

$dao = $factory->getEstoqueDao();
$dao->insere($estoque);

header("Location: estoques.php");
exit;
?>
