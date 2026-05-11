<?php

include_once('ClienteDao.php');
include_once('PostgresDao.php');

class PostgresClienteDao extends PostgresDao implements ClienteDao {

    private $table_name = 'cliente';

    public function insere($cliente) {
        $endereco_id = null;

        if ($cliente->getEndereco() !== null) {
            $endereco_id = $cliente->getEndereco()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (nome, telefone, email, cartao_credito, endereco_id) VALUES" .
        " (:nome, :telefone, :email, :cartao_credito, :endereco_id)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':telefone', $cliente->getTelefone());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':cartao_credito', $cliente->getCartaoCredito());
        $stmt->bindValue(':endereco_id', $endereco_id);

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
        $endereco_id = null;

        if ($cliente->getEndereco() !== null) {
            $endereco_id = $cliente->getEndereco()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET nome = :nome, telefone = :telefone, email = :email, cartao_credito = :cartao_credito, endereco_id = :endereco_id" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $cliente->getNome());
        $stmt->bindValue(':telefone', $cliente->getTelefone());
        $stmt->bindValue(':email', $cliente->getEmail());
        $stmt->bindValue(':cartao_credito', $cliente->getCartaoCredito());
        $stmt->bindValue(':endereco_id', $endereco_id);
        $stmt->bindValue(':id', $cliente->getId());

        return $stmt->execute();
    }

    public function buscaPorId($id) {
        $cliente = null;

        $query = "SELECT 
                    c.id,
                    c.nome,
                    c.telefone,
                    c.email,
                    c.cartao_credito,
                    c.endereco_id,

                    e.rua AS endereco_rua,
                    e.numero AS endereco_numero,
                    e.complemento AS endereco_complemento,
                    e.bairro AS endereco_bairro,
                    e.cep AS endereco_cep,
                    e.cidade AS endereco_cidade,
                    e.estado AS endereco_estado

                  FROM cliente c
                  LEFT JOIN endereco e ON e.id = c.endereco_id
                  WHERE c.id = ?
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $cliente = new Cliente(
                $row['id'],
                $row['nome'],
                $row['telefone'],
                $row['email'],
                $row['cartao_credito']
            );

            if ($row['endereco_id']) {
                $endereco = new Endereco(
                    $row['endereco_id'],
                    $row['endereco_rua'],
                    $row['endereco_numero'],
                    $row['endereco_complemento'],
                    $row['endereco_bairro'],
                    $row['endereco_cep'],
                    $row['endereco_cidade'],
                    $row['endereco_estado']
                );

                $cliente->setEndereco($endereco);
            }
        }

        return $cliente;
    }

    public function buscaTodos($limit = null, $offset = null) {
        $clientes = array();

        $query = "SELECT 
                    c.id,
                    c.nome,
                    c.telefone,
                    c.email,
                    c.cartao_credito,
                    c.endereco_id,

                    e.rua AS endereco_rua,
                    e.numero AS endereco_numero,
                    e.complemento AS endereco_complemento,
                    e.bairro AS endereco_bairro,
                    e.cep AS endereco_cep,
                    e.cidade AS endereco_cidade,
                    e.estado AS endereco_estado

                  FROM cliente c
                  LEFT JOIN endereco e ON e.id = c.endereco_id
                  ORDER BY c.id ASC";

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
            $cliente = new Cliente(
                $row['id'],
                $row['nome'],
                $row['telefone'],
                $row['email'],
                $row['cartao_credito']
            );

            if ($row['endereco_id']) {
                $endereco = new Endereco(
                    $row['endereco_id'],
                    $row['endereco_rua'],
                    $row['endereco_numero'],
                    $row['endereco_complemento'],
                    $row['endereco_bairro'],
                    $row['endereco_cep'],
                    $row['endereco_cidade'],
                    $row['endereco_estado']
                );

                $cliente->setEndereco($endereco);
            }

            $clientes[] = $cliente;
        }

        return $clientes;
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
