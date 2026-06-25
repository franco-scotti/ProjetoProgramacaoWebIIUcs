<?php

include_once('FornecedorDao.php');
include_once('PostgresDao.php');

class PostgresFornecedorDao extends PostgresDao implements FornecedorDao {

    private $table_name = 'fornecedor';

    public function insere($fornecedor) {

        $endereco_id = null;

        if ($fornecedor->getEndereco() !== null) {
            $endereco_id = $fornecedor->getEndereco()->getId();
        }

        $query = "INSERT INTO " . $this->table_name .
        " (nome, descricao, telefone, email, endereco_id, usuario_id) VALUES" .
        " (:nome, :descricao, :telefone, :email, :endereco_id, :usuario_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":nome", $fornecedor->getNome());
        $stmt->bindValue(":descricao", $fornecedor->getDescricao());
        $stmt->bindValue(":telefone", $fornecedor->getTelefone());
        $stmt->bindValue(":email", $fornecedor->getEmail());
        $stmt->bindValue(":endereco_id", $endereco_id);
        $stmt->bindValue(":usuario_id", $fornecedor->getUsuarioId());

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

        $endereco_id = null;

        if ($fornecedor->getEndereco() !== null) {
            $endereco_id = $fornecedor->getEndereco()->getId();
        }

        $query = "UPDATE " . $this->table_name .
        " SET nome = :nome, descricao = :descricao, telefone = :telefone, email = :email, endereco_id = :endereco_id, usuario_id = :usuario_id" .
        " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(":nome", $fornecedor->getNome());
        $stmt->bindValue(":descricao", $fornecedor->getDescricao());
        $stmt->bindValue(":telefone", $fornecedor->getTelefone());
        $stmt->bindValue(":email", $fornecedor->getEmail());
        $stmt->bindValue(":endereco_id", $endereco_id);
        $stmt->bindValue(":usuario_id", $fornecedor->getUsuarioId());
        $stmt->bindValue(":id", $fornecedor->getId());

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    public function buscaPorId($id) {

        $fornecedor = null;

        $query = "SELECT
                    f.id,
                    f.nome,
                    f.descricao,
                    f.telefone,
                    f.email,
                    f.usuario_id,
                    f.endereco_id,

                    e.rua AS endereco_rua,
                    e.numero AS endereco_numero,
                    e.complemento AS endereco_complemento,
                    e.bairro AS endereco_bairro,
                    e.cep AS endereco_cep,
                    e.cidade AS endereco_cidade,
                    e.estado AS endereco_estado
                FROM
                    " . $this->table_name . " f
                LEFT JOIN endereco e ON e.id = f.endereco_id
                WHERE
                    f.id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $fornecedor = new Fornecedor($row['id'], $row['nome'], $row['descricao'], $row['telefone'], $row['email'], $row['usuario_id']);

            if ($row['usuario_id']) {
                $fornecedor->setUsuarioId($row['usuario_id']);
            }

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

                $fornecedor->setEndereco($endereco);
            }
        }

        return $fornecedor;
    }

    public function buscaPorUsuarioId($usuarioId) {
        $fornecedor = null;

        $query = "SELECT
                    f.id,
                    f.nome,
                    f.descricao,
                    f.telefone,
                    f.email,
                    f.usuario_id,
                    f.endereco_id,

                    e.rua AS endereco_rua,
                    e.numero AS endereco_numero,
                    e.complemento AS endereco_complemento,
                    e.bairro AS endereco_bairro,
                    e.cep AS endereco_cep,
                    e.cidade AS endereco_cidade,
                    e.estado AS endereco_estado
                FROM
                    " . $this->table_name . " f
                LEFT JOIN endereco e ON e.id = f.endereco_id
                WHERE
                    f.usuario_id = ?
                LIMIT
                    1 OFFSET 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $usuarioId);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $fornecedor = new Fornecedor($row['id'], $row['nome'], $row['descricao'], $row['telefone'], $row['email'], $row['usuario_id']);

            if ($row['usuario_id']) {
                $fornecedor->setUsuarioId($row['usuario_id']);
            }

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

                $fornecedor->setEndereco($endereco);
            }
        }

        return $fornecedor;
    }

    public function buscaTodos($limit = null, $offset = null) {

        $fornecedores = array();

        $query = "SELECT
                    f.id,
                    f.nome,
                    f.descricao,
                    f.telefone,
                    f.email,
                    f.usuario_id,
                    f.endereco_id,

                    e.rua AS endereco_rua,
                    e.numero AS endereco_numero,
                    e.complemento AS endereco_complemento,
                    e.bairro AS endereco_bairro,
                    e.cep AS endereco_cep,
                    e.cidade AS endereco_cidade,
                    e.estado AS endereco_estado
                FROM
                    " . $this->table_name . " f
                LEFT JOIN endereco e ON e.id = f.endereco_id
                ORDER BY f.id ASC";

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
            $fornecedor = new Fornecedor($row['id'], $row['nome'], $row['descricao'], $row['telefone'], $row['email'], $row['usuario_id']);

            if ($row['usuario_id']) {
                $fornecedor->setUsuarioId($row['usuario_id']);
            }

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

                $fornecedor->setEndereco($endereco);
            }

            $fornecedores[] = $fornecedor;
        }

        return $fornecedores;
    }

    public function buscaPorCodigoNome($termo) {
        $fornecedores = array();

        $query = "SELECT
                    f.id,
                    f.nome,
                    f.descricao,
                    f.telefone,
                    f.email,
                    f.usuario_id,
                    f.endereco_id,

                    e.rua AS endereco_rua,
                    e.numero AS endereco_numero,
                    e.complemento AS endereco_complemento,
                    e.bairro AS endereco_bairro,
                    e.cep AS endereco_cep,
                    e.cidade AS endereco_cidade,
                    e.estado AS endereco_estado
                FROM " . $this->table_name . " f
                LEFT JOIN endereco e ON e.id = f.endereco_id
                WHERE CAST(f.id AS TEXT) ILIKE :termo
                    OR f.nome ILIKE :termo
                ORDER BY f.id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':termo', '%' . $termo . '%');
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $fornecedor = new Fornecedor(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['telefone'],
                $row['email'],
                $row['usuario_id']
            );

            if ($row['usuario_id']) {
                $fornecedor->setUsuarioId($row['usuario_id']);
            }

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

                $fornecedor->setEndereco($endereco);
            }

            $fornecedores[] = $fornecedor;
        }

        return $fornecedores;
    }

    public function contaTodos() {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }
}
?>
