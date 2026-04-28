<?php
$page_title = "Demo : Insercao de Endereco";
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
?>
<section>
<?php
$erro = @$_GET["erro"];
if ($erro === "vinculo_invalido") {
    echo "<div class='alert alert-danger'>Informe apenas um vinculo: Fornecedor ID ou Cliente ID.</div>";
} elseif ($erro === "fornecedor_invalido") {
    echo "<div class='alert alert-danger'>Fornecedor ID nao encontrado.</div>";
} elseif ($erro === "cliente_invalido") {
    echo "<div class='alert alert-danger'>Cliente ID nao encontrado.</div>";
} elseif ($erro === "erro_insercao") {
    echo "<div class='alert alert-danger'>Nao foi possivel inserir o endereco.</div>";
}
?>
<form action="<?= BASE_URL ?>/app/controllers/insere/insere_endereco.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Rua</td><td><input type='text' name='rua' class='form-control' /></td></tr>
        <tr><td>Numero</td><td><input type='text' name='numero' class='form-control' /></td></tr>
        <tr><td>Complemento</td><td><input type='text' name='complemento' class='form-control' /></td></tr>
        <tr><td>Bairro</td><td><input type='text' name='bairro' class='form-control' /></td></tr>
        <tr><td>CEP</td><td><input type='text' name='cep' class='form-control' /></td></tr>
        <tr><td>Cidade</td><td><input type='text' name='cidade' class='form-control' /></td></tr>
        <tr><td>Estado</td><td><input type='text' name='estado' class='form-control' /></td></tr>
        <tr><td>Fornecedor ID</td><td><input type='text' name='fornecedor_id' class='form-control' /></td></tr>
        <tr><td>Cliente ID</td><td><input type='text' name='cliente_id' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Inserir</button> <a href='<?= BASE_URL ?>/views/listagem/lista_enderecos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
