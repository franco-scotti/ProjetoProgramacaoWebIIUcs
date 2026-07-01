<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

$login = @$_GET["login"];
$senha = @$_GET["senha"];
$nome = @$_GET["nome"];

$login = trim((string)$login);
$senha = trim((string)$senha);
$nome = trim((string)$nome);
$admin = isset($_GET['admin']) && $_GET['admin'] == '1';
$campos = ['login','senha','nome'];
$dados = ['login' => $login, 'senha' => $senha, 'nome' => $nome];

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_usuario.php?erro=campos_obrigatorios");
    exit;
}

$dao = $factory->getUsuarioDao();

if ($login === "" || $senha === "" || $nome === "") {
    header("Location: " . BASE_URL . "/views/cadastro/novo_usuario.php?erro=campos_obrigatorios");
    exit;
}

if ($dao->buscaPorLogin($login) !== null) {
    header("Location: " . BASE_URL . "/views/cadastro/novo_usuario.php?erro=login_duplicado");
    exit;
}

$usuario = new Usuario(null,$login,$senha,$nome,$admin);
$ok = $dao->insere($usuario);

if(!$ok){
    header("Location: " . BASE_URL . "/views/cadastro/novo_usuario.php?erro=erro_insercao");
    exit;
}

header("Location: " . BASE_URL . "/views/listagem/lista_usuarios.php");
exit;

?>
