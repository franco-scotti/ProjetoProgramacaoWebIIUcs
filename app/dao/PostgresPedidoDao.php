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

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
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

        $query = "SELECT 
                    p.id,
                    p.numero,
                    p.data_pedido,
                    p.data_entrega,
                    p.situacao,
                    p.cliente_id,
                    c.nome AS cliente_nome,
                    c.telefone AS cliente_telefone,
                    c.email AS cliente_email,
                    c.cartao_credito AS cliente_cartao_credito
                  FROM pedido p
                  LEFT JOIN cliente c ON c.id = p.cliente_id
                  WHERE p.id = ?
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $pedido = new Pedido(
                $row['id'],
                $row['numero'],
                $row['data_pedido'],
                $row['data_entrega'],
                $row['situacao']
            );

            if ($row['cliente_id']) {
                $cliente = new Cliente(
                    $row['cliente_id'],
                    $row['cliente_nome'],
                    $row['cliente_telefone'],
                    $row['cliente_email'],
                    $row['cliente_cartao_credito']
                );

                $pedido->setCliente($cliente);
            }
        }

        return $pedido;
    }

    public function buscaTodos($limit = null, $offset = null) {
        $pedidos = array();

        $query = "SELECT 
                    p.id,
                    p.numero,
                    p.data_pedido,
                    p.data_entrega,
                    p.situacao,
                    p.cliente_id,
                    c.nome AS cliente_nome,
                    c.telefone AS cliente_telefone,
                    c.email AS cliente_email,
                    c.cartao_credito AS cliente_cartao_credito
                  FROM pedido p
                  LEFT JOIN cliente c ON c.id = p.cliente_id
                  ORDER BY p.id ASC";

        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);

        if ($limit !== null && $offset !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $pedido = new Pedido(
                $row['id'],
                $row['numero'],
                $row['data_pedido'],
                $row['data_entrega'],
                $row['situacao']
            );

            if ($row['cliente_id']) {
                $cliente = new Cliente(
                    $row['cliente_id'],
                    $row['cliente_nome'],
                    $row['cliente_telefone'],
                    $row['cliente_email'],
                    $row['cliente_cartao_credito']
                );

                $pedido->setCliente($cliente);
            }

            $pedidos[] = $pedido;
        }

        return $pedidos;
    }

    public function buscaPorFornecedorId($fornecedorId) {
        $pedidos = array();

        $query = "SELECT DISTINCT
                    p.id,
                    p.numero,
                    p.data_pedido,
                    p.data_entrega,
                    p.situacao,
                    p.cliente_id,
                    c.nome AS cliente_nome,
                    c.telefone AS cliente_telefone,
                    c.email AS cliente_email,
                    c.cartao_credito AS cliente_cartao_credito
                  FROM pedido p
                  LEFT JOIN cliente c ON c.id = p.cliente_id
                  INNER JOIN item_pedido ip ON ip.pedido_id = p.id
                  INNER JOIN produto pr ON pr.id = ip.produto_id
                  WHERE pr.fornecedor_id = :fornecedor_id
                  ORDER BY p.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':fornecedor_id', (int)$fornecedorId, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedido = new Pedido(
                $row['id'],
                $row['numero'],
                $row['data_pedido'],
                $row['data_entrega'],
                $row['situacao']
            );

            if ($row['cliente_id']) {
                $cliente = new Cliente(
                    $row['cliente_id'],
                    $row['cliente_nome'],
                    $row['cliente_telefone'],
                    $row['cliente_email'],
                    $row['cliente_cartao_credito']
                );
                $pedido->setCliente($cliente);
            }

            $pedidos[] = $pedido;
        }

        return $pedidos;
    }

    public function buscaPorNumeroOuCliente($termo) {
        $pedidos = array();

        $query = "SELECT
                    p.id,
                    p.numero,
                    p.data_pedido,
                    p.data_entrega,
                    p.situacao,
                    p.cliente_id,
                    c.nome AS cliente_nome,
                    c.telefone AS cliente_telefone,
                    c.email AS cliente_email,
                    c.cartao_credito AS cliente_cartao_credito
                  FROM pedido p
                  LEFT JOIN cliente c ON c.id = p.cliente_id
                  WHERE CAST(p.numero AS TEXT) ILIKE :termo
                     OR c.nome ILIKE :termo
                  ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pedido = new Pedido(
                $row['id'],
                $row['numero'],
                $row['data_pedido'],
                $row['data_entrega'],
                $row['situacao']
            );
            if ($row['cliente_id']) {
                $pedido->setCliente(new Cliente(
                    $row['cliente_id'],
                    $row['cliente_nome'],
                    $row['cliente_telefone'],
                    $row['cliente_email'],
                    $row['cliente_cartao_credito']
                ));
            }
            $pedidos[] = $pedido;
        }

        return $pedidos;
    }

    public function contaTodos() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    }
}
?>