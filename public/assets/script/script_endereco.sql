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

-- Adiciona flag de admin em usuário e vínculos de usuário em cliente/fornecedor
ALTER TABLE usuario
ADD COLUMN IF NOT EXISTS admin BOOLEAN DEFAULT FALSE NOT NULL;

ALTER TABLE cliente
ADD COLUMN IF NOT EXISTS usuario_id INTEGER;

ALTER TABLE fornecedor
ADD COLUMN IF NOT EXISTS usuario_id INTEGER;

ALTER TABLE cliente
ADD CONSTRAINT IF NOT EXISTS fk_cliente_usuario
FOREIGN KEY (usuario_id)
REFERENCES usuario(id);

ALTER TABLE fornecedor
ADD CONSTRAINT IF NOT EXISTS fk_fornecedor_usuario
FOREIGN KEY (usuario_id)
REFERENCES usuario(id);
