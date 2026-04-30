<?php

include_once('EstoqueDao.php');
include_once('PostgresDao.php');

class PostgresEstoqueDao extends PostgresDao implements EstoqueDao {

    private $table_name = 'estoque';

    public function insere($estoque) {
        $produto_id = null;
        if ($estoque->getProduto() !== null) {
            $produto_id = $estoque->getProduto()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (produto_id, quantidade, preco) VALUES" .
        " (:produto_id, :quantidade, :preco)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':produto_id', $produto_id);
        $stmt->bindValue(':quantidade', $estoque->getQuantidade());
        $stmt->bindValue(':preco', $estoque->getPreco());

        return $stmt->execute();
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function remove($estoque) {
        return $this->removePorId($estoque->getId());
    }

    public function altera(&$estoque) {
        $produto_id = null;
        if ($estoque->getProduto() !== null) {
            $produto_id = $estoque->getProduto()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET produto_id = :produto_id, quantidade = :quantidade, preco = :preco" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':produto_id', $produto_id);
        $stmt->bindValue(':quantidade', $estoque->getQuantidade());
        $stmt->bindValue(':preco', $estoque->getPreco());
        $stmt->bindValue(':id', $estoque->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $estoque = null;
        $query = "SELECT id, produto_id, quantidade, preco FROM " . $this->table_name . " WHERE id = ? LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $estoque = new Estoque($row['id'], $row['quantidade'], $row['preco']);
            if ($row['produto_id']) {
                $estoque->setProduto(new Produto($row['produto_id'], '', '', null));
            }
        }

        return $estoque;
    }

    public function buscaTodos($limit = null, $offset = null) {
        $estoques = array();

        $query = "SELECT id, produto_id, quantidade, preco
                FROM " . $this->table_name . "
                ORDER BY id ASC";

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
            $estoque = new Estoque($row['id'], $row['quantidade'], $row['preco']);

            if ($row['produto_id']) {
                $estoque->setProduto(new Produto($row['produto_id'], '', '', null));
            }

            $estoques[] = $estoque;
        }

        return $estoques;
    }

    public function buscaPorCodigoNome($termo) {
        $estoques = array();

        $query = "SELECT e.id, e.produto_id, e.quantidade, e.preco
                FROM estoque e
                LEFT JOIN produto p ON p.id = e.produto_id
                WHERE CAST(e.id AS TEXT) ILIKE :termo
                    OR CAST(e.produto_id AS TEXT) ILIKE :termo
                    OR p.nome ILIKE :termo
                ORDER BY e.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $estoque = new Estoque($row['id'], $row['quantidade'], $row['preco']);

            if ($row['produto_id']) {
                $estoque->setProduto(new Produto($row['produto_id'], '', '', null));
            }

            $estoques[] = $estoque;
        }

        return $estoques;
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
