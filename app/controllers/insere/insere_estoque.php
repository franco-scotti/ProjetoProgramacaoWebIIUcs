<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);

$estoque = new Estoque(null, $quantidade, $preco);
if ($produtoId !== "") {
    $estoque->setProduto(new Produto($produtoId, '', '', null));
}

$dao = $factory->getEstoqueDao();
$dao->insere($estoque);

header("Location: " . BASE_URL . "/views/listagem/lista_estoques.php");
exit;
?>
