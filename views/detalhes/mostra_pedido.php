<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/ProjetoProgramacaoWebIIUcs');
}

include_once dirname(__DIR__, 2) . "/app/controllers/login/verifica.php";
include_once dirname(__DIR__, 2) . "/routes/fachada.php";

$id     = (int)($_GET['id'] ?? 0);
$dao    = $factory->getPedidoDao();
$pedido = $dao->buscaPorId($id);

if ($pedido) {
    $page_title = "Pedido #" . $pedido->getNumero();
} else {
    $page_title = "Pedido não encontrado";
}

include_once dirname(__DIR__) . "/layout/layout_header.php";

if (!$pedido) {
    echo "<section><div class='alert alert-danger'>Pedido não encontrado.</div></section>";
    include_once dirname(__DIR__) . "/layout/layout_footer.php";
    exit;
}

$clienteNome = $pedido->getCliente() ? htmlspecialchars($pedido->getCliente()->getNome()) : '—';
$sit         = htmlspecialchars($pedido->getSituacao());
$badge       = ['NOVO' => 'info', 'PREPARANDO PARA ENVIO' => 'warning', 'A CAMINHO' => 'primary', 'ENTREGUE' => 'success', 'CANCELADO' => 'danger'][$sit] ?? 'default';
?>

<section>

<!-- MESTRE -->
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Cabeçalho do Pedido</h3></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6">
                <p><strong>Número:</strong> <?= htmlspecialchars($pedido->getNumero()) ?></p>
                <p><strong>Data do pedido:</strong> <?= htmlspecialchars($pedido->getDataPedido()) ?></p>
                <p><strong>Data de entrega:</strong> <?= htmlspecialchars($pedido->getDataEntrega() ?: '—') ?></p>
            </div>
            <div class="col-sm-6">
                <p><strong>Situação:</strong> <span class="label label-<?= $badge ?>" style="font-size:1em"><?= $sit ?></span></p>
                <p><strong>Cliente:</strong> <?= $clienteNome ?></p>
                <p><strong><span id="total-pedido-label">Total</span>:</strong> <span id="total-pedido" style="font-weight:bold;color:#27ae60">carregando…</span>
                <?php if (($_SESSION['usuario_tipo'] ?? '') === 'fornecedor'): ?>
                    <small class="text-muted" style="display:block;margin-top:2px">
                        * Exibindo apenas os produtos do seu fornecimento neste pedido.
                    </small>
                <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- CARROSSEL DE FOTOS -->
<div id="carrossel-box" style="display:none;margin-bottom:24px">
    <h4>Fotos dos itens</h4>
    <div id="carrossel" style="position:relative;overflow:hidden;width:100%;background:#f5f5f5;border-radius:6px;padding:8px">
        <div id="carrossel-slides" style="display:flex;transition:transform .35s ease;width:100%"></div>
        <div style="text-align:center;margin-top:8px">
            <button id="prev-slide" class="btn btn-default btn-sm">&#8592; Anterior</button>
            <span id="slide-counter" style="margin:0 10px"></span>
            <button id="next-slide" class="btn btn-default btn-sm">Próximo &#8594;</button>
        </div>
    </div>
</div>

<!-- DETALHE (itens via AJAX) -->
<h4>Itens do Pedido</h4>
<div id="itens-loading" style="color:#888">Carregando itens…</div>

<table class="table table-bordered" id="itens-table" style="display:none">
    <thead>
        <tr>
            <th style="width:80px">Foto</th>
            <th>Produto</th>
            <th>Descrição</th>
            <th>Qtd</th>
            <th>Preço unit.</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody id="itens-body"></tbody>
</table>

<div id="itens-paginacao" style="margin-top:8px"></div>

<div style="margin-top:20px">
    <?php $tipo = $_SESSION['usuario_tipo'] ?? ''; ?>
    <a href="<?= BASE_URL ?>/views/listagem/lista_pedidos.php" class="btn btn-default">Voltar</a>
    <?php if ($tipo === 'admin'): ?>
        <a href="<?= BASE_URL ?>/views/cadastro/form_pedido.php?id=<?= $pedido->getId() ?>" class="btn btn-info left-margin">Alterar pedido</a>
    <?php endif; ?>
</div>

</section>

<script>
var PEDIDO_ID  = <?= $pedido->getId() ?>;
var BASE_URL   = '<?= BASE_URL ?>';
var paginaAtual = 1;
var totalPaginas = 1;
var slideAtual  = 0;
var todasFotos  = [];

function carregaItens(pagina) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', BASE_URL + '/app/controllers/api/pedido_itens.php?pedido_id=' + PEDIDO_ID
             + '&pagina=' + pagina + '&limit=5', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4 || xhr.status !== 200) return;
        var d = JSON.parse(xhr.responseText);
        paginaAtual  = d.pagina_atual;
        totalPaginas = d.total_paginas;

        // Total e rótulo
        document.getElementById('total-pedido').textContent = d.total_fmt;
        if (d.total_label) {
            document.getElementById('total-pedido-label').textContent = d.total_label;
        }

        // Carrossel (só na primeira carga)
        if (pagina === 1 && d.fotos && d.fotos.length > 0) {
            todasFotos = d.fotos;
            renderCarrossel();
        }

        // Linhas da tabela
        var tbody = document.getElementById('itens-body');
        tbody.innerHTML = '';
        d.itens.forEach(function (item) {
            var img = item.foto
                ? '<img src="' + item.foto + '" style="max-width:70px;max-height:70px;object-fit:cover;border-radius:4px">'
                : '<span style="color:#bbb">Sem foto</span>';
            tbody.innerHTML +=
                '<tr>' +
                '<td>' + img + '</td>' +
                '<td>' + escHtml(item.produto_nome) + '</td>' +
                '<td>' + escHtml(item.produto_descricao) + '</td>' +
                '<td>' + item.quantidade + '</td>' +
                '<td>' + item.preco_fmt + '</td>' +
                '<td>' + item.subtotal_fmt + '</td>' +
                '</tr>';
        });

        document.getElementById('itens-loading').style.display = 'none';
        document.getElementById('itens-table').style.display   = '';

        // Paginação
        var pag = document.getElementById('itens-paginacao');
        pag.innerHTML = '';
        if (totalPaginas > 1) {
            pag.innerHTML = '<p>Página ' + paginaAtual + ' de ' + totalPaginas + '</p>';
            if (paginaAtual > 1) {
                var btnAnt = document.createElement('button');
                btnAnt.className = 'btn btn-default';
                btnAnt.textContent = '← Anterior';
                btnAnt.onclick = function () { carregaItens(paginaAtual - 1); };
                pag.appendChild(btnAnt);
            }
            if (paginaAtual < totalPaginas) {
                var btnProx = document.createElement('button');
                btnProx.className = 'btn btn-default left-margin';
                btnProx.textContent = 'Próxima →';
                btnProx.onclick = function () { carregaItens(paginaAtual + 1); };
                pag.appendChild(btnProx);
            }
        }
    };
    xhr.send();
}

function renderCarrossel() {
    var box    = document.getElementById('carrossel-box');
    var slides = document.getElementById('carrossel-slides');
    slides.innerHTML = '';
    todasFotos.forEach(function (f) {
        var div = document.createElement('div');
        div.style.cssText = 'min-width:100%;text-align:center;padding:4px';
        div.innerHTML = '<img src="' + f.src + '" alt="' + escHtml(f.nome)
            + '" style="max-height:260px;max-width:100%;object-fit:contain;border-radius:6px">'
            + '<p style="margin-top:6px;font-weight:600">' + escHtml(f.nome) + '</p>';
        slides.appendChild(div);
    });
    atualizaSlide();
    box.style.display = '';
}

function atualizaSlide() {
    document.getElementById('carrossel-slides').style.transform =
        'translateX(-' + (slideAtual * 100) + '%)';
    document.getElementById('slide-counter').textContent =
        (slideAtual + 1) + ' / ' + todasFotos.length;
}

document.getElementById('prev-slide').addEventListener('click', function () {
    slideAtual = (slideAtual - 1 + todasFotos.length) % todasFotos.length;
    atualizaSlide();
});
document.getElementById('next-slide').addEventListener('click', function () {
    slideAtual = (slideAtual + 1) % todasFotos.length;
    atualizaSlide();
});

function escHtml(s) {
    if (!s) return '';
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Inicia
carregaItens(1);
</script>

<?php include_once dirname(__DIR__) . "/layout/layout_footer.php"; ?>
