<?php

include_once('FornecedorDao.php');
include_once('PostgresDao.php');

class PostgresFornecedorDao extends PostgresDao implements FornecedorDao {

    private $table_name = 'fornecedor';

    public function insere($fornecedor) {

        $query = "INSERT INTO " . $this->table_name .
        " (nome, descricao, telefone, email) VALUES" .
        " (:nome, :descricao, :telefone, :email)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":nome", $fornecedor->getNome());
        $stmt->bindValue(":descricao", $fornecedor->getDescricao());
        $stmt->bindValue(":telefone", $fornecedor->getTelefone());
        $stmt->bindValue(":email", $fornecedor->getEmail());

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    public function remove($fornecedor) {
        return $this->removePorId($fornecedor->getId());
    }

    public function altera(&$fornecedor) {

        $query = "UPDATE " . $this->table_name .
        " SET nome = :nome, descricao = :descricao, telefone = :telefone, email = :email" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":nome", $fornecedor->getNome());
        $stmt->bindValue(":descricao", $fornecedor->getDescricao());
        $stmt->bindValue(":telefone", $fornecedor->getTelefone());
        $stmt->bindValue(":email", $fornecedor->getEmail());
        $stmt->bindValue(":id", $fornecedor->getId());

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    public function buscaPorId($id) {

        $fornecedor = null;

        $query = "SELECT
                    id, nome, descricao, telefone, email
                FROM
                    " . $this->table_name . "
                WHERE
                    id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $fornecedor = new Fornecedor($row['id'], $row['nome'], $row['descricao'], $row['telefone'], $row['email']);
        }

        return $fornecedor;
    }

    public function buscaTodos() {

        $fornecedores = array();

        $query = "SELECT
                    id, nome, descricao, telefone, email
                FROM
                    " . $this->table_name .
                    " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $fornecedores[] = new Fornecedor($row['id'], $row['nome'], $row['descricao'], $row['telefone'], $row['email']);
        }

        return $fornecedores;
    }
}
?>
