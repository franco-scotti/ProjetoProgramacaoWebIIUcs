<?php
include_once "fachada.php";

$id = @$_GET["id"];
$rua = trim((string)@$_GET["rua"]);
$numero = trim((string)@$_GET["numero"]);
$complemento = trim((string)@$_GET["complemento"]);
$bairro = trim((string)@$_GET["bairro"]);
$cep = trim((string)@$_GET["cep"]);
$cidade = trim((string)@$_GET["cidade"]);
$estado = trim((string)@$_GET["estado"]);
$fornecedorId = trim((string)@$_GET["fornecedor_id"]);
$clienteId = trim((string)@$_GET["cliente_id"]);

if (($fornecedorId === "" && $clienteId === "") || ($fornecedorId !== "" && $clienteId !== "")) {
    header("Location: modifica_endereco.php?id=" . $id . "&erro=vinculo_invalido");
    exit;
}

$endereco = new Endereco($id, $rua, $numero, $complemento, $bairro, $cep, $cidade, $estado);

if ($fornecedorId !== "") {
    $fornecedor = $factory->getFornecedorDao()->buscaPorId($fornecedorId);
    if ($fornecedor === null) {
        header("Location: modifica_endereco.php?id=" . $id . "&erro=fornecedor_invalido");
        exit;
    }
    $endereco->setFornecedor(new Fornecedor($fornecedorId, '', '', '', ''));
}

if ($clienteId !== "") {
    $cliente = $factory->getClienteDao()->buscaPorId($clienteId);
    if ($cliente === null) {
        header("Location: modifica_endereco.php?id=" . $id . "&erro=cliente_invalido");
        exit;
    }
    $endereco->setCliente(new Cliente($clienteId, '', '', '', ''));
}

$dao = $factory->getEnderecoDao();
try {
    $dao->altera($endereco);
} catch (PDOException $e) {
    header("Location: modifica_endereco.php?id=" . $id . "&erro=erro_alteracao");
    exit;
}

header("Location: enderecos.php");
exit;
?>
