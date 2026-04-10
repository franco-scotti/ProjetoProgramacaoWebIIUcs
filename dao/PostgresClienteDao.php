<?php

include_once('ClienteDao.php');
include_once('PostgresDao.php');

class PostgresClienteDao extends PostgresDao implements ClienteDao {

    private $table_name = 'cliente';

    public function insere($cliente) {
        $query = "INSERT INTO " . $this->table_name .
        " (nome, telefone, email, cartao_credito) VALUES" .
        " (:nome, :telefone, :email, :cartao_credito)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':telefone', $cliente->getTelefone());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':cartao_credito', $cliente->getCartaoCredito());

        return $stmt->execute();
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function remove($cliente) {
        return $this->removePorId($cliente->getId());
    }

    public function altera(&$cliente) {
        $query = "UPDATE " . $this->table_name .
        " SET nome = :nome, telefone = :telefone, email = :email, cartao_credito = :cartao_credito" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':telefone', $cliente->getTelefone());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':cartao_credito', $cliente->getCartaoCredito());
        $stmt->bindValue(':id', $cliente->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $cliente = null;
        $query = "SELECT id, nome, telefone, email, cartao_credito FROM " . $this->table_name . " WHERE id = ? LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $cliente = new Cliente($row['id'], $row['nome'], $row['telefone'], $row['email'], $row['cartao_credito']);
        }

        return $cliente;
    }

    public function buscaTodos() {
        $clientes = array();
        $query = "SELECT id, nome, telefone, email, cartao_credito FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $clientes[] = new Cliente($row['id'], $row['nome'], $row['telefone'], $row['email'], $row['cartao_credito']);
        }

        return $clientes;
    }
}
?>
