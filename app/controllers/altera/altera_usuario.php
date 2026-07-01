<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

$id = @$_GET["id"];
$login = @$_GET["login"];
$senha = @$_GET["senha"];
$nome = @$_GET["nome"];
$admin = isset($_GET['admin']) && $_GET['admin'] == '1';
$campos = ['login','senha','nome'];
$dados = ['login' => $login, 'senha' => $senha, 'nome' => $nome];

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_usuario.php?id=" . $id . "&erro=campos_obrigatorios");
    exit;
}

$usuario = new Usuario($id,$login,$senha,$nome,$admin);
$dao = $factory->getUsuarioDao();

$usuario->setSenha(md5($usuario->getLogin().$usuario->getSenha()));

$dao->altera($usuario);

header("Location: " . BASE_URL . "/views/listagem/lista_usuarios.php");
exit;

?>
