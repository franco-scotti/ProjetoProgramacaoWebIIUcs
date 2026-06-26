<?php
$page_title = "Demo : Listagem de Produtos";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo         = $_SESSION['usuario_tipo'] ?? '';
$fornecedorId = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                ? (int)$_SESSION['usuario_fornecedor_id']
                : null;
$filtraFornecedor = ($fornecedorId !== null);

// Cliente não acessa gestão de produtos
if ($tipo === 'cliente') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$dao          = $factory->getProdutoDao();
$isFornecedor = ($tipo === 'fornecedor');

function escreveLinhasProdutos($produtos, $isFornecedor) {
    if ($produtos) {
        foreach ($produtos as $produto) {
            $id            = htmlspecialchars($produto->getId());
            $nome          = htmlspecialchars($produto->getNome());
            $descricao     = htmlspecialchars($produto->getDescricao());
            $fornecedorNome= $produto->getFornecedor() ? htmlspecialchars($produto->getFornecedor()->getNome()) : '—';

            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$nome}</td>";
            echo "<td>{$descricao}</td>";
            echo "<td>{$fornecedorNome}</td>";
            echo "<td>";
            echo "<a href='" . BASE_URL . "/views/detalhes/mostra_produto.php?id={$id}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
            echo "<a href='" . BASE_URL . "/views/cadastro/form_produto.php?id={$id}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
            if (!$isFornecedor) {
                echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_produto.php?id={$id}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum produto encontrado.</td></tr>";
    }
}

// Requisição AJAX — respeita filtro de fornecedor
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $termo = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
    if ($filtraFornecedor) {
        $produtos = $termo !== ''
            ? $dao->buscaPorCodigoNomeFornecedor($termo, $fornecedorId)
            : $dao->buscaPorFornecedorId($fornecedorId, 10, 0);
    } else {
        $produtos = $termo !== ''
            ? $dao->buscaPorCodigoNome($termo)
            : $dao->buscaTodos(10, 0);
    }
    escreveLinhasProdutos($produtos, $isFornecedor);
    exit;
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

echo "<section>";
echo "<h2>" . ($filtraFornecedor ? "Meus Produtos" : "Todos os Produtos") . "</h2>";

echo "<div class='form-group'>";
echo "<input type='text' id='pesquisaProduto' class='form-control' placeholder='Digite o código ou nome do produto'>";
echo "</div>";

$itensPorPagina = 10;
$paginaAtual    = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) $paginaAtual = 1;

if ($filtraFornecedor) {
    $totalProdutos = $dao->contaPorFornecedor($fornecedorId);
} else {
    $totalProdutos = $dao->contaTodos();
}

$totalPaginas = max(1, (int)ceil($totalProdutos / $itensPorPagina));
if ($paginaAtual > $totalPaginas) $paginaAtual = $totalPaginas;
$offset = ($paginaAtual - 1) * $itensPorPagina;

$produtos = $filtraFornecedor
    ? $dao->buscaPorFornecedorId($fornecedorId, $itensPorPagina, $offset)
    : $dao->buscaTodos($itensPorPagina, $offset);

echo "<table class='table table-hover table-responsive table-bordered'>";
echo "<thead><tr><th>Id</th><th>Nome</th><th>Descrição</th><th>Fornecedor</th><th>Ações</th></tr></thead>";
echo "<tbody id='resultadoProdutos'>";
escreveLinhasProdutos($produtos, $isFornecedor);
echo "</tbody>";
echo "</table>";

echo "<div id='paginacaoProdutos'>";
echo "<p>Página {$paginaAtual} de {$totalPaginas}</p>";
if ($totalPaginas > 1) {
    echo "<nav>";
    if ($paginaAtual > 1) {
        $ant = $paginaAtual - 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_produtos.php?pagina={$ant}' class='btn btn-default left-margin'>Anterior</a>";
    }
    if ($paginaAtual < $totalPaginas) {
        $prox = $paginaAtual + 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_produtos.php?pagina={$prox}' class='btn btn-default left-margin'>Próxima</a>";
    }
    echo "</nav>";
}
echo "</div>";

// Fornecedor pode cadastrar novo produto (já virá vinculado ao seu ID)
echo "<a href='" . BASE_URL . "/views/cadastro/form_produto.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";
?>

<script>
document.getElementById('pesquisaProduto').addEventListener('keyup', function() {
    var termo = this.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('resultadoProdutos').innerHTML = this.responseText;
            document.getElementById('paginacaoProdutos').style.display = termo.trim() !== '' ? 'none' : 'block';
        }
    };
    xhttp.open('GET', 'lista_produtos.php?ajax=1&pesquisa=' + encodeURIComponent(termo), true);
    xhttp.send();
});
</script>

<?php
include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
