-- Adiciona o vínculo do endereço no cliente e fornecedor
ALTER TABLE cliente
ADD COLUMN endereco_id INTEGER;

ALTER TABLE fornecedor
ADD COLUMN endereco_id INTEGER;

-- Cria as chaves estrangeiras
ALTER TABLE cliente
ADD CONSTRAINT fk_cliente_endereco
FOREIGN KEY (endereco_id)
REFERENCES endereco(id);

ALTER TABLE fornecedor
ADD CONSTRAINT fk_fornecedor_endereco
FOREIGN KEY (endereco_id)
REFERENCES endereco(id);

-- Remove os vínculos antigos da tabela endereço
ALTER TABLE endereco
DROP COLUMN IF EXISTS cliente_id;

ALTER TABLE endereco
DROP COLUMN IF EXISTS fornecedor_id;