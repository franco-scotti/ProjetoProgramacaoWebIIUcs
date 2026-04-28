<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include  dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";
$id = @$_GET["id"];

$dao = $factory->getUsuarioDao();
$usuario = $dao->buscaPorId($id);
if($usuario) {
	$page_title = "Demo : Exibindo Usuário : " . $usuario->getNome();
} else {
	$page_title = "Demo : Usuário não encontrado!";
} 

// layout do cabeçalho
include_once dirname(__DIR__) . "/layout/layout_header.php";
if($usuario) {
echo "<section>";
//dados do usuário
echo "<h1> Login : " . $usuario->getLogin() . "</h1>";
echo "<p> Id : " . $usuario->getId() . "</p>";
echo "<p> Nome : " . $usuario->getNome() . "</p>";
// botão voltar
echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php' class='btn btn-primary left-margin'>";
echo "Voltar";
echo "</a>";
echo "</section>";
}
// layout do rodapé
include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
