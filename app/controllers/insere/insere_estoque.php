<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/valida_campos.php";

$produtoId = trim((string)@$_GET["produto_id"]);
$quantidade = trim((string)@$_GET["quantidade"]);
$preco = trim((string)@$_GET["preco"]);
$campos = ['produto_id','quantidade','preco'];
$dados = ['produto_id' => $produtoId, 'quantidade' => $quantidade, 'preco' => $preco];

if (!empty(camposObrigatorios($campos, $dados))) {
    header("Location: " . BASE_URL . "/views/cadastro/form_estoque.php?erro=campos_obrigatorios");
    exit;
}

$estoque = new Estoque(null, $quantidade, $preco);
if ($produtoId !== "") {
    $estoque->setProduto(new Produto($produtoId, '', '', null));
}

$dao = $factory->getEstoqueDao();
$dao->insere($estoque);

header("Location: " . BASE_URL . "/views/listagem/lista_estoques.php");
exit;
?>
