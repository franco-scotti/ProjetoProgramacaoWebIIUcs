<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEstoqueDao();
$estoque = $dao->buscaPorId($id);

if($estoque) {
    $page_title = "Demo : Exibindo Estoque";
} else {
    $page_title = "Demo : Estoque nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($estoque) {
    $produtoId = $estoque->getProduto() ? $estoque->getProduto()->getId() : '';
    echo "<section>";
    echo "<h1> Estoque ID : " . $estoque->getId() . "</h1>";
    echo "<p>Produto ID : " . $produtoId . "</p>";
    echo "<p>Quantidade : " . $estoque->getQuantidade() . "</p>";
    echo "<p>Preco : " . $estoque->getPreco() . "</p>";
    echo "<a href='" . BASE_URL . "/views/listagem/lista_estoques.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
