<?php
$page_title = "Demo : Listagem de Enderecos";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getEnderecoDao();
$enderecos = $dao->buscaTodos();

if($enderecos) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Rua</th><th>Numero</th><th>Bairro</th><th>Cidade</th><th>Estado</th><th>FornecedorId</th><th>ClienteId</th><th>Acoes</th></tr>";

    foreach ($enderecos as $endereco) {
        $fornecedorId = $endereco->getFornecedor() ? $endereco->getFornecedor()->getId() : '';
        $clienteId = $endereco->getCliente() ? $endereco->getCliente()->getId() : '';

        echo "<tr>";
        echo "<td>{$endereco->getId()}</td>";
        echo "<td>{$endereco->getRua()}</td>";
        echo "<td>{$endereco->getNumero()}</td>";
        echo "<td>{$endereco->getBairro()}</td>";
        echo "<td>{$endereco->getCidade()}</td>";
        echo "<td>{$endereco->getEstado()}</td>";
        echo "<td>{$fornecedorId}</td>";
        echo "<td>{$clienteId}</td>";
        echo "<td>";
        echo "<a href='mostra_endereco.php?id={$endereco->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='modifica_endereco.php?id={$endereco->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='remove_endereco.php?id={$endereco->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='novo_endereco.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
