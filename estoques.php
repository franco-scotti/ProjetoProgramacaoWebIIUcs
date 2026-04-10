<?php
$page_title = "Demo : Listagem de Estoque";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getEstoqueDao();
$estoques = $dao->buscaTodos();

if($estoques) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>ProdutoId</th><th>Quantidade</th><th>Preco</th><th>Acoes</th></tr>";

    foreach ($estoques as $estoque) {
        $produtoId = $estoque->getProduto() ? $estoque->getProduto()->getId() : '';
        echo "<tr>";
        echo "<td>{$estoque->getId()}</td>";
        echo "<td>{$produtoId}</td>";
        echo "<td>{$estoque->getQuantidade()}</td>";
        echo "<td>{$estoque->getPreco()}</td>";
        echo "<td>";
        echo "<a href='mostra_estoque.php?id={$estoque->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='modifica_estoque.php?id={$estoque->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='remove_estoque.php?id={$estoque->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='novo_estoque.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
