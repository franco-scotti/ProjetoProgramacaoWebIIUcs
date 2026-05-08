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

        $query = "SELECT 
                    e.id,
                    e.rua,
                    e.numero,
                    e.complemento,
                    e.bairro,
                    e.cep,
                    e.cidade,
                    e.estado,
                    e.fornecedor_id,
                    e.cliente_id,

                    f.nome AS fornecedor_nome,
                    f.descricao AS fornecedor_descricao,
                    f.telefone AS fornecedor_telefone,
                    f.email AS fornecedor_email,

                    c.nome AS cliente_nome,
                    c.telefone AS cliente_telefone,
                    c.email AS cliente_email,
                    c.cartao_credito AS cliente_cartao_credito
                  FROM endereco e
                  LEFT JOIN fornecedor f ON f.id = e.fornecedor_id
                  LEFT JOIN cliente c ON c.id = e.cliente_id
                  WHERE e.id = ?
                  LIMIT 1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $endereco = new Endereco(
                $row['id'],
                $row['rua'],
                $row['numero'],
                $row['complemento'],
                $row['bairro'],
                $row['cep'],
                $row['cidade'],
                $row['estado']
            );

            if ($row['fornecedor_id']) {
                $fornecedor = new Fornecedor(
                    $row['fornecedor_id'],
                    $row['fornecedor_nome'],
                    $row['fornecedor_descricao'],
                    $row['fornecedor_telefone'],
                    $row['fornecedor_email']
                );

                $endereco->setFornecedor($fornecedor);
            }

            if ($row['cliente_id']) {
                $cliente = new Cliente(
                    $row['cliente_id'],
                    $row['cliente_nome'],
                    $row['cliente_telefone'],
                    $row['cliente_email'],
                    $row['cliente_cartao_credito']
                );

                $endereco->setCliente($cliente);
            }
        }

        return $endereco;
    }

    public function buscaTodos($limit = null, $offset = null) {
        $enderecos = array();

        $query = "SELECT 
                    e.id,
                    e.rua,
                    e.numero,
                    e.complemento,
                    e.bairro,
                    e.cep,
                    e.cidade,
                    e.estado,
                    e.fornecedor_id,
                    e.cliente_id,

                    f.nome AS fornecedor_nome,
                    f.descricao AS fornecedor_descricao,
                    f.telefone AS fornecedor_telefone,
                    f.email AS fornecedor_email,

                    c.nome AS cliente_nome,
                    c.telefone AS cliente_telefone,
                    c.email AS cliente_email,
                    c.cartao_credito AS cliente_cartao_credito
                  FROM endereco e
                  LEFT JOIN fornecedor f ON f.id = e.fornecedor_id
                  LEFT JOIN cliente c ON c.id = e.cliente_id
                  ORDER BY e.id ASC";

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
            $endereco = new Endereco(
                $row['id'],
                $row['rua'],
                $row['numero'],
                $row['complemento'],
                $row['bairro'],
                $row['cep'],
                $row['cidade'],
                $row['estado']
            );

            if ($row['fornecedor_id']) {
                $fornecedor = new Fornecedor(
                    $row['fornecedor_id'],
                    $row['fornecedor_nome'],
                    $row['fornecedor_descricao'],
                    $row['fornecedor_telefone'],
                    $row['fornecedor_email']
                );

                $endereco->setFornecedor($fornecedor);
            }

            if ($row['cliente_id']) {
                $cliente = new Cliente(
                    $row['cliente_id'],
                    $row['cliente_nome'],
                    $row['cliente_telefone'],
                    $row['cliente_email'],
                    $row['cliente_cartao_credito']
                );

                $endereco->setCliente($cliente);
            }

            $enderecos[] = $endereco;
        }

        return $enderecos;
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
