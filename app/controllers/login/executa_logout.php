<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/comum.php";

//if ( is_session_started() === FALSE ) {
    session_start();
    if(isset($_SESSION["nome_usuario"])) {
        session_destroy();
        header("location: " . BASE_URL . "/public/index.php");
        exit();
    } 
//} 
?>


		