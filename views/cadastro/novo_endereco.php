<?php
$page_title = "Demo : Insercao de Endereco";
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__) . "/layout/layout_header.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$fornecedorDao = $factory->getFornecedorDao();
$clienteDao = $factory->getClienteDao();

$fornecedores = $fornecedorDao->buscaTodos();
$clientes = $clienteDao->buscaTodos();
?>

<section>
<?php
$erro = @$_GET["erro"];

if ($erro === "vinculo_invalido") {
    echo "<div class='alert alert-danger'>Informe apenas um vínculo: Fornecedor ou Cliente.</div>";
} elseif ($erro === "fornecedor_invalido") {
    echo "<div class='alert alert-danger'>Fornecedor não encontrado.</div>";
} elseif ($erro === "cliente_invalido") {
    echo "<div class='alert alert-danger'>Cliente não encontrado.</div>";
} elseif ($erro === "erro_insercao") {
    echo "<div class='alert alert-danger'>Não foi possível inserir o endereço.</div>";
}
?>

<form action="<?= BASE_URL ?>/app/controllers/insere/insere_endereco.php" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Rua</td>
            <td><input type='text' name='rua' class='form-control' /></td>
        </tr>

        <tr>
            <td>Numero</td>
            <td><input type='text' name='numero' class='form-control' /></td>
        </tr>

        <tr>
            <td>Complemento</td>
            <td><input type='text' name='complemento' class='form-control' /></td>
        </tr>

        <tr>
            <td>Bairro</td>
            <td><input type='text' name='bairro' class='form-control' /></td>
        </tr>

        <tr>
            <td>CEP</td>
            <td><input type='text' name='cep' class='form-control' /></td>
        </tr>

        <tr>
            <td>Cidade</td>
            <td><input type='text' name='cidade' class='form-control' /></td>
        </tr>

        <tr>
            <td>Estado</td>
            <td><input type='text' name='estado' class='form-control' /></td>
        </tr>

        <tr>
            <td>Fornecedor</td>
            <td>
                <select name="fornecedor_id" class="form-control">
                    <option value="">Selecione um fornecedor</option>

                    <?php foreach ($fornecedores as $fornecedor) { ?>
                        <option value="<?= $fornecedor->getId() ?>">
                            <?= $fornecedor->getNome() ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Cliente</td>
            <td>
                <select name="cliente_id" class="form-control">
                    <option value="">Selecione um cliente</option>

                    <?php foreach ($clientes as $cliente) { ?>
                        <option value="<?= $cliente->getId() ?>">
                            <?= $cliente->getNome() ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-primary">Inserir</button>
                <a href='<?= BASE_URL ?>/views/listagem/lista_enderecos.php' class='btn btn-primary left-margin'>Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
