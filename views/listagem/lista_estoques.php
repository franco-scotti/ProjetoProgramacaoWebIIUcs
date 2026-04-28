<?php
$page_title = "Demo : Listagem de Estoque";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

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
        echo "<a href='" . BASE_URL . "/views/detalhes/mostra_estoque.php?id={$estoque->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='" . BASE_URL . "/views/altera/modifica_estoque.php?id={$estoque->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_estoque.php?id={$estoque->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='" . BASE_URL . "/views/cadastro/novo_estoque.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
