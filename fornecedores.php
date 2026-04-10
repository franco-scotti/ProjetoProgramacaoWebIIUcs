<?php
$page_title = "Demo : Listagem de Fornecedores";

include_once "layout_header.php";
include_once "fachada.php";

echo "<section>";

$dao = $factory->getFornecedorDao();
$fornecedores = $dao->buscaTodos();

if($fornecedores) {
    echo "<table class='table table-hover table-responsive table-bordered'>";
    echo "<tr>";
        echo "<th>Id</th>";
        echo "<th>Nome</th>";
        echo "<th>Descricao</th>";
        echo "<th>Telefone</th>";
        echo "<th>Email</th>";
        echo "<th>Acoes</th>";
    echo "</tr>";

    foreach ($fornecedores as $fornecedor) {
        echo "<tr>";
            echo "<td>{$fornecedor->getId()}</td>";
            echo "<td>{$fornecedor->getNome()}</td>";
            echo "<td>{$fornecedor->getDescricao()}</td>";
            echo "<td>{$fornecedor->getTelefone()}</td>";
            echo "<td>{$fornecedor->getEmail()}</td>";
            echo "<td>";
                echo "<a href='mostra_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-primary left-margin'>";
                    echo "<span class='glyphicon glyphicon-list'></span> Mostra";
                echo "</a>";
                echo "<a href='modifica_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-info left-margin'>";
                    echo "<span class='glyphicon glyphicon-edit'></span> Altera";
                echo "</a>";
                echo "<a href='remove_fornecedor.php?id={$fornecedor->getId()}' class='btn btn-danger left-margin' ";
                    echo "onclick=\"return confirm('Tem certeza que quer excluir?')\">";
                    echo "<span class='glyphicon glyphicon-remove'></span> Exclui";
                echo "</a>";
            echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<a href='novo_fornecedor.php' class='btn btn-primary left-margin'>Novo</a>";

echo "</section>";

include_once "layout_footer.php";
?>
