<?php

include_once('ItemPedidoDao.php');
include_once('PostgresDao.php');

class PostgresItemPedidoDao extends PostgresDao implements ItemPedidoDao {

    private $table_name = 'item_pedido';

    public function insere($itemPedido) {
        $pedido_id = null;
        $produto_id = null;

        if ($itemPedido->getPedido() !== null) {
            $pedido_id = $itemPedido->getPedido()->getId();
        }

        if ($itemPedido->getProduto() !== null) {
            $produto_id = $itemPedido->getProduto()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (pedido_id, produto_id, quantidade, preco) VALUES" .
        " (:pedido_id, :produto_id, :quantidade, :preco)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pedido_id', $pedido_id);
        $stmt->bindValue(':produto_id', $produto_id);
        $stmt->bindValue(':quantidade', $itemPedido->getQuantidade());
        $stmt->bindValue(':preco', $itemPedido->getPreco());

        return $stmt->execute();
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function remove($itemPedido) {
        return $this->removePorId($itemPedido->getId());
    }

    public function altera(&$itemPedido) {
        $pedido_id = null;
        $produto_id = null;

        if ($itemPedido->getPedido() !== null) {
            $pedido_id = $itemPedido->getPedido()->getId();
        }

        if ($itemPedido->getProduto() !== null) {
            $produto_id = $itemPedido->getProduto()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET pedido_id = :pedido_id, produto_id = :produto_id, quantidade = :quantidade, preco = :preco" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':pedido_id', $pedido_id);
        $stmt->bindValue(':produto_id', $produto_id);
        $stmt->bindValue(':quantidade', $itemPedido->getQuantidade());
        $stmt->bindValue(':preco', $itemPedido->getPreco());
        $stmt->bindValue(':id', $itemPedido->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $itemPedido = null;
        $query = "SELECT id, pedido_id, produto_id, quantidade, preco FROM " . $this->table_name . " WHERE id = ? LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $itemPedido = new ItemPedido($row['id'], $row['quantidade'], $row['preco']);
            if ($row['pedido_id']) {
                $itemPedido->setPedido(new Pedido($row['pedido_id'], null, null, null, null));
            }
            if ($row['produto_id']) {
                $itemPedido->setProduto(new Produto($row['produto_id'], '', '', null));
            }
        }

        return $itemPedido;
    }

    public function buscaTodos() {
        $itensPedido = array();
        $query = "SELECT id, pedido_id, produto_id, quantidade, preco FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $itemPedido = new ItemPedido($row['id'], $row['quantidade'], $row['preco']);
            if ($row['pedido_id']) {
                $itemPedido->setPedido(new Pedido($row['pedido_id'], null, null, null, null));
            }
            if ($row['produto_id']) {
                $itemPedido->setProduto(new Produto($row['produto_id'], '', '', null));
            }
            $itensPedido[] = $itemPedido;
        }

        return $itensPedido;
    }
}
?>
