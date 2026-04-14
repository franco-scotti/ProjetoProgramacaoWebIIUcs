<?php
$page_title = "Demo : Listagem de Produtos";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getProdutoDao();
$itensPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) {
    $paginaAtual = 1;
}

$totalProdutos = $dao->contaTodos();
$totalPaginas = max(1, (int)ceil($totalProdutos / $itensPorPagina));
if ($paginaAtual > $totalPaginas) {
    $paginaAtual = $totalPaginas;
}

$offset = ($paginaAtual - 1) * $itensPorPagina;
$produtos = $dao->buscaTodos($itensPorPagina, $offset);

if($produtos) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Nome</th><th>Descricao</th><th>FornecedorId</th><th>Acoes</th></tr>";

    foreach ($produtos as $produto) {
        $fornecedorId = $produto->getFornecedor() ? $produto->getFornecedor()->getId() : '';
        echo "<tr>";
        echo "<td>{$produto->getId()}</td>";
        echo "<td>{$produto->getNome()}</td>";
        echo "<td>{$produto->getDescricao()}</td>";
        echo "<td>{$fornecedorId}</td>";
        echo "<td>";
        echo "<a href='mostra_produto.php?id={$produto->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='modifica_produto.php?id={$produto->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='remove_produto.php?id={$produto->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<p>Pagina {$paginaAtual} de {$totalPaginas}</p>";

if ($totalPaginas > 1) {
    echo "<nav>";
    if ($paginaAtual > 1) {
        $paginaAnterior = $paginaAtual - 1;
        echo "<a href='produtos.php?pagina={$paginaAnterior}' class='btn btn-default left-margin'>Anterior</a>";
    }
    if ($paginaAtual < $totalPaginas) {
        $proximaPagina = $paginaAtual + 1;
        echo "<a href='produtos.php?pagina={$proximaPagina}' class='btn btn-default left-margin'>Proxima</a>";
    }
    echo "</nav>";
}

echo "<a href='novo_produto.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
