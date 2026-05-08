<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getItemPedidoDao();
$item = $dao->buscaPorId($id);

if($item) {
    $page_title = "Demo : Exibindo Item de Pedido";
} else {
    $page_title = "Demo : Item de Pedido nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($item) {
    $pedidoNumero = $item->getPedido() ? $item->getPedido()->getNumero() : '';
    $produtoNome = $item->getProduto() ? $item->getProduto()->getNome() : '';
    echo "<section>";
    echo "<h1> Item ID : " . $item->getId() . "</h1>";
    echo "<p>Pedido : " . $pedidoNumero . "</p>";
    echo "<p>Produto : " . $produtoNome . "</p>";
    echo "<p>Quantidade : " . $item->getQuantidade() . "</p>";
    echo "<p>Preco : " . $item->getPreco() . "</p>";
    echo "<a href='" . BASE_URL . "/views/listagem/lista_itens_pedido.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
