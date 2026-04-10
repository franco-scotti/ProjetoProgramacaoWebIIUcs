<?php
$page_title = "Demo : Listagem de Clientes";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getClienteDao();
$clientes = $dao->buscaTodos();

if($clientes) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Nome</th><th>Telefone</th><th>Email</th><th>Cartao</th><th>Acoes</th></tr>";

    foreach ($clientes as $cliente) {
        echo "<tr>";
        echo "<td>{$cliente->getId()}</td>";
        echo "<td>{$cliente->getNome()}</td>";
        echo "<td>{$cliente->getTelefone()}</td>";
        echo "<td>{$cliente->getEmail()}</td>";
        echo "<td>{$cliente->getCartaoCredito()}</td>";
        echo "<td>";
        echo "<a href='mostra_cliente.php?id={$cliente->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='modifica_cliente.php?id={$cliente->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='remove_cliente.php?id={$cliente->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='novo_cliente.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
