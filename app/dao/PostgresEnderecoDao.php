<?php

include_once('EnderecoDao.php');
include_once('PostgresDao.php');

class PostgresEnderecoDao extends PostgresDao implements EnderecoDao {

    private $table_name = 'endereco';

    public function insere($endereco) {
        $fornecedor_id = null;
        $cliente_id = null;

        if ($endereco->getFornecedor() !== null) {
            $fornecedor_id = $endereco->getFornecedor()->getId();
        }

        if ($endereco->getCliente() !== null) {
            $cliente_id = $endereco->getCliente()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (rua, numero, complemento, bairro, cep, cidade, estado, fornecedor_id, cliente_id) VALUES" .
        " (:rua, :numero, :complemento, :bairro, :cep, :cidade, :estado, :fornecedor_id, :cliente_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':rua', $endereco->getRua());
        $stmt->bindValue(':numero', $endereco->getNumero());
        $stmt->bindValue(':complemento', $endereco->getComplemento());
        $stmt->bindValue(':bairro', $endereco->getBairro());
        $stmt->bindValue(':cep', $endereco->getCep());
        $stmt->bindValue(':cidade', $endereco->getCidade());
        $stmt->bindValue(':estado', $endereco->getEstado());
        $stmt->bindValue(':fornecedor_id', $fornecedor_id);
        $stmt->bindValue(':cliente_id', $cliente_id);

        return $stmt->execute();
    }

    public function removePorId($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function remove($endereco) {
        return $this->removePorId($endereco->getId());
    }

    public function altera(&$endereco) {
        $fornecedor_id = null;
        $cliente_id = null;

        if ($endereco->getFornecedor() !== null) {
            $fornecedor_id = $endereco->getFornecedor()->getId();
        }

        if ($endereco->getCliente() !== null) {
            $cliente_id = $endereco->getCliente()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET rua = :rua, numero = :numero, complemento = :complemento, bairro = :bairro, cep = :cep, cidade = :cidade, estado = :estado, fornecedor_id = :fornecedor_id, cliente_id = :cliente_id" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':rua', $endereco->getRua());
        $stmt->bindValue(':numero', $endereco->getNumero());
        $stmt->bindValue(':complemento', $endereco->getComplemento());
        $stmt->bindValue(':bairro', $endereco->getBairro());
        $stmt->bindValue(':cep', $endereco->getCep());
        $stmt->bindValue(':cidade', $endereco->getCidade());
        $stmt->bindValue(':estado', $endereco->getEstado());
        $stmt->bindValue(':fornecedor_id', $fornecedor_id);
        $stmt->bindValue(':cliente_id', $cliente_id);
        $stmt->bindValue(':id', $endereco->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $endereco = null;
        $query = "SELECT id, rua, numero, complemento, bairro, cep, cidade, estado, fornecedor_id, cliente_id FROM " . $this->table_name . " WHERE id = ? LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $endereco = new Endereco($row['id'], $row['rua'], $row['numero'], $row['complemento'], $row['bairro'], $row['cep'], $row['cidade'], $row['estado']);
            if ($row['fornecedor_id']) {
                $endereco->setFornecedor(new Fornecedor($row['fornecedor_id'], '', '', '', ''));
            }
            if ($row['cliente_id']) {
                $endereco->setCliente(new Cliente($row['cliente_id'], '', '', '', ''));
            }
        }

        return $endereco;
    }

    public function buscaTodos() {
        $enderecos = array();
        $query = "SELECT id, rua, numero, complemento, bairro, cep, cidade, estado, fornecedor_id, cliente_id FROM " . $this->table_name . " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $endereco = new Endereco($row['id'], $row['rua'], $row['numero'], $row['complemento'], $row['bairro'], $row['cep'], $row['cidade'], $row['estado']);
            if ($row['fornecedor_id']) {
                $endereco->setFornecedor(new Fornecedor($row['fornecedor_id'], '', '', '', ''));
            }
            if ($row['cliente_id']) {
                $endereco->setCliente(new Cliente($row['cliente_id'], '', '', '', ''));
            }
            $enderecos[] = $endereco;
        }

        return $enderecos;
    }
}
?>
