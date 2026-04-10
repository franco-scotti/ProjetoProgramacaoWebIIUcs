<?php
include_once "fachada.php";

$id = @$_GET["id"];
$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);

$estoque = new Estoque($id, $quantidade, $preco);
if ($produtoId !== "") {
    $estoque->setProduto(new Produto($produtoId, '', '', null));
}

$dao = $factory->getEstoqueDao();
$dao->altera($estoque);

header("Location: estoques.php");
exit;
?>
