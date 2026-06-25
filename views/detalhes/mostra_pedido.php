<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getPedidoDao();
$pedido = $dao->buscaPorId($id);

if($pedido) {
    $page_title = "Demo : Exibindo Pedido";
} else {
    $page_title = "Demo : Pedido nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($pedido) {
    $clienteNome = $pedido->getCliente() ? $pedido->getCliente()->getNome() : 'Não especificado';
    $pdo = $factory->getConnection();
    $stmt = $pdo->prepare(
        "SELECT ip.quantidade, ip.preco, pr.nome AS produto_nome, pr.descricao AS produto_descricao
         FROM item_pedido ip
         LEFT JOIN produto pr ON pr.id = ip.produto_id
         WHERE ip.pedido_id = :pedido_id"
    );
    $stmt->bindValue(':pedido_id', $pedido->getId(), PDO::PARAM_INT);
    $stmt->execute();
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<section>";
    echo "<h1> Pedido Número : " . htmlspecialchars($pedido->getNumero()) . "</h1>";
    echo "<p>Id : " . htmlspecialchars($pedido->getId()) . "</p>";
    echo "<p>Data Pedido : " . htmlspecialchars($pedido->getDataPedido()) . "</p>";
    echo "<p>Data Entrega : " . htmlspecialchars($pedido->getDataEntrega()) . "</p>";
    echo "<p>Situação : " . htmlspecialchars($pedido->getSituacao()) . "</p>";
    echo "<p>Cliente : " . htmlspecialchars($clienteNome) . "</p>";

    if ($itens) {
        echo "<h3>Itens do pedido</h3>";
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Produto</th><th>Descrição</th><th>Quantidade</th><th>Preço</th><th>Subtotal</th></tr></thead>";
        echo "<tbody>";
        foreach ($itens as $item) {
            $subtotal = $item['quantidade'] * $item['preco'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['produto_nome']) . "</td>";
            echo "<td>" . htmlspecialchars($item['produto_descricao']) . "</td>";
            echo "<td>" . (int)$item['quantidade'] . "</td>";
            echo "<td>R$ " . number_format($item['preco'], 2, ',', '.') . "</td>";
            echo "<td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>Este pedido não contém itens.</p>";
    }

    echo "<a href='" . BASE_URL . "/views/listagem/lista_pedidos.php' class='btn btn-primary left-margin'>Voltar</a>";
    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
