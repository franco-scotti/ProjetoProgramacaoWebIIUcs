<?php
include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEnderecoDao();
$endereco = $dao->buscaPorId($id);

if($endereco) {
    $page_title = "Demo : Exibindo Endereco";
} else {
    $page_title = "Demo : Endereco nao encontrado!";
}

include_once "layout_header.php";

if($endereco) {
    $fornecedorId = $endereco->getFornecedor() ? $endereco->getFornecedor()->getId() : '';
    $clienteId = $endereco->getCliente() ? $endereco->getCliente()->getId() : '';

    echo "<section>";
    echo "<h1> Rua : " . $endereco->getRua() . "</h1>";
    echo "<p>Id : " . $endereco->getId() . "</p>";
    echo "<p>Numero : " . $endereco->getNumero() . "</p>";
    echo "<p>Complemento : " . $endereco->getComplemento() . "</p>";
    echo "<p>Bairro : " . $endereco->getBairro() . "</p>";
    echo "<p>CEP : " . $endereco->getCep() . "</p>";
    echo "<p>Cidade : " . $endereco->getCidade() . "</p>";
    echo "<p>Estado : " . $endereco->getEstado() . "</p>";
    echo "<p>Fornecedor ID : " . $fornecedorId . "</p>";
    echo "<p>Cliente ID : " . $clienteId . "</p>";
    echo "<a href='enderecos.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once "layout_footer.php";
?>
