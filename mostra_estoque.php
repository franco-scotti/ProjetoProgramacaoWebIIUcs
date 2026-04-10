<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEstoqueDao();
$estoque = $dao->buscaPorId($id);

if($estoque) {
    $page_title = "Demo : Exibindo Estoque";
} else {
    $page_title = "Demo : Estoque nao encontrado!";
}

include_once "layout_header.php";

if($estoque) {
    $produtoId = $estoque->getProduto() ? $estoque->getProduto()->getId() : '';
    echo "<section>";
    echo "<h1> Estoque ID : " . $estoque->getId() . "</h1>";
    echo "<p>Produto ID : " . $produtoId . "</p>";
    echo "<p>Quantidade : " . $estoque->getQuantidade() . "</p>";
    echo "<p>Preco : " . $estoque->getPreco() . "</p>";
    echo "<a href='estoques.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
