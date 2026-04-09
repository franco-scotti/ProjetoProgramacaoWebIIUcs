drop table if exists item_pedido cascade;
drop table if exists estoque cascade;
drop table if exists pedido cascade;
drop table if exists produto cascade;
drop table if exists endereco cascade;
drop table if exists cliente cascade;
drop table if exists fornecedor cascade;
drop table if exists usuario cascade;

create table usuario (
    id serial not null,
    login varchar(30) not null unique,
    senha varchar(255) not null,
    nome varchar(255) not null,
    constraint pk_usuario primary key (id)
);

insert into usuario(login, senha, nome) values ('krohn','123','Alexandre Krohn');
insert into usuario(login, senha, nome) values ('alexandre','123','Alexandre K.');

create table fornecedor (
    id serial not null,
    nome varchar(100) not null,
    descricao varchar(255),
    telefone varchar(30),
    email varchar(150),
    constraint pk_fornecedor primary key (id)
);

create table cliente (
    id serial not null,
    nome varchar(100) not null,
    telefone varchar(30),
    email varchar(150),
    cartao_credito varchar(30),
    constraint pk_cliente primary key (id)
);

create table endereco (
    id serial not null,
    rua varchar(150) not null,
    numero varchar(20),
    complemento varchar(100),
    bairro varchar(100),
    cep varchar(20),
    cidade varchar(100),
    estado varchar(100),
    fornecedor_id integer unique,
    cliente_id integer unique,
    constraint pk_endereco primary key (id),
    constraint fk_endereco_fornecedor
        foreign key (fornecedor_id) references fornecedor(id)
        on update cascade on delete cascade,
    constraint fk_endereco_cliente
        foreign key (cliente_id) references cliente(id)
        on update cascade on delete cascade,
    constraint ck_endereco_vinculo
        check (
            (fornecedor_id is not null and cliente_id is null)
            or
            (fornecedor_id is null and cliente_id is not null)
        )
);

create table produto (
    id serial not null,
    nome varchar(100) not null,
    descricao varchar(255),
    foto bytea,
    fornecedor_id integer not null,
    constraint pk_produto primary key (id),
    constraint fk_produto_fornecedor
        foreign key (fornecedor_id) references fornecedor(id)
        on update cascade on delete restrict
);

create table estoque (
    id serial not null,
    produto_id integer not null unique,
    quantidade integer not null default 0,
    preco numeric(12,2) not null,
    constraint pk_estoque primary key (id),
    constraint fk_estoque_produto
        foreign key (produto_id) references produto(id)
        on update cascade on delete cascade,
    constraint ck_estoque_quantidade check (quantidade >= 0),
    constraint ck_estoque_preco check (preco >= 0)
);

create table pedido (
    id serial not null,
    numero integer not null unique,
    data_pedido date not null,
    data_entrega date,
    situacao varchar(20) not null,
    cliente_id integer not null,
    constraint pk_pedido primary key (id),
    constraint fk_pedido_cliente
        foreign key (cliente_id) references cliente(id)
        on update cascade on delete restrict,
    constraint ck_pedido_situacao
        check (upper(situacao) in ('NOVO', 'ENTREGUE', 'CANCELADO'))
);

create table item_pedido (
    id serial not null,
    pedido_id integer not null,
    produto_id integer not null,
    quantidade integer not null,
    preco numeric(12,2) not null,
    constraint pk_item_pedido primary key (id),
    constraint uq_item_pedido unique (pedido_id, produto_id),
    constraint fk_item_pedido_pedido
        foreign key (pedido_id) references pedido(id)
        on update cascade on delete cascade,
    constraint fk_item_pedido_produto
        foreign key (produto_id) references produto(id)
        on update cascade on delete restrict,
    constraint ck_item_quantidade check (quantidade > 0),
    constraint ck_item_preco check (preco >= 0)
);
