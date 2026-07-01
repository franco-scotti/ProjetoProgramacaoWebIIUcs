<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

$rua = trim((string)@$_GET["rua"]);
$numero = trim((string)@$_GET["numero"]);
$complemento = trim((string)@$_GET["complemento"]);
$bairro = trim((string)@$_GET["bairro"]);
$cep = trim((string)@$_GET["cep"]);
$cidade = trim((string)@$_GET["cidade"]);
$estado = trim((string)@$_GET["estado"]);
$fornecedorId = trim((string)@$_GET["fornecedor_id"]);
$clienteId = trim((string)@$_GET["cliente_id"]);
$campos = ['rua','numero','complemento','bairro','cep','cidade','estado'];
$dados = ['rua' => $rua, 'numero' => $numero, 'complemento' => $complemento, 'bairro' => $bairro, 'cep' => $cep, 'cidade' => $cidade, 'estado' => $estado];

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_endereco.php?erro=campos_obrigatorios");
    exit;
}

if (($fornecedorId === "" && $clienteId === "") || ($fornecedorId !== "" && $clienteId !== "")) {
    header("Location: " . BASE_URL . "/views/cadastro/novo_endereco.php?erro=vinculo_invalido");
    exit;
}

$endereco = new Endereco(null, $rua, $numero, $complemento, $bairro, $cep, $cidade, $estado);

if ($fornecedorId !== "") {
    $fornecedor = $factory->getFornecedorDao()->buscaPorId($fornecedorId);
    if ($fornecedor === null) {
        header("Location: " . BASE_URL . "/views/cadastro/novo_endereco.php?erro=fornecedor_invalido");
        exit;
    }
    $endereco->setFornecedor(new Fornecedor($fornecedorId, '', '', '', ''));
}

if ($clienteId !== "") {
    $cliente = $factory->getClienteDao()->buscaPorId($clienteId);
    if ($cliente === null) {
        header("Location: " . BASE_URL . "/views/cadastro/novo_endereco.php?erro=cliente_invalido");
        exit;
    }
    $endereco->setCliente(new Cliente($clienteId, '', '', '', ''));
}

$dao = $factory->getEnderecoDao();
try {
    $dao->insere($endereco);
} catch (PDOException $e) {
    header("Location: " . BASE_URL . "/views/cadastro/novo_endereco.php?erro=erro_insercao");
    exit;
}

header("Location: " . BASE_URL . "/views/listagem/lista_enderecos.php");
exit;
?>
