<?php
$page_title = "Demo : Listagem de Estoques";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$tipo = $_SESSION['usuario_tipo'] ?? '';
if ($tipo === 'cliente') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$fornecedorId     = isset($_SESSION['usuario_fornecedor_id']) && $_SESSION['usuario_fornecedor_id'] !== null
                    ? (int)$_SESSION['usuario_fornecedor_id']
                    : null;
$filtraFornecedor = ($fornecedorId !== null);
$isFornecedor     = ($tipo === 'fornecedor');

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
$dao = $factory->getEstoqueDao();

function escreveLinhasEstoques($estoques, $isFornecedor) {
    if ($estoques) {
        foreach ($estoques as $estoque) {
            $nomeProd = $estoque->getProduto() ? htmlspecialchars($estoque->getProduto()->getNome()) : '—';
            echo "<tr>";
            echo "<td>{$estoque->getId()}</td>";
            echo "<td>{$nomeProd}</td>";
            echo "<td>{$estoque->getQuantidade()}</td>";
            echo "<td>{$estoque->getPreco()}</td>";
            echo "<td>";
            echo "<a href='" . BASE_URL . "/views/detalhes/mostra_estoque.php?id={$estoque->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
            echo "<a href='" . BASE_URL . "/views/altera/modifica_estoque.php?id={$estoque->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
            if (!$isFornecedor) {
                echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_estoque.php?id={$estoque->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Nenhum estoque encontrado</td></tr>";
    }
}

// AJAX — filtro textual respeitando fornecedor
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $termo = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
    if ($filtraFornecedor) {
        // Busca estoques do fornecedor e filtra pelo termo em PHP
        $estoques = $dao->buscaPorFornecedorId($fornecedorId);
        if ($termo !== '') {
            $termoLower = strtolower($termo);
            $estoques = array_values(array_filter($estoques, function($e) use ($termoLower) {
                $nomeProd = $e->getProduto() ? strtolower($e->getProduto()->getNome()) : '';
                return strpos((string)$e->getId(), $termoLower) !== false
                    || strpos($nomeProd, $termoLower) !== false;
            }));
        }
    } else {
        $estoques = $termo !== ''
            ? $dao->buscaPorCodigoNome($termo)
            : $dao->buscaTodos(10, 0);
    }
    escreveLinhasEstoques($estoques, $isFornecedor);
    exit;
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

echo "<section>";
echo "<h2>" . ($filtraFornecedor ? "Estoque dos meus produtos" : "Todos os Estoques") . "</h2>";

echo "<input type='text' id='pesquisaEstoque' class='form-control' placeholder='Buscar por código ou nome do produto'>";
echo "<br>";

$itensPorPagina = 10;
$paginaAtual    = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) $paginaAtual = 1;

if ($filtraFornecedor) {
    $estoques     = $dao->buscaPorFornecedorId($fornecedorId);
    $totalPaginas = 1; // lista curta sem paginação
} else {
    $totalEstoques = $dao->contaTodos();
    $totalPaginas  = max(1, (int)ceil($totalEstoques / $itensPorPagina));
    if ($paginaAtual > $totalPaginas) $paginaAtual = $totalPaginas;
    $offset        = ($paginaAtual - 1) * $itensPorPagina;
    $estoques      = $dao->buscaTodos($itensPorPagina, $offset);
}

echo "<table class='table table-hover table-responsive table-bordered'>";
echo "<tr><th>Id</th><th>Produto</th><th>Quantidade</th><th>Preço</th><th>Ações</th></tr>";
echo "<tbody id='resultadoEstoques'>";
escreveLinhasEstoques($estoques, $isFornecedor);
echo "</tbody>";
echo "</table>";

echo "<div id='paginacaoEstoques'>";
if (!$filtraFornecedor) {
    echo "<p>Página {$paginaAtual} de {$totalPaginas}</p>";
    if ($totalPaginas > 1) {
        echo "<nav>";
        if ($paginaAtual > 1) {
            $ant = $paginaAtual - 1;
            echo "<a href='lista_estoques.php?pagina={$ant}' class='btn btn-default left-margin'>Anterior</a>";
        }
        if ($paginaAtual < $totalPaginas) {
            $prox = $paginaAtual + 1;
            echo "<a href='" . BASE_URL . "/views/listagem/lista_estoques.php?pagina={$prox}' class='btn btn-default left-margin'>Próxima</a>";
        }
        echo "</nav>";
    }
}
echo "</div>";

if (!$isFornecedor) {
    echo "<a href='" . BASE_URL . "/views/cadastro/novo_estoque.php' class='btn btn-primary left-margin'>Novo</a>";
}

echo "</section>";
?>

<script>
document.getElementById('pesquisaEstoque').addEventListener('keyup', function() {
    var termo = this.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('resultadoEstoques').innerHTML = this.responseText;
            document.getElementById('paginacaoEstoques').style.display = termo.trim() !== '' ? 'none' : 'block';
        }
    };
    xhttp.open("GET", "lista_estoques.php?ajax=1&pesquisa=" + encodeURIComponent(termo), true);
    xhttp.send();
});
</script>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
