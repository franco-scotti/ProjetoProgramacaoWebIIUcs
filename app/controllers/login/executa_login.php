<?php 
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

require "../../../routes/fachada.php"; 
 
session_start();

$login = isset($_POST["login"]) ? addslashes(trim($_POST["login"])) : FALSE; 
$senha = isset($_POST["senha"]) ? trim($_POST["senha"]) : FALSE; 
 
if(!$login || !$senha) 
{ 
    echo "login = " . $login . " / senha = " . $senha . "<br>";
    echo "Você deve digitar sua senha e login!<br>"; 
    echo "<a href='" . BASE_URL . "/public/login.php'>Efetuar Login</a>";
    exit; 
}  

$dao = $factory->getUsuarioDao();
$usuario = $dao->buscaPorLogin($login);

$problemas = FALSE;
if($usuario) {
    if(!strcmp($senha, $usuario->getSenha())) 
    { 
        $_SESSION["id_usuario"]= $usuario->getId(); 
        $_SESSION["nome_usuario"] = stripslashes($usuario->getNome()); 
        header("Location: " . BASE_URL . "/public/index.php"); 
        exit; 
    } else {
        $problemas = TRUE; 
    }
} else {
    $problemas = TRUE; 
}

if($problemas==TRUE) {
    header("Location: " . BASE_URL . "/public/index.php"); 
    exit; 
}
?>
