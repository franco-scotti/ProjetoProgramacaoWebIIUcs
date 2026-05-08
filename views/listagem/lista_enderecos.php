<?php
$page_title = "Demo : Listagem de Enderecos";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

echo "<section>";

$dao = $factory->getEnderecoDao();
$enderecos = $dao->buscaTodos();

if($enderecos) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr><th>Id</th><th>Rua</th><th>Numero</th><th>Bairro</th><th>Cidade</th><th>Estado</th><th>Fornecedor</th><th>Cliente</th><th>Acoes</th></tr>";

    foreach ($enderecos as $endereco) {
        $fornecedorId = $endereco->getFornecedor() ? $endereco->getFornecedor()->getNome() : '';
        $clienteId = $endereco->getCliente() ? $endereco->getCliente()->getNome() : '';

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
        echo "<a href='" . BASE_URL . "/views/detalhes/mostra_endereco.php?id={$endereco->getId()}' class='btn btn-primary left-margin'><span class='glyphicon glyphicon-list'></span> Mostra</a>";
        echo "<a href='" . BASE_URL . "/views/altera/modifica_endereco.php?id={$endereco->getId()}' class='btn btn-info left-margin'><span class='glyphicon glyphicon-edit'></span> Altera</a>";
        echo "<a href='" . BASE_URL . "/app/controllers/remove/remove_endereco.php?id={$endereco->getId()}' class='btn btn-danger left-margin' onclick=\"return confirm('Tem certeza que quer excluir?')\"><span class='glyphicon glyphicon-remove'></span> Exclui</a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

echo "<a href='" . BASE_URL . "/views/cadastro/novo_endereco.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
