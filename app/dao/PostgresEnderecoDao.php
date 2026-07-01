<?php

include_once('EnderecoDao.php');
include_once('PostgresDao.php');

class PostgresEnderecoDao extends PostgresDao implements EnderecoDao {

    private $table_name = 'endereco';

    public function insere($endereco) {
        $query = "INSERT INTO " . $this->table_name .
        " (rua, numero, complemento, bairro, cep, cidade, estado) VALUES" .
        " (:rua, :numero, :complemento, :bairro, :cep, :cidade, :estado)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':rua', $endereco->getRua());
        $stmt->bindValue(':numero', $endereco->getNumero());
        $stmt->bindValue(':complemento', $endereco->getComplemento());
        $stmt->bindValue(':bairro', $endereco->getBairro());
        $stmt->bindValue(':cep', $endereco->getCep());
        $stmt->bindValue(':cidade', $endereco->getCidade());
        $stmt->bindValue(':estado', $endereco->getEstado());

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

    public function remove($endereco) {
        return $this->removePorId($endereco->getId());
    }

    public function altera(&$endereco) {

        $query = "UPDATE " . $this->table_name .
        " SET rua = :rua,
            numero = :numero,
            complemento = :complemento,
            bairro = :bairro,
            cep = :cep,
            cidade = :cidade,
            estado = :estado
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':rua', $endereco->getRua());
        $stmt->bindValue(':numero', $endereco->getNumero());
        $stmt->bindValue(':complemento', $endereco->getComplemento());
        $stmt->bindValue(':bairro', $endereco->getBairro());
        $stmt->bindValue(':cep', $endereco->getCep());
        $stmt->bindValue(':cidade', $endereco->getCidade());
        $stmt->bindValue(':estado', $endereco->getEstado());
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
                    e.estado
                  FROM endereco e
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

    public function ultimoId() {
        $query = "SELECT currval(pg_get_serial_sequence('endereco', 'id')) as id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'];
    }
}
?>
