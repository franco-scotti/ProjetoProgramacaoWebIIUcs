<?php
$page_title = "Demo : Listagem de Clientes";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

echo "<section>";

$dao = $factory->getClienteDao();
$clientes = $dao->buscaTodos();

if($clientes) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Nome</th><th>Telefone</th><th>Email</th><th>Cartao</th><th>Acoes</th></tr>";

    foreach ($clientes as $cliente) {
        echo "<tr>";
        echo "<td>{$cliente->getNome()}</td>";
        echo "<td>{$cliente->getTelefone()}</td>";
        echo "<td>{$cliente->getEmail()}</td>";
        echo "<td>{$cliente->getCartaoCredito()}</td>";
        echo "<td>";
        echo "<a href='" . BASE_URL . "/views/detalhes/mostra_cliente.php?id={$cliente->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='" . BASE_URL . "/views/cadastro/form_cliente.php?id={$cliente->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_cliente.php?id={$cliente->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='" . BASE_URL . "/views/cadastro/form_cliente.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
