use db_ornatis;

INSERT INTO tbl_empresa (biografia, telefone, nome_fantasia, cnpj, 
intervalo_tempo_padrao_entre_servicos, observacoes_pagamento)
VALUES ("Serviços diversos de beleza desde 1996", 
"91111-2222",
"Maria cabelos",
"11222111000124",
5,
"Aceitanos cartão, pix e em dinheiro");

select * from tbl_empresa