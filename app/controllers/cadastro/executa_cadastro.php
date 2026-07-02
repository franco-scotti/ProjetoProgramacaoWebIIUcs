<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}
include_once dirname(__DIR__, 3) . "/app/controllers/login/comum.php";
include_once dirname(__DIR__, 3) . "/routes/fachada.php";

if (is_session_started() === FALSE) {
    session_start();
}

if (isset($_SESSION['id_usuario'])) {
    header('Location: ' . BASE_URL . '/public/index.php');
    exit;
}

$tipo        = (isset($_POST['tipo']) && $_POST['tipo'] === 'fornecedor') ? 'fornecedor' : 'cliente';
$login       = trim((string)($_POST['login']         ?? ''));
$senha       = trim((string)($_POST['senha']         ?? ''));
$senhaConf   = trim((string)($_POST['senha_confirma']?? ''));
$nome        = trim((string)($_POST['nome']          ?? ''));
$email       = trim((string)($_POST['email']         ?? ''));
$telefone    = trim((string)($_POST['telefone']      ?? ''));
$descricao   = trim((string)($_POST['descricao']     ?? ''));
$rua         = trim((string)($_POST['rua']           ?? ''));
$numero      = trim((string)($_POST['numero']        ?? ''));
$complemento = trim((string)($_POST['complemento']   ?? ''));
$bairro      = trim((string)($_POST['bairro']        ?? ''));
$cep         = trim((string)($_POST['cep']           ?? ''));
$cidade      = trim((string)($_POST['cidade']        ?? ''));
$estado      = trim((string)($_POST['estado']        ?? ''));

$retorno = BASE_URL . '/public/cadastro.php?tipo=' . $tipo
    . '&login='   . urlencode($login)
    . '&nome='    . urlencode($nome)
    . '&email='   . urlencode($email)
    . '&telefone='. urlencode($telefone)
    . '&descricao='. urlencode($descricao)
    . '&rua='     . urlencode($rua)
    . '&numero='  . urlencode($numero)
    . '&complemento=' . urlencode($complemento)
    . '&bairro='  . urlencode($bairro)
    . '&cep='     . urlencode($cep)
    . '&cidade='  . urlencode($cidade)
    . '&estado='  . urlencode($estado);

if ($login === '' || $senha === '' || $nome === '' || $email === '') {
    header('Location: ' . $retorno . '&erro=campos_obrigatorios');
    exit;
}

if ($senha !== $senhaConf) {
    header('Location: ' . $retorno . '&erro=senhas_diferentes');
    exit;
}

$usuarioDao = $factory->getUsuarioDao();

if ($usuarioDao->buscaPorLogin($login) !== null) {
    header('Location: ' . $retorno . '&erro=login_duplicado');
    exit;
}

$pdo = $factory->getConnection();
try {
    $pdo->beginTransaction();

    $novoUsuario = new Usuario(null, $login, $senha, $nome, false);
    $stmtU = $pdo->prepare(
        "INSERT INTO usuario (login, senha, nome, admin) VALUES (:login, :senha, :nome, :admin) RETURNING id"
    );
    $stmtU->bindValue(':login', $login);
    $stmtU->bindValue(':senha', $senha);
    $stmtU->bindValue(':nome',  $nome);
    $stmtU->bindValue(':admin', false, PDO::PARAM_BOOL);
    $stmtU->execute();
    $rowU     = $stmtU->fetch(PDO::FETCH_ASSOC);
    $usuarioId = (int)$rowU['id'];

    $enderecoId = null;
    if ($rua !== '' || $cidade !== '' || $cep !== '') {
        $stmtE = $pdo->prepare(
            "INSERT INTO endereco (rua, numero, complemento, bairro, cep, cidade, estado)
             VALUES (:rua, :numero, :complemento, :bairro, :cep, :cidade, :estado) RETURNING id"
        );
        $stmtE->bindValue(':rua',         $rua         !== '' ? $rua         : null, PDO::PARAM_STR);
        $stmtE->bindValue(':numero',      $numero      !== '' ? $numero      : null, PDO::PARAM_STR);
        $stmtE->bindValue(':complemento', $complemento !== '' ? $complemento : null, PDO::PARAM_STR);
        $stmtE->bindValue(':bairro',      $bairro      !== '' ? $bairro      : null, PDO::PARAM_STR);
        $stmtE->bindValue(':cep',         $cep         !== '' ? $cep         : null, PDO::PARAM_STR);
        $stmtE->bindValue(':cidade',      $cidade      !== '' ? $cidade      : null, PDO::PARAM_STR);
        $stmtE->bindValue(':estado',      $estado      !== '' ? $estado      : null, PDO::PARAM_STR);
        $stmtE->execute();
        $rowE       = $stmtE->fetch(PDO::FETCH_ASSOC);
        $enderecoId = (int)$rowE['id'];
    }

    $vinculoId = null;
    if ($tipo === 'cliente') {
        $stmtC = $pdo->prepare(
            "INSERT INTO cliente (nome, telefone, email, cartao_credito, endereco_id, usuario_id)
             VALUES (:nome, :telefone, :email, NULL, :endereco_id, :usuario_id) RETURNING id"
        );
        $stmtC->bindValue(':nome',       $nome);
        $stmtC->bindValue(':telefone',   $telefone   !== '' ? $telefone   : null, PDO::PARAM_STR);
        $stmtC->bindValue(':email',      $email      !== '' ? $email      : null, PDO::PARAM_STR);
        $stmtC->bindValue(':endereco_id',$enderecoId,                             PDO::PARAM_INT);
        $stmtC->bindValue(':usuario_id', $usuarioId,                              PDO::PARAM_INT);
        $stmtC->execute();
        $rowC      = $stmtC->fetch(PDO::FETCH_ASSOC);
        $vinculoId = (int)$rowC['id'];
    } else {
        $stmtF = $pdo->prepare(
            "INSERT INTO fornecedor (nome, descricao, telefone, email, endereco_id, usuario_id)
             VALUES (:nome, :descricao, :telefone, :email, :endereco_id, :usuario_id) RETURNING id"
        );
        $stmtF->bindValue(':nome',       $nome);
        $stmtF->bindValue(':descricao',  $descricao  !== '' ? $descricao  : null, PDO::PARAM_STR);
        $stmtF->bindValue(':telefone',   $telefone   !== '' ? $telefone   : null, PDO::PARAM_STR);
        $stmtF->bindValue(':email',      $email      !== '' ? $email      : null, PDO::PARAM_STR);
        $stmtF->bindValue(':endereco_id',$enderecoId,                             PDO::PARAM_INT);
        $stmtF->bindValue(':usuario_id', $usuarioId,                              PDO::PARAM_INT);
        $stmtF->execute();
        $rowF      = $stmtF->fetch(PDO::FETCH_ASSOC);
        $vinculoId = (int)$rowF['id'];
    }

    $pdo->commit();

    $_SESSION['id_usuario']            = $usuarioId;
    $_SESSION['nome_usuario']          = $nome;
    $_SESSION['usuario_tipo']          = $tipo;
    $_SESSION['usuario_cliente_id']    = $tipo === 'cliente'    ? $vinculoId : null;
    $_SESSION['usuario_fornecedor_id'] = $tipo === 'fornecedor' ? $vinculoId : null;

    if ($tipo === 'cliente') {
        header('Location: ' . BASE_URL . '/public/meus_pedidos.php');
    } else {
        header('Location: ' . BASE_URL . '/public/portal_fornecedor.php');
    }
    exit;

} catch (Throwable $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Cadastro error: ' . $ex->getMessage());
    header('Location: ' . $retorno . '&erro=erro_insercao');
    exit;
}