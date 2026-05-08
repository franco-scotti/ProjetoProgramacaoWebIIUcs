<?php
$page_title = "Demo : Listagem de Produtos";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

function escreveLinhasProdutos($produtos) {
    if($produtos) {
        foreach ($produtos as $produto) {
            $id = htmlspecialchars($produto->getId());
            $nome = htmlspecialchars($produto->getNome());
            $descricao = htmlspecialchars($produto->getDescricao());
            $fornecedorNome = $produto->getFornecedor() ? htmlspecialchars($produto->getFornecedor()->getNome()) : '';

            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$nome}</td>";
            echo "<td>{$descricao}</td>";
            echo "<td>{$fornecedorNome}</td>";
            echo "<td>";
            echo "<a href='" . BASE_URL . "/views/detalhes/mostra_produto.php?id={$id}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
            echo "<a href='" . BASE_URL . "/views/altera/modifica_produto.php?id={$id}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
            echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_produto.php?id={$id}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum produto encontrado.</td></tr>";
    }
}

$dao = $factory->getProdutoDao();

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $termo = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

    if ($termo != '') {
        $produtos = $dao->buscaPorCodigoNome($termo);
    } else {
        $produtos = $dao->buscaTodos(10, 0);
    }

    escreveLinhasProdutos($produtos);
    exit;
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

echo "<section>";

echo "<div class='form-group'>";
echo "<input type='text' id='pesquisaProduto' class='form-control' placeholder='Digite o codigo ou nome do produto'>";
echo "</div>";

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

echo "<table class='table table-hover table-responsive table-bordered'>";
echo "<thead>";
echo "<tr><th>Id</th><th>Nome</th><th>Descricao</th><th>Fornecedor</th><th>Acoes</th></tr>";
echo "</thead>";
echo "<tbody id='resultadoProdutos'>";
escreveLinhasProdutos($produtos);
echo "</tbody>";
echo "</table>";

echo "<div id='paginacaoProdutos'>";
echo "<p>Pagina {$paginaAtual} de {$totalPaginas}</p>";

if ($totalPaginas > 1) {
    echo "<nav>";
    if ($paginaAtual > 1) {
        $paginaAnterior = $paginaAtual - 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_produtos.php?pagina={$paginaAnterior}' class='btn btn-default left-margin'>Anterior</a>";
    }
    if ($paginaAtual < $totalPaginas) {
        $proximaPagina = $paginaAtual + 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_produtos.php?pagina={$proximaPagina}' class='btn btn-default left-margin'>Proxima</a>";
    }
    echo "</nav>";
}
echo "</div>";

echo "<a href='" . BASE_URL . "/views/cadastro/novo_produto.php' class='btn btn-primary left-margin'>Novo</a>";
?>

<script>
document.getElementById('pesquisaProduto').addEventListener('keyup', function() {
    var termo = this.value;
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('resultadoProdutos').innerHTML = this.responseText;

            if (termo.trim() != '') {
                document.getElementById('paginacaoProdutos').style.display = 'none';
            } else {
                document.getElementById('paginacaoProdutos').style.display = 'block';
            }
        }
    };

    xhttp.open('GET', 'lista_produtos.php?ajax=1&pesquisa=' + encodeURIComponent(termo), true);
    xhttp.send();
});
</script>

<?php
echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
