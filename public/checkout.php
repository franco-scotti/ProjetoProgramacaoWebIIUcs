<?php
$page_title = "Checkout - UCS Commerce";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__) . "/routes/fachada.php";
include_once dirname(__DIR__) . "/views/layout/layout_header.php";

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$logado = isset($_SESSION['id_usuario']);
$erro = isset($_GET['error']) ? $_GET['error'] : '';
$erroMsg = '';
if ($erro === '1') $erroMsg = 'Erro ao finalizar o pedido. Tente novamente.';
elseif ($erro === 'login_duplicado') $erroMsg = 'O login informado já está em uso. Escolha outro.';
elseif ($erro === 'campos_obrigatorios') $erroMsg = 'Preencha todos os campos obrigatórios.';

$clientePersistido = null;
$enderecoSalvo = '';
$cartaoSalvo = '';
$usaraDadosSalvos = false;

if ($logado) {
    $clientePersistido = $factory->getClienteDao()->buscaPorUsuarioId((int)$_SESSION['id_usuario']);
    if ($clientePersistido) {
        $enderecoObj = $clientePersistido->getEndereco();
        if ($enderecoObj) {
            $partesEndereco = array();
            if ($enderecoObj->getRua()) $partesEndereco[] = $enderecoObj->getRua();
            if ($enderecoObj->getNumero()) $partesEndereco[] = $enderecoObj->getNumero();
            if ($enderecoObj->getComplemento()) $partesEndereco[] = $enderecoObj->getComplemento();
            if ($enderecoObj->getBairro()) $partesEndereco[] = $enderecoObj->getBairro();
            if ($enderecoObj->getCidade()) $partesEndereco[] = $enderecoObj->getCidade();
            if ($enderecoObj->getEstado()) $partesEndereco[] = $enderecoObj->getEstado();
            if ($enderecoObj->getCep()) $partesEndereco[] = $enderecoObj->getCep();
            $enderecoSalvo = implode(', ', $partesEndereco);
        }

        $cartaoSalvo = trim((string)$clientePersistido->getCartaoCredito());
        $usaraDadosSalvos = ($enderecoSalvo !== '' || $cartaoSalvo !== '');
    }
}
?>
<section>
    <h2>Finalizar Pedido</h2>

    <?php if (!$cart) { ?>
        <p>Seu carrinho está vazio.</p>
        <a href="<?php echo BASE_URL; ?>/public/catalogo.php" class="btn btn-primary">Voltar ao catálogo</a>
    <?php } else { ?>

        <?php if ($erroMsg) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($erroMsg); ?></div>
        <?php } ?>

        <form method="POST" action="<?php echo BASE_URL; ?>/app/controllers/checkout/execute.php">

            <?php if (!$logado) { ?>
            <!-- Dados do Cliente (usuário não logado) -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <strong>⚠ Você não está logado.</strong>
                    Informe seus dados para continuar. Uma conta será criada automaticamente.
                </div>
                <div class="card-body">

                    <h5 class="mb-3">Dados pessoais (Cliente)</h5>

                    <div class="form-group">
                        <label>Nome completo <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control"
                               value="<?php echo htmlspecialchars($_GET['nome'] ?? ''); ?>"
                               required />
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>"
                               required />
                    </div>
                    <div class="form-group">
                        <label>Telefone <span class="text-danger">*</span></label>
                        <input type="text" name="telefone" class="form-control"
                               value="<?php echo htmlspecialchars($_GET['telefone'] ?? ''); ?>" required />
                    </div>

                    <hr>
                    <h5 class="mb-3">Dados de acesso (Usuário)</h5>

                    <div class="form-group">
                        <label>Login <span class="text-danger">*</span></label>
                        <input type="text" name="login" class="form-control"
                               value="<?php echo htmlspecialchars($_GET['login'] ?? ''); ?>"
                               required />
                        <small class="form-text text-muted">Será usado para acessar sua conta futuramente.</small>
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
            <?php } else { ?>
            <!-- Usuário logado: apenas exibe nome -->
            <div class="alert alert-success mb-4">
                ✔ Logado como <strong><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></strong>
            </div>
            <?php } ?>

            <!-- Dados de entrega e pagamento -->
            <div class="card mb-4">
                <div class="card-header"><strong>Entrega e Pagamento</strong></div>
                <div class="card-body">
                    <?php if ($logado && $usaraDadosSalvos) { ?>
                        <div class="alert alert-info mb-3">
                            Usaremos o endereço e o cartão já cadastrados para este cliente.
                        </div>
                    <?php } else { ?>
                        <div class="form-group">
                            <label>Endereço de entrega <span class="text-danger">*</span></label>
                            <textarea name="endereco" class="form-control" required><?php echo htmlspecialchars($_GET['endereco'] ?? ($logado ? $enderecoSalvo : '')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Cartão (demo) <span class="text-danger">*</span></label>
                            <input type="text" name="cartao" class="form-control" placeholder="0000 0000 0000 0000"
                                   value="<?php echo htmlspecialchars($_GET['cartao'] ?? ($logado ? $cartaoSalvo : '')); ?>" required />
                        </div>
                    <?php } ?>
                </div>
            </div>

            <button class="btn btn-success btn-lg" type="submit">Confirmar Pedido</button>
        </form>

    <?php } ?>
</section>

<?php include_once dirname(__DIR__) . "/views/layout/layout_footer.php"; ?>