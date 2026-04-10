<?php

include_once('ProdutoDao.php');
include_once('PostgresDao.php');

class PostgresProdutoDao extends PostgresDao implements ProdutoDao {

    private $table_name = 'produto';

    public function insere($produto) {
        $fornecedor_id = null;
        if ($produto->getFornecedor() !== null) {
            $fornecedor_id = $produto->getFornecedor()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (nome, descricao, foto, fornecedor_id) VALUES" .
        " (:nome, :descricao, :foto, :fornecedor_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':descricao', $produto->getDescricao());
        $stmt->bindValue(':foto', $produto->getFoto());
        $stmt->bindValue(':fornecedor_id', $fornecedor_id);

        return $stmt->execute();
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function remove($produto) {
        return $this->removePorId($produto->getId());
    }

    public function altera(&$produto) {
        $fornecedor_id = null;
        if ($produto->getFornecedor() !== null) {
            $fornecedor_id = $produto->getFornecedor()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET nome = :nome, descricao = :descricao, foto = :foto, fornecedor_id = :fornecedor_id" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $produto->getNome());
        $stmt->bindValue(':descricao', $produto->getDescricao());
        $stmt->bindValue(':foto', $produto->getFoto());
        $stmt->bindValue(':fornecedor_id', $fornecedor_id);
        $stmt->bindValue(':id', $produto->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $produto = null;
        $query = "SELECT id, nome, descricao, foto, fornecedor_id FROM " . $this->table_name . " WHERE id = ? LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $produto = new Produto($row['id'], $row['nome'], $row['descricao'], $row['foto']);
            if ($row['fornecedor_id']) {
                $produto->setFornecedor(new Fornecedor($row['fornecedor_id'], '', '', '', ''));
            }
        }

        return $produto;
    }

    public function buscaTodos() {
        $produtos = array();
        $query = "SELECT id, nome, descricao, foto, fornecedor_id FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $produto = new Produto($row['id'], $row['nome'], $row['descricao'], $row['foto']);
            if ($row['fornecedor_id']) {
                $produto->setFornecedor(new Fornecedor($row['fornecedor_id'], '', '', '', ''));
            }
            $produtos[] = $produto;
        }

        return $produtos;
    }
}
?>
