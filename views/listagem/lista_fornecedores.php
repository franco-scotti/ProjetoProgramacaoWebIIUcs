<?php
$page_title = "Demo : Listagem de Fornecedores";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

// Somente admin pode listar fornecedores
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$dao = $factory->getFornecedorDao();

function escreveLinhasFornecedores($fornecedores) {
    if ($fornecedores) {
        foreach ($fornecedores as $fornecedor) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fornecedor->getNome()) . "</td>";
            echo "<td>" . htmlspecialchars($fornecedor->getDescricao()) . "</td>";
            echo "<td>" . htmlspecialchars($fornecedor->getTelefone()) . "</td>";
            echo "<td>" . htmlspecialchars($fornecedor->getEmail()) . "</td>";
            echo "<td>";
            echo "<a href='" . BASE_URL . "/views/detalhes/mostra_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
            echo "<a href='" . BASE_URL . "/views/cadastro/form_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
            echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Nenhum fornecedor encontrado</td></tr>";
    }
}

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $termo = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';
    $fornecedores = $termo != '' ? $dao->buscaPorCodigoNome($termo) : $dao->buscaTodos(10, 0);
    escreveLinhasFornecedores($fornecedores);
    exit;
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

echo "<section>";

$itensPorPagina = 10;
$paginaAtual    = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaAtual < 1) $paginaAtual = 1;

$totalFornecedores = $dao->contaTodos();
$totalPaginas      = max(1, (int)ceil($totalFornecedores / $itensPorPagina));
if ($paginaAtual > $totalPaginas) $paginaAtual = $totalPaginas;

$offset      = ($paginaAtual - 1) * $itensPorPagina;
$fornecedores = $dao->buscaTodos($itensPorPagina, $offset);

echo "<input type='text' id='pesquisaFornecedor' class='form-control' placeholder='Buscar por código ou nome'>";
echo "<br>";
echo "<table class='table table-hover table-responsive table-bordered'>";
echo "<tr><th>Nome</th><th>Descrição</th><th>Telefone</th><th>Email</th><th>Ações</th></tr>";
echo "<tbody id='resultadoFornecedores'>";
escreveLinhasFornecedores($fornecedores);
echo "</tbody>";
echo "</table>";

echo "<div id='paginacaoFornecedores'>";
echo "<p>Página {$paginaAtual} de {$totalPaginas}</p>";
if ($totalPaginas > 1) {
    echo "<nav>";
    if ($paginaAtual > 1) {
        $ant = $paginaAtual - 1;
        echo "<a href='lista_fornecedores.php?pagina={$ant}' class='btn btn-default left-margin'>Anterior</a>";
    }
    if ($paginaAtual < $totalPaginas) {
        $prox = $paginaAtual + 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_fornecedores.php?pagina={$prox}' class='btn btn-default left-margin'>Próxima</a>";
    }
    echo "</nav>";
}
echo "</div>";

echo "<a href='" . BASE_URL . "/views/cadastro/form_fornecedor.php' class='btn btn-primary left-margin'>Novo</a>";
echo "</section>";
?>

<script>
document.getElementById('pesquisaFornecedor').addEventListener('keyup', function() {
    var termo = this.value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('resultadoFornecedores').innerHTML = this.responseText;
            document.getElementById('paginacaoFornecedores').style.display = termo.trim() != '' ? 'none' : 'block';
        }
    };
    xhttp.open("GET", "lista_fornecedores.php?ajax=1&pesquisa=" + encodeURIComponent(termo), true);
    xhttp.send();
});
</script>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
