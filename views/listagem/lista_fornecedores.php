<?php
$page_title = "Demo : Listagem de Fornecedores";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

echo "<section>";

$dao = $factory->getFornecedorDao();
$itensPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) {
    $paginaAtual = 1;
}

$totalFornecedores = $dao->contaTodos();
$totalPaginas = max(1, (int)ceil($totalFornecedores / $itensPorPagina));
if ($paginaAtual > $totalPaginas) {
    $paginaAtual = $totalPaginas;
}

$offset = ($paginaAtual - 1) * $itensPorPagina;
$fornecedores = $dao->buscaTodos($itensPorPagina, $offset);

if($fornecedores) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
        echo "<th>Id</th>";
        echo "<th>Nome</th>";
        echo "<th>Descricao</th>";
        echo "<th>Telefone</th>";
        echo "<th>Email</th>";
        echo "<th>Acoes</th>";
    echo "</tr>";

    foreach ($fornecedores as $fornecedor) {
        echo "<tr>";
            echo "<td>{$fornecedor->getId()}</td>";
            echo "<td>{$fornecedor->getNome()}</td>";
            echo "<td>{$fornecedor->getDescricao()}</td>";
            echo "<td>{$fornecedor->getTelefone()}</td>";
            echo "<td>{$fornecedor->getEmail()}</td>";
            echo "<td>";
                echo "<a href='" . BASE_URL . "/views/detalhes/mostra_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-primary left-margin'>";
                    echo "<span class='glyphicon glyphicon-list'></span> Mostra";
                echo "</a>";
                echo "<a href='" . BASE_URL . "/views/altera/modifica_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-info left-margin'>";
                    echo "<span class='glyphicon glyphicon-edit'></span> Altera";
                echo "</a>";
                echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-danger left-margin' ";
                    echo "onclick=\"return confirm('Tem certeza que quer excluir?')\">";
                    echo "<span class='glyphicon glyphicon-remove'></span> Exclui";
                echo "</a>";
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
        echo "<a href='lista_fornecedores.php?pagina={$paginaAnterior}' class='btn btn-default left-margin'>Anterior</a>";
    }
    if ($paginaAtual < $totalPaginas) {
        $proximaPagina = $paginaAtual + 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_fornecedores.php?pagina={$proximaPagina}' class='btn btn-default left-margin'>Proxima</a>";
    }
    echo "</nav>";
}

echo "<a href='" . BASE_URL . "/views/cadastro/novo_fornecedor.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
