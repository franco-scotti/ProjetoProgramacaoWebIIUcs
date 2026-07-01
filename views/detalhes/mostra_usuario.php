<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include  dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";
$id = @$_GET["id"];

$dao = $factory->getUsuarioDao();
$clienteDao = $factory->getClienteDao();
$fornecedorDao = $factory->getFornecedorDao();
$usuario = $dao->buscaPorId($id);
if($usuario) {
	$page_title = "Demo : Exibindo Usuário : " . $usuario->getNome();
} else {
	$page_title = "Demo : Usuário não encontrado!";
} 

include_once dirname(__DIR__) . "/layout/layout_header.php";
if($usuario) {
$cliente = $clienteDao->buscaPorUsuarioId($usuario->getId());
$fornecedor = $fornecedorDao->buscaPorUsuarioId($usuario->getId());

echo "<section>";
echo "<h1> Login : " . htmlspecialchars((string)$usuario->getLogin()) . "</h1>";
echo "<p> Id : " . $usuario->getId() . "</p>";
echo "<p> Nome : " . htmlspecialchars((string)$usuario->getNome()) . "</p>";
echo "<p> Admin : " . ($usuario->isAdmin() ? 'Sim' : 'Não') . "</p>";
echo "<p> Cliente : " . ($cliente ? 'Sim' : 'Não') . "</p>";
echo "<p> Fornecedor : " . ($fornecedor ? 'Sim' : 'Não') . "</p>";
echo "<a href='" . BASE_URL . "/views/listagem/lista_usuarios.php' class='btn btn-primary left-margin'>";
echo "Voltar";
echo "</a>";
echo "</section>";
}
include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
