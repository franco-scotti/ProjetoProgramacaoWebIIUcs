-- Atualiza a tabela de pedidos para aceitar os novos estados de situação.
ALTER TABLE pedido
    DROP CONSTRAINT IF EXISTS ck_pedido_situacao;

ALTER TABLE pedido
    ADD CONSTRAINT ck_pedido_situacao
    CHECK (upper(situacao) IN ('NOVO', 'PREPARANDO PARA ENVIO', 'A CAMINHO', 'ENTREGUE', 'CANCELADO'));
