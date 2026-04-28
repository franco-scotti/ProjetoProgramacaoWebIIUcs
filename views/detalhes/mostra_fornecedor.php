<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];

$dao = $factory->getFornecedorDao();
$fornecedor = $dao->buscaPorId($id);
if($fornecedor) {
    $page_title = "Demo : Exibindo Fornecedor : " . $fornecedor->getNome();
} else {
    $page_title = "Demo : Fornecedor nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($fornecedor) {
    echo "<section>";
    echo "<h1> Nome : " . $fornecedor->getNome() . "</h1>";
    echo "<p> Id : " . $fornecedor->getId() . "</p>";
    echo "<p> Descricao : " . $fornecedor->getDescricao() . "</p>";
    echo "<p> Telefone : " . $fornecedor->getTelefone() . "</p>";
    echo "<p> Email : " . $fornecedor->getEmail() . "</p>";
    echo "<a href='" . BASE_URL . "/views/listagem/lista_fornecedores.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
