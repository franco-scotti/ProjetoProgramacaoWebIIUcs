<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEnderecoDao();
$endereco = $dao->buscaPorId($id);

if($endereco) {
    $page_title = "Demo : Exibindo Endereco";
} else {
    $page_title = "Demo : Endereco nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($endereco) {
    $fornecedorNome = $endereco->getFornecedor() ? $endereco->getFornecedor()->getNome() : '';
    $clienteNome = $endereco->getCliente() ? $endereco->getCliente()->getNome() : '';

    echo "<section>";
    echo "<h1> Rua : " . $endereco->getRua() . "</h1>";
    echo "<p>Id : " . $endereco->getId() . "</p>";
    echo "<p>Numero : " . $endereco->getNumero() . "</p>";
    echo "<p>Complemento : " . $endereco->getComplemento() . "</p>";
    echo "<p>Bairro : " . $endereco->getBairro() . "</p>";
    echo "<p>CEP : " . $endereco->getCep() . "</p>";
    echo "<p>Cidade : " . $endereco->getCidade() . "</p>";
    echo "<p>Estado : " . $endereco->getEstado() . "</p>";
    echo "<p>Fornecedor : " . $fornecedorNome . "</p>";
    echo "<p>Cliente : " . $clienteNome . "</p>";
    echo "<a href='" . BASE_URL . "/views/listagem/lista_enderecos.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
