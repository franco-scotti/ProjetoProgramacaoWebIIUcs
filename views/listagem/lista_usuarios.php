<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$dao = $factory->getUsuarioDao();

function escreveLinhasUsuarios($usuarios) {
    if($usuarios) {
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>{$usuario->getId()}</td>";
            echo "<td>{$usuario->getLogin()}</td>";
            echo "<td>{$usuario->getNome()}</td>";
            echo "<td>" . ($usuario->isAdmin() ? 'Sim' : 'Não') . "</td>";
            echo "<td>";
            echo "<a href='" . BASE_URL . "/views/detalhes/mostra_usuario.php?id={$usuario->getId()}' class='btn btn-primary left-margin'>";
            echo "<span class='glyphicon glyphicon-list'></span> Mostra";
            echo "</a>";

            echo "<a href='" . BASE_URL . "/views/altera/modifica_usuario.php?id={$usuario->getId()}' class='btn btn-info left-margin'>";
            echo "<span class='glyphicon glyphicon-edit'></span> Altera";
            echo "</a>";

            echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_usuario.php?id={$usuario->getId()}' class='btn btn-danger left-margin' ";
            echo "onclick=\"return confirm('Tem certeza que quer excluir?')\">";
            echo "<span class='glyphicon glyphicon-remove'></span> Exclui";
            echo "</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Nenhum usuário encontrado</td></tr>";
    }
}

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $termo = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

    if ($termo != '') {
        $usuarios = $dao->buscaPorCodigoNome($termo);
    } else {
        $usuarios = $dao->buscaTodos(10, 0);
    }

    escreveLinhasUsuarios($usuarios);
    exit;
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$page_title = "Demo : Listagem de Usuários";

include_once dirname(__DIR__) . "/layout/layout_header.php";

$itensPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($paginaAtual < 1) {
    $paginaAtual = 1;
}

$totalUsuarios = $dao->contaTodos();
$totalPaginas = max(1, (int)ceil($totalUsuarios / $itensPorPagina));
if ($paginaAtual > $totalPaginas) {
    $paginaAtual = $totalPaginas;
}

$offset = ($paginaAtual - 1) * $itensPorPagina;
$usuarios = $dao->buscaTodos($itensPorPagina, $offset);

echo "<section>";
echo "<input type='text' id='pesquisaUsuario' class='form-control' placeholder='Buscar por código, login ou nome'>";
echo "<br>";

echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
        echo "<th>Id</th>";
        echo "<th>Login</th>";
        echo "<th>Nome</th>";
        echo "<th>Admin</th>";
        echo "<th>Ações</th>";
    echo "</tr>";

    echo "<tbody id='resultadoUsuarios'>";
        escreveLinhasUsuarios($usuarios);
    echo "</tbody>";
echo "</table>";

echo "<div id='paginacaoUsuarios'>";

echo "<p>Pagina {$paginaAtual} de {$totalPaginas}</p>";

if ($totalPaginas > 1) {
    echo "<nav>";

    if ($paginaAtual > 1) {
        $paginaAnterior = $paginaAtual - 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php?pagina={$paginaAnterior}' class='btn btn-default left-margin'>Anterior</a>";
    }

    if ($paginaAtual < $totalPaginas) {
        $proximaPagina = $paginaAtual + 1;
        echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php?pagina={$proximaPagina}' class='btn btn-default left-margin'>Proxima</a>";
    }

    echo "</nav>";
}

echo "</div>";

echo "<a href='" . BASE_URL . "/views/cadastro/novo_usuario.php' class='btn btn-primary left-margin'>";
echo "Novo";
echo "</a>";

echo "</section>";
?>

<script>
document.getElementById('pesquisaUsuario').addEventListener('keyup', function() {

    var termo = this.value;

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('resultadoUsuarios').innerHTML = this.responseText;

            if (termo.trim() != '') {
                document.getElementById('paginacaoUsuarios').style.display = 'none';
            } else {
                document.getElementById('paginacaoUsuarios').style.display = 'block';
            }
        }
    };

    xhttp.open("GET", "lista_usuarios.php?ajax=1&pesquisa=" + encodeURIComponent(termo), true);
    xhttp.send();
});
</script>

<?php
include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
