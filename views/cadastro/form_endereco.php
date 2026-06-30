<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/routes/fachada.php";
include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";

$id       = isset($_GET['id']) ? (int)$_GET['id'] : null;
$endereco = null;
$erro     = $_GET['erro'] ?? '';

$enderecoDao   = $factory->getEnderecoDao();
$fornecedorDao = $factory->getFornecedorDao();
$clienteDao    = $factory->getClienteDao();

$fornecedores = $fornecedorDao->buscaTodos();
$clientes     = $clienteDao->buscaTodos();

if ($id) {
    $endereco   = $enderecoDao->buscaPorId($id);
    $page_title = "Demo : Alteração de Endereço";
    $action     = BASE_URL . "/app/controllers/altera/altera_endereco.php";
    $textoBotao = "Alterar";
} else {
    $page_title = "Demo : Inserção de Endereço";
    $action     = BASE_URL . "/app/controllers/insere/insere_endereco.php";
    $textoBotao = "Inserir";
}

$fornecedorAtualId = $endereco && $endereco->getFornecedor() ? $endereco->getFornecedor()->getId() : '';
$clienteAtualId    = $endereco && $endereco->getCliente()    ? $endereco->getCliente()->getId()    : '';

include_once dirname(__DIR__) . "/layout/layout_header.php";

$erros = [
    'vinculo_invalido'   => 'Informe apenas um vínculo: Fornecedor ou Cliente.',
    'fornecedor_invalido'=> 'Fornecedor não encontrado.',
    'cliente_invalido'   => 'Cliente não encontrado.',
    'erro_insercao'      => 'Não foi possível inserir o endereço.',
    'erro_alteracao'     => 'Não foi possível alterar o endereço.',
];
if ($erro && isset($erros[$erro])) {
    echo "<div class='alert alert-danger'>" . $erros[$erro] . "</div>";
}
?>
<section>
<form action="<?= $action ?>" method="get">
    <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td>Rua</td>
            <td><input type='text' name='rua' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getRua()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Número</td>
            <td><input type='text' name='numero' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getNumero()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Complemento</td>
            <td><input type='text' name='complemento' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getComplemento()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Bairro</td>
            <td><input type='text' name='bairro' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getBairro()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>CEP</td>
            <td><input type='text' name='cep' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getCep()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Cidade</td>
            <td><input type='text' name='cidade' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getCidade()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Estado</td>
            <td><input type='text' name='estado' class='form-control'
                       value="<?= $endereco ? htmlspecialchars($endereco->getEstado()) : '' ?>" /></td>
        </tr>
        <tr>
            <td>Fornecedor</td>
            <td>
                <select name="fornecedor_id" class="form-control">
                    <option value="">Selecione um fornecedor</option>
                    <?php foreach ($fornecedores as $f): ?>
                        <option value="<?= $f->getId() ?>"
                            <?= ($f->getId() == $fornecedorAtualId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Cliente</td>
            <td>
                <select name="cliente_id" class="form-control">
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c->getId() ?>"
                            <?= ($c->getId() == $clienteAtualId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <?php if ($endereco): ?>
                    <input type="hidden" name="id" value="<?= $endereco->getId() ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary"><?= $textoBotao ?></button>
                <a href="<?= BASE_URL ?>/views/listagem/lista_enderecos.php" class="btn btn-primary left-margin">Cancela</a>
            </td>
        </tr>
    </table>
</form>
</section>
<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
