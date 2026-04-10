<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getClienteDao();
$cliente = $dao->buscaPorId($id);

if($cliente) {
    $page_title = "Demo : Exibindo Cliente : " . $cliente->getNome();
} else {
    $page_title = "Demo : Cliente nao encontrado!";
}

include_once "layout_header.php";

if($cliente) {
    echo "<section>";
    echo "<h1> Nome : " . $cliente->getNome() . "</h1>";
    echo "<p>Id : " . $cliente->getId() . "</p>";
    echo "<p>Telefone : " . $cliente->getTelefone() . "</p>";
    echo "<p>Email : " . $cliente->getEmail() . "</p>";
    echo "<p>Cartao : " . $cliente->getCartaoCredito() . "</p>";
    echo "<a href='clientes.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
