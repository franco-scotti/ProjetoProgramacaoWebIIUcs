<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

// layout do cabeçalho
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$page_title = "Demo : Listagem de Usuários";

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

echo "<section>";

// procura usuários

$dao = $factory->getUsuarioDao();
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

// display the products if there are any
if($usuarios) {
 
	echo "<table class='table table-hover table-responsive table-bordered'>";
	echo "<tr>";
		echo "<th>Id</th>";
		echo "<th>Login</th>";
		echo "<th>Nome</th>";
	echo "</tr>";

	foreach ($usuarios as $usuario) {

		echo "<tr>";
			echo "<td>{$usuario->getId()}</td>";
			echo "<td>{$usuario->getLogin()}</td>";
			echo "<td>{$usuario->getNome()}</td>";
			echo "<td>";
				echo "<a href='" . BASE_URL . "/views/detalhes/mostra_usuario.php?id={$usuario->getId()}' class='btn btn-primary left-margin'>";
                    echo "<span class='glyphicon glyphicon-list'></span> Mostra";
                echo "</a>";

                echo "<a href='" . BASE_URL . "/views/alteracao/modifica_usuario.php?id={$usuario->getId()}' class='btn btn-info left-margin'>";
                    echo "<span class='glyphicon glyphicon-edit'></span> Altera";
                echo "</a>";

                echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_usuario.php?id={$usuario->getId()}' class='btn btn-danger left-margin' ";
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
		echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php?pagina={$paginaAnterior}' class='btn btn-default left-margin'>Anterior</a>";
	}
	if ($paginaAtual < $totalPaginas) {
		$proximaPagina = $paginaAtual + 1;
		echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php?pagina={$proximaPagina}' class='btn btn-default left-margin'>Proxima</a>";
	}
	echo "</nav>";
}
 
echo "<a href='" . BASE_URL . "/views/cadastro/novo_usuario.php' class='btn btn-primary left-margin'>";
echo "Novo";
echo "</a>";

echo "</section>";

// layout do rodapé
include_once dirname(__DIR__) . "/layout/layout_footer.php";


