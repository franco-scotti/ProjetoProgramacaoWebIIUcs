<?php
$page_title = "Demo : Alteracao de Endereco";

include_once "fachada.php";

$id = @$_GET["id"];
$dao = $factory->getEnderecoDao();
$endereco = $dao->buscaPorId($id);
$fornecedorId = $endereco->getFornecedor() ? $endereco->getFornecedor()->getId() : '';
$clienteId = $endereco->getCliente() ? $endereco->getCliente()->getId() : '';

include_once "layout_header.php";
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
} elseif ($erro === "erro_alteracao") {
    echo "<div class='alert alert-danger'>Nao foi possivel alterar o endereco.</div>";
}
?>
<form action="altera_endereco.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr><td>Rua</td><td><input type='text' name='rua' value='<?php echo $endereco->getRua();?>' class='form-control' /></td></tr>
        <tr><td>Numero</td><td><input type='text' name='numero' value='<?php echo $endereco->getNumero();?>' class='form-control' /></td></tr>
        <tr><td>Complemento</td><td><input type='text' name='complemento' value='<?php echo $endereco->getComplemento();?>' class='form-control' /></td></tr>
        <tr><td>Bairro</td><td><input type='text' name='bairro' value='<?php echo $endereco->getBairro();?>' class='form-control' /></td></tr>
        <tr><td>CEP</td><td><input type='text' name='cep' value='<?php echo $endereco->getCep();?>' class='form-control' /></td></tr>
        <tr><td>Cidade</td><td><input type='text' name='cidade' value='<?php echo $endereco->getCidade();?>' class='form-control' /></td></tr>
        <tr><td>Estado</td><td><input type='text' name='estado' value='<?php echo $endereco->getEstado();?>' class='form-control' /></td></tr>
        <tr><td>Fornecedor ID</td><td><input type='text' name='fornecedor_id' value='<?php echo $fornecedorId;?>' class='form-control' /></td></tr>
        <tr><td>Cliente ID</td><td><input type='text' name='cliente_id' value='<?php echo $clienteId;?>' class='form-control' /></td></tr>
        <tr><td></td><td><button type="submit" class="btn btn-primary">Alterar</button> <a href='enderecos.php' class='btn btn-primary left-margin'>Cancela</a></td></tr>
    </table>
    <input type='hidden' name='id' value='<?php echo $endereco->getId();?>'/>
</form>
</section>
<?php include_once "layout_footer.php"; ?>
