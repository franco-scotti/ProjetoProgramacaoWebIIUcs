<?php
$page_title = "Cadastro - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/views/layout/layout_header.php";

// Redireciona quem já está logado
if (isset($_SESSION['id_usuario'])) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$tipo = isset($_GET['tipo']) && $_GET['tipo'] === 'fornecedor' ? 'fornecedor' : 'cliente';
$erro = $_GET['erro'] ?? '';
$g    = fn($k) => htmlspecialchars($_GET[$k] ?? '');

$erros = [
    'campos_obrigatorios' => 'Preencha todos os campos obrigatórios.',
    'login_duplicado'     => 'Esse login já está em uso. Escolha outro.',
    'senhas_diferentes'   => 'As senhas não conferem.',
    'erro_insercao'       => 'Não foi possível concluir o cadastro. Tente novamente.',
];
?>

<section>
    <h2>Cadastre-se</h2>

    <!-- Abas Cliente / Fornecedor -->
    <ul class="nav nav-tabs mb-4" style="margin-bottom:20px">
        <li class="<?= $tipo === 'cliente'    ? 'active' : '' ?>">
            <a href="?tipo=cliente">Sou Cliente</a>
        </li>
        <li class="<?= $tipo === 'fornecedor' ? 'active' : '' ?>">
            <a href="?tipo=fornecedor">Sou Fornecedor</a>
        </li>
    </ul>

    <?php if ($erro && isset($erros[$erro])): ?>
        <div class="alert alert-danger"><?= $erros[$erro] ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/app/controllers/cadastro/executa_cadastro.php">
        <input type="hidden" name="tipo" value="<?= $tipo ?>">

        <!-- DADOS DE ACESSO -->
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Dados de acesso</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Login <span class="text-danger">*</span></label>
                    <input type="text" name="login" class="form-control"
                           value="<?= $g('login') ?>" required />
                </div>
                <div class="form-group">
                    <label>Senha <span class="text-danger">*</span></label>
                    <input type="password" name="senha" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Confirmar senha <span class="text-danger">*</span></label>
                    <input type="password" name="senha_confirma" class="form-control" required />
                </div>
            </div>
        </div>

        <!-- DADOS PESSOAIS -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Dados <?= $tipo === 'fornecedor' ? 'do Fornecedor' : 'do Cliente' ?></strong>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Nome completo <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-control"
                           value="<?= $g('nome') ?>" required />
                </div>
                <div class="form-group">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                           value="<?= $g('email') ?>" required />
                </div>
                <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name="telefone" class="form-control"
                           value="<?= $g('telefone') ?>" />
                </div>

                <?php if ($tipo === 'fornecedor'): ?>
                <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"><?= $g('descricao') ?></textarea>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ENDEREÇO -->
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Endereço</strong></div>
            <div class="panel-body row">
                <div class="col-sm-8 form-group">
                    <label>Rua</label>
                    <input type="text" name="rua" class="form-control" value="<?= $g('rua') ?>" />
                </div>
                <div class="col-sm-4 form-group">
                    <label>Número</label>
                    <input type="text" name="numero" class="form-control" value="<?= $g('numero') ?>" />
                </div>
                <div class="col-sm-6 form-group">
                    <label>Complemento</label>
                    <input type="text" name="complemento" class="form-control" value="<?= $g('complemento') ?>" />
                </div>
                <div class="col-sm-6 form-group">
                    <label>Bairro</label>
                    <input type="text" name="bairro" class="form-control" value="<?= $g('bairro') ?>" />
                </div>
                <div class="col-sm-3 form-group">
                    <label>CEP</label>
                    <input type="text" name="cep" class="form-control" value="<?= $g('cep') ?>" />
                </div>
                <div class="col-sm-6 form-group">
                    <label>Cidade</label>
                    <input type="text" name="cidade" class="form-control" value="<?= $g('cidade') ?>" />
                </div>
                <div class="col-sm-3 form-group">
                    <label>Estado</label>
                    <input type="text" name="estado" class="form-control" value="<?= $g('estado') ?>" />
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg">Criar conta</button>
        <a href="<?= BASE_URL ?>/public/login.php" class="btn btn-default btn-lg" style="margin-left:10px">
            Já tenho conta
        </a>
    </form>
</section>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>