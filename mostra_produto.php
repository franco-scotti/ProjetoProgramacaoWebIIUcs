<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getProdutoDao();
$produto = $dao->buscaPorId($id);

if($produto) {
    $page_title = "Demo : Exibindo Produto : " . $produto->getNome();
} else {
    $page_title = "Demo : Produto nao encontrado!";
}

include_once "layout_header.php";

if($produto) {
    $fornecedorId = $produto->getFornecedor() ? $produto->getFornecedor()->getId() : '';
    echo "<section>";
    echo "<h1> Nome : " . $produto->getNome() . "</h1>";
    echo "<p>Id : " . $produto->getId() . "</p>";
    echo "<p>Descricao : " . $produto->getDescricao() . "</p>";
    echo "<p>Fornecedor ID : " . $fornecedorId . "</p>";
    echo "<a href='produtos.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
