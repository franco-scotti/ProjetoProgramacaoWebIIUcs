<?php

include_once('PedidoDao.php');
include_once('PostgresDao.php');

class PostgresPedidoDao extends PostgresDao implements PedidoDao {

    private $table_name = 'pedido';

    public function insere($pedido) {
        $cliente_id = null;
        if ($pedido->getCliente() !== null) {
            $cliente_id = $pedido->getCliente()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (numero, data_pedido, data_entrega, situacao, cliente_id) VALUES" .
        " (:numero, :data_pedido, :data_entrega, :situacao, :cliente_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':numero', $pedido->getNumero());
        $stmt->bindValue(':data_pedido', $pedido->getDataPedido());
        $stmt->bindValue(':data_entrega', $pedido->getDataEntrega());
        $stmt->bindValue(':situacao', $pedido->getSituacao());
        $stmt->bindValue(':cliente_id', $cliente_id);

        return $stmt->execute();
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function remove($pedido) {
        return $this->removePorId($pedido->getId());
    }

    public function altera(&$pedido) {
        $cliente_id = null;
        if ($pedido->getCliente() !== null) {
            $cliente_id = $pedido->getCliente()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET numero = :numero, data_pedido = :data_pedido, data_entrega = :data_entrega, situacao = :situacao, cliente_id = :cliente_id" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':numero', $pedido->getNumero());
        $stmt->bindValue(':data_pedido', $pedido->getDataPedido());
        $stmt->bindValue(':data_entrega', $pedido->getDataEntrega());
        $stmt->bindValue(':situacao', $pedido->getSituacao());
        $stmt->bindValue(':cliente_id', $cliente_id);
        $stmt->bindValue(':id', $pedido->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $pedido = null;
        $query = "SELECT id, numero, data_pedido, data_entrega, situacao, cliente_id FROM " . $this->table_name . " WHERE id = ? LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $pedido = new Pedido($row['id'], $row['numero'], $row['data_pedido'], $row['data_entrega'], $row['situacao']);
            if ($row['cliente_id']) {
                $pedido->setCliente(new Cliente($row['cliente_id'], '', '', '', ''));
            }
        }

        return $pedido;
    }

    public function buscaTodos() {
        $pedidos = array();
        $query = "SELECT id, numero, data_pedido, data_entrega, situacao, cliente_id FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $pedido = new Pedido($row['id'], $row['numero'], $row['data_pedido'], $row['data_entrega'], $row['situacao']);
            if ($row['cliente_id']) {
                $pedido->setCliente(new Cliente($row['cliente_id'], '', '', '', ''));
            }
            $pedidos[] = $pedido;
        }

        return $pedidos;
    }
}
?>
