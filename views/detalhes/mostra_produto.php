<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id = @$_GET["id"];
$dao = $factory->getProdutoDao();
$produto = $dao->buscaPorId($id);

if($produto) {
    $page_title = "Demo : Exibindo Produto : " . $produto->getNome();
} else {
    $page_title = "Demo : Produto nao encontrado!";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if($produto) {

    $fornecedorNome = $produto->getFornecedor() 
        ? $produto->getFornecedor()->getNome() 
        : '';

    echo "<section>";
    echo "<h1> Nome : " . $produto->getNome() . "</h1>";
    echo "<p>Id : " . $produto->getId() . "</p>";
    echo "<p>Descricao : " . $produto->getDescricao() . "</p>";
    echo "<p>Fornecedor: " . $fornecedorNome . "</p>";
    $foto = $produto->getFoto();
    if ($foto) {
        if (strpos($foto, 'data:image') === 0) {
            $srcFoto = $foto;
        } else {
            $srcFoto = 'data:image/jpeg;base64,' . $foto;
        }
        echo "<img src='" . $srcFoto . "' 
                style='max-width:300px; border:1px solid #ccc; padding:5px;'>";
    }
    echo "<br><br>";
    echo "<a href='" . BASE_URL . "/views/listagem/lista_produtos.php' 
            class='btn btn-primary left-margin'>
            Voltar
          </a>";
    echo "</section>";
}

include_once dirname(__DIR__) . "/layout/layout_footer.php";
?>
