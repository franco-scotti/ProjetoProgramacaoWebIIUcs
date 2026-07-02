<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

if (!$cart) {
    header('Location: ' . BASE_URL . '/public/catalogo.php');
    exit;
}

$nome      = trim((string)($_POST['nome']          ?? ''));
$email     = trim((string)($_POST['email']         ?? ''));
$telefone  = trim((string)($_POST['telefone']      ?? ''));
$endereco  = trim((string)($_POST['endereco']      ?? ''));
$cartao    = trim((string)($_POST['cartao']        ?? ''));
$login     = trim((string)($_POST['login']         ?? ''));
$senha     = trim((string)($_POST['senha']         ?? ''));
$senhaConf = trim((string)($_POST['senha_confirma']?? ''));

$logado    = isset($_SESSION['id_usuario']);
$clientePersistido = null;
if ($logado) {
    $clientePersistido = $factory->getClienteDao()->buscaPorUsuarioId((int)$_SESSION['id_usuario']);
    if ($clientePersistido && empty($_SESSION['usuario_cliente_id'])) {
        $_SESSION['usuario_cliente_id'] = $clientePersistido->getId();
    }
}

$enderecoParaPedido = $endereco;
$cartaoParaPedido   = $cartao;
if ($enderecoParaPedido === '' && $clientePersistido) {
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
        $enderecoParaPedido = implode(', ', $partesEndereco);
    }
}
if ($cartaoParaPedido === '' && $clientePersistido) {
    $cartaoParaPedido = trim((string)$clientePersistido->getCartaoCredito());
}

if (!$logado) {
    if ($nome === '' || $email === '' || $login === '' || $senha === '') {
        header('Location: ' . BASE_URL . '/public/checkout.php?error=campos_obrigatorios'
            . '&nome=' . urlencode($nome)
            . '&email=' . urlencode($email)
            . '&telefone=' . urlencode($telefone)
            . '&login=' . urlencode($login));
        exit;
    }

    if ($senha !== $senhaConf) {
        header('Location: ' . BASE_URL . '/public/checkout.php?error=senhas_diferentes'
            . '&nome=' . urlencode($nome)
            . '&email=' . urlencode($email)
            . '&telefone=' . urlencode($telefone)
            . '&login=' . urlencode($login));
        exit;
    }

    $usuarioDao = $factory->getUsuarioDao();

    if ($usuarioDao->buscaPorLogin($login) !== null) {
        header('Location: ' . BASE_URL . '/public/checkout.php?error=login_duplicado'
            . '&nome=' . urlencode($nome)
            . '&email=' . urlencode($email)
            . '&telefone=' . urlencode($telefone)
            . '&login=' . urlencode($login));
        exit;
    }

    $novoUsuario = new Usuario(null, $login, $senha, $nome, false);
    $okUsuario = $usuarioDao->insere($novoUsuario);

    if (!$okUsuario) {
        header('Location: ' . BASE_URL . '/public/checkout.php?error=1');
        exit;
    }

    $usuarioCriado = $usuarioDao->buscaPorLogin($login);

    if (!$usuarioCriado) {
        header('Location: ' . BASE_URL . '/public/checkout.php?error=1');
        exit;
    }

    $_SESSION['id_usuario']           = $usuarioCriado->getId();
    $_SESSION['nome_usuario']         = $usuarioCriado->getNome();
    $_SESSION['usuario_tipo']         = 'cliente';
    $_SESSION['usuario_cliente_id']   = null;
    $_SESSION['usuario_fornecedor_id']= null;
}

$pdo = $factory->getConnection();
try {
    $pdo->beginTransaction();

    $clienteId = null;

    if ($logado && isset($_SESSION['usuario_cliente_id']) && $_SESSION['usuario_cliente_id']) {
        $clienteId = (int)$_SESSION['usuario_cliente_id'];
    } else {
        if ($email !== '') {
            $stmt = $pdo->prepare("SELECT id FROM cliente WHERE email = :email LIMIT 1");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $clienteId = (int)$row['id'];
            }
        }

        if (!$clienteId) {
            $nomeCliente = $nome !== '' ? $nome : 'Cliente';
            $usuarioIdNovo = !$logado ? $_SESSION['id_usuario'] : null;

            $stmt = $pdo->prepare(
                "INSERT INTO cliente (nome, telefone, email, cartao_credito, endereco_id, usuario_id)
                 VALUES (:nome, :telefone, :email, :cartao, NULL, :usuario_id) RETURNING id"
            );
            $stmt->bindValue(':nome',       $nomeCliente);
            $stmt->bindValue(':telefone',   $telefone !== '' ? $telefone : null, PDO::PARAM_STR);
            $stmt->bindValue(':email',      $email    !== '' ? $email    : null, PDO::PARAM_STR);
            $stmt->bindValue(':cartao',     $cartaoParaPedido !== '' ? $cartaoParaPedido : null, PDO::PARAM_STR);
            $stmt->bindValue(':usuario_id', $usuarioIdNovo,                      PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $clienteId = (int)$row['id'];

            $_SESSION['usuario_cliente_id'] = $clienteId;
        } else if (!$logado) {
            $stmt = $pdo->prepare(
                "UPDATE cliente SET usuario_id = :uid WHERE id = :id AND usuario_id IS NULL"
            );
            $stmt->bindValue(':uid', $_SESSION['id_usuario'], PDO::PARAM_INT);
            $stmt->bindValue(':id',  $clienteId,              PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['usuario_cliente_id'] = $clienteId;
        }
    }

    if ($clientePersistido && $clientePersistido->getId() && $clienteId === (int)$clientePersistido->getId() && $cartaoParaPedido !== '') {
        $stmtUpdateCartao = $pdo->prepare("UPDATE cliente SET cartao_credito = :cartao WHERE id = :id");
        $stmtUpdateCartao->bindValue(':cartao', $cartaoParaPedido, PDO::PARAM_STR);
        $stmtUpdateCartao->bindValue(':id', (int)$clientePersistido->getId(), PDO::PARAM_INT);
        $stmtUpdateCartao->execute();
    }

    $numero      = time();
    $data_pedido = date('Y-m-d');
    $situacao    = 'PREPARANDO PARA ENVIO';

    $stmt = $pdo->prepare(
        "INSERT INTO pedido (numero, data_pedido, data_entrega, situacao, cliente_id)
         VALUES (:numero, :data_pedido, NULL, :situacao, :cliente_id) RETURNING id"
    );
    $stmt->bindValue(':numero',      $numero);
    $stmt->bindValue(':data_pedido', $data_pedido);
    $stmt->bindValue(':situacao',    $situacao);
    $stmt->bindValue(':cliente_id',  $clienteId, PDO::PARAM_INT);
    $stmt->execute();
    $row      = $stmt->fetch(PDO::FETCH_ASSOC);
    $pedidoId = (int)$row['id'];

    $insertItem   = $pdo->prepare("INSERT INTO item_pedido (pedido_id, produto_id, quantidade, preco) VALUES (:pedido_id, :produto_id, :quantidade, :preco)");
    $selectEstoque= $pdo->prepare("SELECT id, produto_id, quantidade FROM estoque WHERE produto_id = :produto_id LIMIT 1");
    $updateEstoque= $pdo->prepare("UPDATE estoque SET quantidade = :quantidade WHERE id = :id");

    $total = 0;
    foreach ($cart as $item) {
        $produtoId = (int)$item['id'];
        $quant     = (int)$item['quantidade'];
        $preco     = (float)$item['preco'];
        $subtotal  = $preco * $quant;
        $total    += $subtotal;

        $insertItem->bindValue(':pedido_id',  $pedidoId,  PDO::PARAM_INT);
        $insertItem->bindValue(':produto_id', $produtoId, PDO::PARAM_INT);
        $insertItem->bindValue(':quantidade', $quant,     PDO::PARAM_INT);
        $insertItem->bindValue(':preco',      $preco);
        $insertItem->execute();

        $selectEstoque->bindValue(':produto_id', $produtoId, PDO::PARAM_INT);
        $selectEstoque->execute();
        $e = $selectEstoque->fetch(PDO::FETCH_ASSOC);
        if ($e) {
            $newQuantidade = max(0, (int)$e['quantidade'] - $quant);
            $updateEstoque->bindValue(':quantidade', $newQuantidade, PDO::PARAM_INT);
            $updateEstoque->bindValue(':id',         (int)$e['id'],  PDO::PARAM_INT);
            $updateEstoque->execute();
        }
    }

    $pdo->commit();

    $_SESSION['checkout_summary'] = array(
        'pedido_id' => $pedidoId,
        'nome'      => $nome,
        'email'     => $email,
        'endereco'  => $enderecoParaPedido,
        'total'     => $total,
        'items'     => $cart
    );
    $_SESSION['checkout_cliente_id'] = $clienteId;

    unset($_SESSION['cart']);

    header('Location: ' . BASE_URL . '/public/confirmacao.php?pedido_id=' . $pedidoId);
    exit;

} catch (Throwable $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Checkout error: ' . $ex->getMessage());
    header('Location: ' . BASE_URL . '/public/checkout.php?error=1');
    exit;
}