<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getClienteDao();
$cliente = $dao->buscaPorId($id);

if($cliente) {
    $page_title = "Demo : Exibindo Cliente : " . $cliente->getNome();
} else {
    $page_title = "Demo : Cliente nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($cliente) {

    $endereco = $cliente->getEndereco();

    echo "<section>";

    echo "<h1> Nome : " . $cliente->getNome() . "</h1>";

    echo "<p>Id : " . $cliente->getId() . "</p>";

    echo "<p>Telefone : " . $cliente->getTelefone() . "</p>";

    echo "<p>Email : " . $cliente->getEmail() . "</p>";

    echo "<p>Cartao : " . $cliente->getCartaoCredito() . "</p>";

    // BLOCO ENDEREÇO
    if ($endereco) {

        echo "<hr>";

        echo "<h3>Endereco</h3>";

        echo "<p>Rua : " . $endereco->getRua() . "</p>";

        echo "<p>Numero : " . $endereco->getNumero() . "</p>";

        echo "<p>Complemento : " . $endereco->getComplemento() . "</p>";

        echo "<p>Bairro : " . $endereco->getBairro() . "</p>";

        echo "<p>CEP : " . $endereco->getCep() . "</p>";

        echo "<p>Cidade : " . $endereco->getCidade() . "</p>";

        echo "<p>Estado : " . $endereco->getEstado() . "</p>";
    }

    echo "<a href='" . BASE_URL . "/views/listagem/lista_clientes.php' 
            class='btn btn-primary left-margin'>
            Voltar
          </a>";

    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
