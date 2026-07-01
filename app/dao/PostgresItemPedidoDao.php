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

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
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

        $query = "SELECT 
                    ip.id,
                    ip.pedido_id,
                    ip.produto_id,
                    ip.quantidade,
                    ip.preco,

                    p.numero AS pedido_numero,
                    p.data_pedido AS pedido_data_pedido,
                    p.data_entrega AS pedido_data_entrega,
                    p.situacao AS pedido_situacao,

                    pr.nome AS produto_nome,
                    pr.descricao AS produto_descricao,
                    pr.foto AS produto_foto
                  FROM item_pedido ip
                  LEFT JOIN pedido p ON p.id = ip.pedido_id
                  LEFT JOIN produto pr ON pr.id = ip.produto_id
                  WHERE ip.id = ?
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $itemPedido = new ItemPedido(
                $row['id'],
                $row['quantidade'],
                $row['preco']
            );

            if ($row['pedido_id']) {
                $pedido = new Pedido(
                    $row['pedido_id'],
                    $row['pedido_numero'],
                    $row['pedido_data_pedido'],
                    $row['pedido_data_entrega'],
                    $row['pedido_situacao']
                );

                $itemPedido->setPedido($pedido);
            }

            if ($row['produto_id']) {
                $produto = new Produto(
                    $row['produto_id'],
                    $row['produto_nome'],
                    $row['produto_descricao'],
                    $row['produto_foto']
                );

                $itemPedido->setProduto($produto);
            }
        }

        return $itemPedido;
    }

    public function buscaTodos($limit = null, $offset = null) {
        $itensPedido = array();

        $query = "SELECT 
                    ip.id,
                    ip.pedido_id,
                    ip.produto_id,
                    ip.quantidade,
                    ip.preco,

                    p.numero AS pedido_numero,
                    p.data_pedido AS pedido_data_pedido,
                    p.data_entrega AS pedido_data_entrega,
                    p.situacao AS pedido_situacao,

                    pr.nome AS produto_nome,
                    pr.descricao AS produto_descricao,
                    pr.foto AS produto_foto
                  FROM item_pedido ip
                  LEFT JOIN pedido p ON p.id = ip.pedido_id
                  LEFT JOIN produto pr ON pr.id = ip.produto_id
                  ORDER BY ip.id ASC";

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
            $itemPedido = new ItemPedido(
                $row['id'],
                $row['quantidade'],
                $row['preco']
            );

            if ($row['pedido_id']) {
                $pedido = new Pedido(
                    $row['pedido_id'],
                    $row['pedido_numero'],
                    $row['pedido_data_pedido'],
                    $row['pedido_data_entrega'],
                    $row['pedido_situacao']
                );

                $itemPedido->setPedido($pedido);
            }

            if ($row['produto_id']) {
                $produto = new Produto(
                    $row['produto_id'],
                    $row['produto_nome'],
                    $row['produto_descricao'],
                    $row['produto_foto']
                );

                $itemPedido->setProduto($produto);
            }

            $itensPedido[] = $itemPedido;
        }

        return $itensPedido;
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
