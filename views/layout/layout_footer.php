</main>

    <footer class="store-footer">
        <div class="footer-grid">
            <div>
                <span class="footer-label">UCS Commerce</span>
                <p>Operacao visual renovada para catalogo, atendimento e gestao do e-commerce.</p>
            </div>
            <div>
                <span class="footer-label">Atalhos</span>
                <p>
                    <a href="<?php echo BASE_URL; ?>/public/catalogo.php">Catálogo</a>
                    <?php if (isset($_SESSION["nome_usuario"])) { ?>
                        <a href="<?php echo BASE_URL; ?>/views/listagem/lista_pedidos.php">Pedidos</a>
                        <a href="<?php echo BASE_URL; ?>/views/listagem/lista_clientes.php">Clientes</a>
                    <?php } ?>
                </p>
            </div>
            <div>
                <span class="footer-label">Base</span>
                <p>Projeto academico em PHP com PostgreSQL, Bootstrap e interface voltada a loja virtual.</p>
            </div>
        </div>
    </footer>
</body>
</html>