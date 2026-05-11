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
        $query = "SELECT 
            p.id,
            p.nome,
            p.descricao,
            p.foto,
            p.fornecedor_id,
            f.nome AS fornecedor_nome,
            f.descricao AS fornecedor_descricao,
            f.telefone AS fornecedor_telefone,
            f.email AS fornecedor_email
          FROM produto p
          LEFT JOIN fornecedor f ON f.id = p.fornecedor_id
          WHERE p.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $fornecedor = null;
            if ($row['fornecedor_id']) {
                $fornecedor = new Fornecedor(
                    $row['fornecedor_id'],
                    $row['fornecedor_nome'],
                    $row['fornecedor_descricao'],
                    $row['fornecedor_telefone'],
                    $row['fornecedor_email']
                );
            }

            $produto = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['foto']
            );
            $produto->setFornecedor($fornecedor);

            return $produto;
        }
    }    

    public function buscaTodos($limit = null, $offset = null) {
        $produtos = array();
        $query = "SELECT 
            p.id,
            p.nome,
            p.descricao,
            p.foto,
            p.fornecedor_id,
            f.nome AS fornecedor_nome,
            f.descricao AS fornecedor_descricao,
            f.telefone AS fornecedor_telefone,
            f.email AS fornecedor_email
          FROM produto p
          LEFT JOIN fornecedor f ON f.id = p.fornecedor_id
          ORDER BY p.id ASC";

        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $produto = new Produto($row['id'], $row['nome'], $row['descricao'], $row['foto']);
            if ($row['fornecedor_id']) {
                $produto->setFornecedor(new Fornecedor($row['fornecedor_id'], $row['fornecedor_nome'], $row['fornecedor_descricao'], $row['fornecedor_telefone'], $row['fornecedor_email']));
            }
            $produtos[] = $produto;
        }

        return $produtos;
    }

    public function buscaPorCodigoNome($termo) {
        $produtos = array();
        $query = "SELECT 
            p.id,
            p.nome,
            p.descricao,
            p.foto,
            p.fornecedor_id,

            f.id AS fornecedor_id,
            f.nome AS fornecedor_nome,
            f.descricao AS fornecedor_descricao,
            f.telefone AS fornecedor_telefone,
            f.email AS fornecedor_email
          FROM produto p
          LEFT JOIN fornecedor f ON f.id = p.fornecedor_id
          WHERE CAST(p.id AS TEXT) ILIKE :termo OR p.nome ILIKE :termo
          ORDER BY p.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $produto = new Produto($row['id'], $row['nome'], $row['descricao'], $row['foto']);
            if ($row['fornecedor_id']) {
                $produto->setFornecedor(new Fornecedor($row['fornecedor_id'], $row['fornecedor_nome'], $row['fornecedor_descricao'], $row['fornecedor_telefone'], $row['fornecedor_email']));
            }
            $produtos[] = $produto;
        }

        return $produtos;
    }

    public function contaTodos() {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
}
?>
