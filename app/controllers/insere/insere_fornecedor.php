<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

$nome = trim((string)@$_GET["nome"]);
$descricao = trim((string)@$_GET["descricao"]);
$telefone = trim((string)@$_GET["telefone"]);
$email = trim((string)@$_GET["email"]);

$fornecedor = new Fornecedor(null, $nome, $descricao, $telefone, $email);
$dao = $factory->getFornecedorDao();
$dao->insere($fornecedor);

header("Location: " . BASE_URL . "/views/listagem/lista_fornecedores.php");
exit;
?>
