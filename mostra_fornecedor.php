<?php
include_once "fachada.php";

$id = @$_GET["id"];

$dao = $factory->getFornecedorDao();
$fornecedor = $dao->buscaPorId($id);
if($fornecedor) {
    $page_title = "Demo : Exibindo Fornecedor : " . $fornecedor->getNome();
} else {
    $page_title = "Demo : Fornecedor nao encontrado!";
}

include_once "layout_header.php";

if($fornecedor) {
    echo "<section>";
    echo "<h1> Nome : " . $fornecedor->getNome() . "</h1>";
    echo "<p> Id : " . $fornecedor->getId() . "</p>";
    echo "<p> Descricao : " . $fornecedor->getDescricao() . "</p>";
    echo "<p> Telefone : " . $fornecedor->getTelefone() . "</p>";
    echo "<p> Email : " . $fornecedor->getEmail() . "</p>";
    echo "<a href='fornecedores.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
