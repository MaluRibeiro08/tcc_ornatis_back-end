create database db_ornatis;

use db_ornatis;

create table tbl_produto (
	id_produto int not null auto_increment primary key,
    nome_produto varchar(30) not null,
    descricao_produto varchar(200) not null,
    preco_produto float not null,
    qtde_estoque int not null,
    volume float,
    retirada boolean not null,
    entrega boolean not null,
    habilitado boolean not null,
    desconto int not null
);
