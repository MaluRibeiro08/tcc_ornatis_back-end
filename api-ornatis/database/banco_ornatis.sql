-- Wed Mar 16 09:14:47 2022


-- -----------------------------------------------------
-- Schema db_ornatis
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS db_ornatis;
USE db_ornatis ;

-- -----------------------------------------------------
-- Table db_ornatis.tbl_pais
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_pais 
(
	id_pais INT NOT NULL AUTO_INCREMENT,
	nome_pais VARCHAR(35) NOT NULL,
	PRIMARY KEY (id_pais),
	UNIQUE INDEX (id_pais)
);

-- -----------------------------------------------------
-- Table db_ornatis.tbl_estado
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_estado 
(
	id_estado INT NOT NULL AUTO_INCREMENT,
	nome_estado VARCHAR(20) NOT NULL,
	sigla_estado VARCHAR(3) NOT NULL,
	id_pais INT NOT NULL,
	PRIMARY KEY (id_estado),
	UNIQUE INDEX (id_estado),
	
    CONSTRAINT FK_Pais_Estado
		FOREIGN KEY (id_pais)
		REFERENCES db_ornatis.tbl_pais (id_pais)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_cidade
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_cidade 
(
	id_cidade INT NOT NULL AUTO_INCREMENT,
	nome_cidade VARCHAR(35) NOT NULL,
	id_estado INT NOT NULL,
	PRIMARY KEY (id_cidade),
	UNIQUE INDEX (id_cidade),
    
	CONSTRAINT FK_Estado_Cidade
		FOREIGN KEY (id_estado)
		REFERENCES db_ornatis.tbl_estado (id_estado)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_empresa
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_empresa 
(
	id_empresa INT NOT NULL AUTO_INCREMENT,
	biografia VARCHAR(150) NULL,
	imagem_perfil TEXT NULL,
	telefone VARCHAR(16) NOT NULL,
	nome_fantasia VARCHAR(45) NOT NULL,
	cnpj VARCHAR(14) NULL,
	nome_usuario_instagram VARCHAR(30) NULL,
	link_facebook TEXT NULL,
	intervalo_tempo_padrao_entre_servicos INT NOT NULL,
	observacoes_pagamento VARCHAR(250) NULL,
	PRIMARY KEY (id_empresa),
	UNIQUE INDEX (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_endereco_salao
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_endereco_salao 
(
	id_endereco_salao INT NOT NULL AUTO_INCREMENT,
	bairro VARCHAR(35) NOT NULL,
	rua VARCHAR(45) NOT NULL,
	numero VARCHAR(5) NOT NULL,
	complemento VARCHAR(100) NULL,
	cep VARCHAR(8) NOT NULL,
	id_cidade INT NOT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_endereco_salao),
	UNIQUE INDEX (id_endereco_salao),
    
	CONSTRAINT FK_Cidade_EnderecoSalao
		FOREIGN KEY (id_cidade)
		REFERENCES db_ornatis.tbl_cidade (id_cidade),
    
	CONSTRAINT FK_Empresa_EnderecoSalao
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_dia_semana
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_dia_semana 
(
	id_dia_semana INT NOT NULL AUTO_INCREMENT,
	dia_da_semana VARCHAR(13) NOT NULL,
	PRIMARY KEY (id_dia_semana),
	UNIQUE INDEX (id_dia_semana)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_forma_pagamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_forma_pagamento 
(
	id_forma_pagamento INT NOT NULL AUTO_INCREMENT,
	forma_pagamento VARCHAR(18) NOT NULL,
	PRIMARY KEY (id_forma_pagamento),
	UNIQUE INDEX (id_forma_pagamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_tipo_atendimento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_tipo_atendimento 
(
	id_tipo_atendimento INT NOT NULL AUTO_INCREMENT,
	tipo_atendimento VARCHAR(20) NOT NULL,
	PRIMARY KEY (id_tipo_atendimento),
	UNIQUE INDEX (id_tipo_atendimento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_imagem_espaco_salao
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_imagem_espaco_salao 
(
	id_imagem_espaco_salao INT NOT NULL AUTO_INCREMENT,
	imagem_salao TEXT NOT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_imagem_espaco_salao),
	UNIQUE INDEX (id_imagem_espaco_salao),
    
	CONSTRAINT FK_Empresa_ImagemEspacoSalao
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_administrador
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_administrador 
(
	id_administrador INT NOT NULL AUTO_INCREMENT,
	cpf VARCHAR(11) NOT NULL,
	data_nascimento DATE NOT NULL,
	nome_adm VARCHAR(100) NOT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_administrador),
	UNIQUE INDEX (id_administrador),
    
	CONSTRAINT FK_Empresa_Administrador
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);

-- -----------------------------------------------------
-- Table db_ornatis.tbl_login_adm
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_login_adm 
(
	id_login_adm INT NOT NULL AUTO_INCREMENT,
	email_adm VARCHAR(256) NOT NULL,
	senha_adm VARCHAR(25) NOT NULL,
	id_administrador INT NOT NULL,
	PRIMARY KEY (id_login_adm),
	UNIQUE INDEX (id_login_adm),
    
	CONSTRAINT FK_Administrador_Login
		FOREIGN KEY (id_administrador)
		REFERENCES db_ornatis.tbl_administrador (id_administrador)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_dia_funcionamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_dia_funcionamento 
(
	id_dia_funcionamento INT NOT NULL AUTO_INCREMENT,
	hora_inicio TIME NOT NULL,
	hora_termino TIME NOT NULL,
	id_dia_semana INT NOT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_dia_funcionamento),
	UNIQUE INDEX (id_dia_funcionamento),
    
	CONSTRAINT FK_DiaSemana_DiaFuncionamento
		FOREIGN KEY (id_dia_semana)
		REFERENCES db_ornatis.tbl_dia_semana (id_dia_semana),
        
	CONSTRAINT FK_Empresa_DiaFuncionamento
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_excessao_funcionamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_excessao_funcionamento 
(
	id_excessao_funcionamento INT NOT NULL AUTO_INCREMENT,
	data_excessao DATE NOT NULL,
	hora_inicio TIME NOT NULL,
	hora_fim_excessao TIME NOT NULL,
	descricao VARCHAR(50) NOT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_excessao_funcionamento),
	UNIQUE INDEX (id_excessao_funcionamento),
	
	CONSTRAINT FK_Empresa_ExcessaoFuncionamento
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_taxa_cancelamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_taxa_cancelamento 
(
	id_taxa_cancelamento INT NOT NULL AUTO_INCREMENT,
	valor_acima_de_100 TINYINT NOT NULL,
	porcentagem_sobre_valor_servico INT NOT NULL,
	horas_tolerancia INT NOT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_taxa_cancelamento),
	UNIQUE INDEX (id_taxa_cancelamento),
	
	CONSTRAINT FK_Empresa_TaxaCancelamento
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_servico
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_servico 
(
	id_servico INT NOT NULL AUTO_INCREMENT,
	nome_servico VARCHAR(50) NOT NULL,
	tempo_duracao INT NOT NULL,
	habilitado TINYINT NOT NULL DEFAULT 1,
	desconto INT NOT NULL DEFAULT 0,
	intervalo INT NULL,
	preco FLOAT NULL,
	imagem_servico TEXT NULL,
	detalhes VARCHAR(100) NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_servico),
	UNIQUE INDEX (id_servico),
    
	CONSTRAINT FK_Empresa_Servico
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_comprimento_cabelo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_comprimento_cabelo 
(
	id_comprimento_cabelo INT NOT NULL AUTO_INCREMENT,
	comprimento VARCHAR(2) NOT NULL,
	PRIMARY KEY (id_comprimento_cabelo),
	UNIQUE INDEX (id_comprimento_cabelo)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_tipo_categoria
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_tipo_categoria 
(
	id_tipo_categoria INT NOT NULL AUTO_INCREMENT,
	nome_tipo_categoria VARCHAR(25) NOT NULL,
	PRIMARY KEY (id_tipo_categoria),
	UNIQUE INDEX (id_tipo_categoria)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_categoria
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_categoria 
(
	id_categoria INT NOT NULL AUTO_INCREMENT,
	nome_categoria VARCHAR(20) NOT NULL,
	id_tipo_categoria INT NOT NULL,
	PRIMARY KEY (id_categoria),
	UNIQUE INDEX (id_categoria),

	CONSTRAINT FK_TipoCategoria_Categoria
		FOREIGN KEY (id_tipo_categoria)
		REFERENCES db_ornatis.tbl_tipo_categoria (id_tipo_categoria)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_servico_categoria
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_servico_categoria 
(
	id_servico_categoria INT NOT NULL AUTO_INCREMENT,
	id_categoria INT NOT NULL,
	id_servico INT NOT NULL,
	PRIMARY KEY (id_servico_categoria),
	UNIQUE INDEX (id_servico_categoria),
    
	CONSTRAINT FK_Servico_ServicoCategoria
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico),
        
	CONSTRAINT FK_Categoria_ServicoCategoria
		FOREIGN KEY (id_categoria)
		REFERENCES db_ornatis.tbl_categoria (id_categoria)	
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_genero
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_genero 
(
	id_genero INT NOT NULL AUTO_INCREMENT,
	genero VARCHAR(25) NOT NULL,
	PRIMARY KEY (id_genero),
	UNIQUE INDEX(id_genero )
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_servico_genero
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_servico_genero 
(
	id_servico_genero INT NOT NULL AUTO_INCREMENT,
	id_servico INT NOT NULL,
	id_genero INT NOT NULL,
	PRIMARY KEY (id_servico_genero),
	UNIQUE INDEX (id_servico_genero),
    
	CONSTRAINT FK_Servico_ServicoGenero
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico),
        
	CONSTRAINT FK_Genero_ServicoGenero
		FOREIGN KEY (id_genero)
		REFERENCES db_ornatis.tbl_genero (id_genero)
    
    )
;


-- -----------------------------------------------------
-- Table db_ornatis.tbl_empresa_forma_pagamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_empresa_forma_pagamento 
(
	id_empresa_forma_pagamento INT NOT NULL AUTO_INCREMENT,
	id_empresa INT NOT NULL,
	id_forma_pagamento INT NOT NULL,
	PRIMARY KEY (id_empresa_forma_pagamento),
	UNIQUE INDEX (id_empresa_forma_pagamento),
	 
	CONSTRAINT FK_Empresa_EmpresaFormaPagamento
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa),
        
	CONSTRAINT FK_FormaPagamento_EmpresaFormaPagamento
		FOREIGN KEY (id_forma_pagamento)
		REFERENCES db_ornatis.tbl_forma_pagamento (id_forma_pagamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_item_portifoliio
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_item_portifoliio 
(
	id_item_portifoliio INT NOT NULL AUTO_INCREMENT,
	imagem_portifolio TEXT NOT NULL,
	legenda_portifolio VARCHAR(100) NULL,
	data_hora DATETIME NOT NULL,
	id_servico INT NOT NULL,
	PRIMARY KEY (id_item_portifoliio),
	UNIQUE INDEX (id_item_portifoliio),
	
	CONSTRAINT FK_Servico_ItemPortifoliio
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_servico_tipo_atendimento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_servico_tipo_atendimento 
(
	id_servico_tipo_atendimento INT NOT NULL AUTO_INCREMENT,
	id_tipo_atendimento INT NOT NULL,
	id_servico INT NOT NULL,
	PRIMARY KEY (id_servico_tipo_atendimento),
	UNIQUE INDEX (id_servico_tipo_atendimento),
	
	CONSTRAINT FK_TipoAtendimento_ServicoTipoAtendimento
		FOREIGN KEY (id_tipo_atendimento)
		REFERENCES db_ornatis.tbl_tipo_atendimento (id_tipo_atendimento),
        
	CONSTRAINT FK_Servico_ServicoTipoAtendimento
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico)
);

-- -----------------------------------------------------
-- Table db_ornatis.tbl_funcionario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_funcionario 
(
	id_funcionario INT NOT NULL AUTO_INCREMENT,
	nome_funcionario VARCHAR(20) NOT NULL,
	foto_perfil TEXT NULL,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_funcionario),
	UNIQUE INDEX (id_funcionario),

	CONSTRAINT FK_Empresa_Funcionario
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_login_funcionario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_login_funcionario 
(
	id_login_funcionario INT NOT NULL AUTO_INCREMENT,
	cod_funcionario VARCHAR(15) NOT NULL,
	senha VARCHAR(20) NOT NULL,
	id_funcionario INT NOT NULL,
	PRIMARY KEY (id_login_funcionario),
	UNIQUE INDEX (id_login_funcionario),

	CONSTRAINT FK_Funcionario_LoginFuncionario
		FOREIGN KEY (id_funcionario)
		REFERENCES db_ornatis.tbl_funcionario (id_funcionario)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_dia_trabalho
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_dia_trabalho 
(
	id_dia_trabalho INT NOT NULL AUTO_INCREMENT,
	hora_inicio TIME NOT NULL,
	hora_termino TIME NOT NULL,
	id_dia_semana INT NOT NULL,
	id_funcionario INT NOT NULL,
	PRIMARY KEY (id_dia_trabalho),
    UNIQUE INDEX (id_dia_trabalho),
	
	CONSTRAINT FK_DiaSemana_DiaTrabalho
		FOREIGN KEY (id_dia_semana)
		REFERENCES db_ornatis.tbl_dia_semana (id_dia_semana),
        
	CONSTRAINT FK_Funcionario_DiaTrabalho
		FOREIGN KEY (id_funcionario)
		REFERENCES db_ornatis.tbl_funcionario (id_funcionario)
);

-- -----------------------------------------------------
-- Table db_ornatis.tbl_servico_funcionario
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_servico_funcionario 
(
	id_servico_funcionario INT NOT NULL AUTO_INCREMENT,
	id_funcionario INT NOT NULL,
	id_servico INT NOT NULL,
	PRIMARY KEY (id_servico_funcionario),
	UNIQUE INDEX (id_servico_funcionario ),
	
	CONSTRAINT FK_Funcionario_ServicoFuncionario
		FOREIGN KEY (id_funcionario)
		REFERENCES db_ornatis.tbl_funcionario (id_funcionario),
        
	CONSTRAINT fk_Servico_ServicoFuncionario
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_cor_cabelo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_cor_cabelo 
(
	id_cor_cabelo INT NOT NULL AUTO_INCREMENT,
	nome_cor VARCHAR(10) NOT NULL,
	cod_hexa_cor VARCHAR(6) NOT NULL,
	PRIMARY KEY (id_cor_cabelo),
	UNIQUE INDEX (id_cor_cabelo)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_consumidor
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_consumidor 
(
	id_consumidor INT NOT NULL AUTO_INCREMENT,
	telefone VARCHAR(16) NOT NULL,
	foto_perfil_consumidor TEXT NULL,
	cpf_consumidor VARCHAR(11) NOT NULL,
	data_nascimento DATE NOT NULL,
	nome_consumidor VARCHAR(100) NOT NULL,
	id_genero INT NOT NULL,
	id_cor_cabelo INT NOT NULL,
	id_comprimento_cabelo INT NOT NULL,
	PRIMARY KEY (id_consumidor),
	UNIQUE INDEX (id_consumidor),
    
	CONSTRAINT FK_Genero_Consumidor
		FOREIGN KEY (id_genero)
		REFERENCES db_ornatis.tbl_genero (id_genero),
  
	CONSTRAINT FK_CorCabelo_Consumidor
		FOREIGN KEY (id_cor_cabelo)
		REFERENCES db_ornatis.tbl_cor_cabelo (id_cor_cabelo),
        
	CONSTRAINT FK_ComprimentoCabelo_Consumidor
		FOREIGN KEY (id_comprimento_cabelo)
		REFERENCES db_ornatis.tbl_comprimento_cabelo (id_comprimento_cabelo)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_endereco_consumidor
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_endereco_consumidor 
(
	id_endereco_consumidor INT NOT NULL AUTO_INCREMENT,
	bairro VARCHAR(35) NOT NULL,
	rua VARCHAR(45) NOT NULL,
	numero VARCHAR(5) NOT NULL,
	cep VARCHAR(8) NOT NULL,
    complemento VARCHAR(100) NULL,
	id_cidade INT NOT NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_endereco_consumidor),
	UNIQUE INDEX (id_endereco_consumidor),
    
	CONSTRAINT FK_Cidade_EnderecoConsumidor
		FOREIGN KEY (id_cidade)
		REFERENCES db_ornatis.tbl_cidade (id_cidade),
        
	CONSTRAINT FK_Consumidor_EnderecoConsumidor
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_login_consumidor
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_login_consumidor 
(
	id_login_consumidor INT NOT NULL AUTO_INCREMENT,
	email_consumidor VARCHAR(256) NOT NULL,
	senha_consumidor VARCHAR(25) NOT NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_login_consumidor),
	UNIQUE INDEX (id_login_consumidor),

	CONSTRAINT FK_Consumidor_LoginConsumidor
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);

-- -----------------------------------------------------
-- Table db_ornatis.tbl_empresas_favoritas
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_empresas_favoritas 
(
	id_empresas_favoritas INT NOT NULL AUTO_INCREMENT,
	id_empresa INT NOT NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_empresas_favoritas),
	UNIQUE INDEX (id_empresas_favoritas),
    
	CONSTRAINT FK_Empresa_EmpresasFavoritas
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa),
        
	CONSTRAINT FK_Consumidor_EmpresasFavoritas
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_agendamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_agendamento 
(
	id_agendamento INT NOT NULL AUTO_INCREMENT,
	data_agendamento DATE NOT NULL,
	hora_inicio TIME NOT NULL,
	hora_fim TIME NOT NULL,
	observacoes VARCHAR(100) NULL,
	confirmado TINYINT NOT NULL DEFAULT 0,
	id_forma_pagamento INT NOT NULL,
	id_tipo_atendimento INT NOT NULL,
	id_funcionario INT NOT NULL,
	id_consumidor INT NOT NULL,
	id_servico INT NOT NULL,
	PRIMARY KEY (id_agendamento),
	UNIQUE INDEX (id_agendamento),
	
	CONSTRAINT FK_FormaPagamento_Agendamento
		FOREIGN KEY (id_forma_pagamento)
		REFERENCES db_ornatis.tbl_forma_pagamento (id_forma_pagamento),
        
	CONSTRAINT FK_TipoAgendamento_Agendamento
		FOREIGN KEY (id_tipo_atendimento)
		REFERENCES db_ornatis.tbl_tipo_atendimento (id_tipo_atendimento),
        
	CONSTRAINT FK_Funcionario_Agendamento
		FOREIGN KEY (id_funcionario)
		REFERENCES db_ornatis.tbl_funcionario (id_funcionario),
        
	CONSTRAINT FK_Consumidor_Agendamento
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor),
        
	CONSTRAINT FK_Servico_Agendamento
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_efetuacao_agendamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_efetuacao_agendamento 
(
	id_efetuacao_agendamento INT NOT NULL AUTO_INCREMENT,
	data_efetuacao_agendamento DATE NOT NULL,
	hora TIME NOT NULL,
	cod_confirmacao VARCHAR(4) NOT NULL,
	desconto INT NOT NULL,
	valor_liquido FLOAT NOT NULL,
	valor_bruto FLOAT NOT NULL,
	confirmado TINYINT NOT NULL DEFAULT 0,
	id_forma_pagamento INT NOT NULL,
	PRIMARY KEY (id_efetuacao_agendamento),
	UNIQUE INDEX (id_efetuacao_agendamento),
	
	CONSTRAINT FK_FormaPagamento_EfetuacaoAgendamento
		FOREIGN KEY (id_forma_pagamento)
		REFERENCES db_ornatis.tbl_forma_pagamento (id_forma_pagamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_feedback_atendimento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_feedback_atendimento 
(
	id_feedback_atendimento INT NOT NULL AUTO_INCREMENT,
	nota INT NOT NULL,
	comentario VARCHAR(150) NULL,
	data_hora_postagem DATETIME NOT NULL,
	denunciado TINYINT NOT NULL,
	imagem TEXT NULL,
	id_empresa INT NOT NULL,
	id_efetuacao_agendamento INT NOT NULL,
	PRIMARY KEY (id_feedback_atendimento),
	UNIQUE INDEX (id_feedback_atendimento),

	CONSTRAINT FK_Empresa_FeedbackAgendamento
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa),
        
	CONSTRAINT FK_EfetuacaoAgendamento_FeedbackAgendamento
		FOREIGN KEY (id_efetuacao_agendamento)
		REFERENCES db_ornatis.tbl_efetuacao_agendamento (id_efetuacao_agendamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_tipo_divida
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_tipo_divida 
(
	id_tipo_divida INT NOT NULL AUTO_INCREMENT,
	tipo_divida VARCHAR(20) NOT NULL,
	PRIMARY KEY (id_tipo_divida),
	UNIQUE INDEX (id_tipo_divida)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_divida
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_divida 
(
	id_divida INT NOT NULL AUTO_INCREMENT,
	valor_divida VARCHAR(45) NOT NULL,
	consumidor_paga_prestador TINYINT NOT NULL,
	data_criacao DATE NOT NULL,
	hora_criacao TIME NOT NULL,
	paga TINYINT NULL DEFAULT 0,
	data_pagamento DATE NULL,
	hora_pagamento TIME NULL,
	id_agendamento INT NOT NULL,
	id_tipo_divida INT NOT NULL,
	PRIMARY KEY (id_divida),
	UNIQUE INDEX (id_divida),
	
	CONSTRAINT FK_Agendamento_Divida
		FOREIGN KEY (id_agendamento)
		REFERENCES db_ornatis.tbl_agendamento (id_agendamento),
        
	CONSTRAINT FK_TipoDivida_Divida
		FOREIGN KEY (id_tipo_divida)
		REFERENCES db_ornatis.tbl_tipo_divida (id_tipo_divida)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_produto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_produto 
(
	id_produto INT NOT NULL AUTO_INCREMENT,
	nome_produto VARCHAR(30) NOT NULL,
	descricao_produto VARCHAR(200) NOT NULL,
	preco FLOAT NOT NULL,
	quantidade_estoque INT NOT NULL,
	volume FLOAT NULL,
	retirada TINYINT NOT NULL,
	entrega TINYINT NOT NULL,
	habilitado TINYINT NOT NULL,
	desconto INT NOT NULL DEFAULT 0,
	id_empresa INT NOT NULL,
	PRIMARY KEY (id_produto),
	UNIQUE INDEX (id_produto),
    
	CONSTRAINT FK_Empresa_Produto
		FOREIGN KEY (id_empresa)
		REFERENCES db_ornatis.tbl_empresa (id_empresa)
);

-- -----------------------------------------------------
-- Table db_ornatis.tbl_foto_produto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_foto_produto 
(
	id_foto_produto INT NOT NULL AUTO_INCREMENT,
	foto_produto TEXT NOT NULL,
	id_produto INT NOT NULL,
	PRIMARY KEY (id_foto_produto),
	UNIQUE INDEX (id_foto_produto),

	CONSTRAINT FK_Produto_FotoProduto
		FOREIGN KEY (id_produto)
		REFERENCES db_ornatis.tbl_produto (id_produto)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_produto_categoria
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_produto_categoria 
(
	id_produto_categoria INT NOT NULL AUTO_INCREMENT,
	id_produto INT NOT NULL,
	id_categoria INT NOT NULL,
	PRIMARY KEY (id_produto_categoria),
	UNIQUE INDEX (id_produto_categoria),
	
	CONSTRAINT FK_Categoria_CategoriaProduto
		FOREIGN KEY (id_categoria)
		REFERENCES db_ornatis.tbl_categoria (id_categoria),
        
	CONSTRAINT FK_Produto_CategoriaProduto
		FOREIGN KEY (id_produto)
		REFERENCES db_ornatis.tbl_produto (id_produto)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_produtos_favoritos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_produtos_favoritos 
(
	id_produtos_favoritos INT NOT NULL AUTO_INCREMENT,
	id_produto INT NOT NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_produtos_favoritos),
	UNIQUE INDEX (id_produtos_favoritos),

	CONSTRAINT FK_Produto_ProdutosFavoritos
		FOREIGN KEY (id_produto)
		REFERENCES db_ornatis.tbl_produto (id_produto),
        
	CONSTRAINT FK_Consumidor_ProdutosFavoritos
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_feedback_produto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_feedback_produto 
(
	id_feedback_produto INT NOT NULL AUTO_INCREMENT,
	nota INT NOT NULL,
	data_hora_postagem DATETIME NOT NULL,
	id_produto INT NOT NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_feedback_produto),
	UNIQUE INDEX (id_feedback_produto),

	CONSTRAINT FK_Produto_FeedbackProduto
    FOREIGN KEY (id_produto)
    REFERENCES db_ornatis.tbl_produto (id_produto),
    
	CONSTRAINT FK_Consumidor_FeedbackProduto
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_carrinho_compras
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_carrinho_produtos
(
	id_carrinho_produtos INT NOT NULL AUTO_INCREMENT,
	fechado TINYINT NOT NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_carrinho_produtos),
	UNIQUE INDEX (id_carrinho_produtos),
	
	CONSTRAINT FK_Consumidor_CarrinhoProdutos
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_carrinho_produto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_itens_carrinho_produto 
(
	id_itens_carrinho_produto INT NOT NULL AUTO_INCREMENT,
	id_carrinho_produtos INT NOT NULL,
	id_produto INT NOT NULL,
	PRIMARY KEY (id_itens_carrinho_produto),
	UNIQUE INDEX (id_itens_carrinho_produto),
	
	CONSTRAINT FK_CarrinhoProdutos_ItensCarrinhoProduto
		FOREIGN KEY (id_carrinho_produtos)
		REFERENCES db_ornatis.tbl_carrinho_produtos (id_carrinho_produtos),
        
	CONSTRAINT FK_Produtos_ItensCarrinhoProduto
		FOREIGN KEY (id_produto)
		REFERENCES db_ornatis.tbl_produto (id_produto)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_compra
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_compra 
(
	id_compra INT NOT NULL AUTO_INCREMENT,
	data_hora_compra DATETIME NOT NULL,
	valor_bruto FLOAT NOT NULL,
	valor_liquido FLOAT NOT NULL,
	id_carrinho_produtos INT NOT NULL,
	id_forma_pagamento INT NOT NULL,
	PRIMARY KEY (id_compra),
	UNIQUE INDEX (id_compra),
	
	CONSTRAINT FK_FormaPagamento_Compra
		FOREIGN KEY (id_forma_pagamento)
		REFERENCES db_ornatis.tbl_forma_pagamento (id_forma_pagamento),
    
	CONSTRAINT FK_CarrinhoProdutos_Compra
		FOREIGN KEY (id_carrinho_produtos)
		REFERENCES db_ornatis.tbl_carrinho_Produtos (id_carrinho_produtos)
    
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_carrinho_servico
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_carrinho_servico 
(
	id_carrinho_servicos INT NOT NULL AUTO_INCREMENT,
	fechado TINYINT NOT NULL,
	data_hora_fechamento DATETIME NULL,
	id_consumidor INT NOT NULL,
	PRIMARY KEY (id_carrinho_servicos),
	UNIQUE INDEX (id_carrinho_servicos),

	CONSTRAINT FK_Consumidor_CarrinhoServicos
		FOREIGN KEY (id_consumidor)
		REFERENCES db_ornatis.tbl_consumidor (id_consumidor)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_itens_carrinho_servico
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_itens_carrinho_servico 
(
	id_itens_carrinho_servico INT NOT NULL AUTO_INCREMENT,
	tbl_servico_id_servico INT NOT NULL,
	tbl_carrinho_servico_id_carrinho_servicos INT NOT NULL,
    PRIMARY KEY (id_itens_carrinho_servico),
	UNIQUE INDEX (id_itens_carrinho_servico),
	
	CONSTRAINT FK_Servico_ItensCarrinhoServico
		FOREIGN KEY (tbl_servico_id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico),
        
	CONSTRAINT FK_CarrinhoServico_ItensCarrinhoServico
		FOREIGN KEY (tbl_carrinho_servico_id_carrinho_servicos)
		REFERENCES db_ornatis.tbl_carrinho_servico (id_carrinho_servicos)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_cancelamento
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_cancelamento 
(
	id_cancelamento INT NOT NULL AUTO_INCREMENT,
	data_cancelamento DATE NOT NULL,
	hora_cancelamento TIME NOT NULL,
	id_agendamento INT NOT NULL,
	PRIMARY KEY (id_cancelamento),
	UNIQUE INDEX (id_cancelamento),
    
	CONSTRAINT FK_Agendamento_Cancelamento
		FOREIGN KEY (id_agendamento)
		REFERENCES db_ornatis.tbl_agendamento (id_agendamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_agendamento_efetivado
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_agendamento_efetivado 
(
	id_agendamento_efetivado INT NOT NULL AUTO_INCREMENT,
	id_efetuacao_agendamento INT NOT NULL,
	id_agendamento INT NOT NULL,
	PRIMARY KEY (id_agendamento_efetivado),
	UNIQUE INDEX (id_agendamento_efetivado),
    
	CONSTRAINT FK_EfetuacaoAgendamento_AgendamentoEfetivado
		FOREIGN KEY (id_efetuacao_agendamento)
		REFERENCES db_ornatis.tbl_efetuacao_agendamento (id_efetuacao_agendamento),
        
	CONSTRAINT FK_Agendamento_AgendamentoEfetivado
		FOREIGN KEY (id_agendamento)
		REFERENCES db_ornatis.tbl_agendamento (id_agendamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_excessao_dia_trabalho
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_excessao_dia_trabalho 
(
	id_excessao_funcionamento INT NOT NULL AUTO_INCREMENT,
	data_excessao DATE NOT NULL,
	hora_inicio_excessao TIME NOT NULL,
	hora_fim_excessao TIME NOT NULL,
	descricao VARCHAR(50) NOT NULL,
	id_funcionario INT NOT NULL,
	PRIMARY KEY (id_excessao_funcionamento),
	UNIQUE INDEX (id_excessao_funcionamento),
	
	CONSTRAINT FK_Funcionario_ExcessaoDiaTrabalho
		FOREIGN KEY (id_funcionario)
		REFERENCES db_ornatis.tbl_funcionario (id_funcionario)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_efetuacao_nao_agendada
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_efetuacao_nao_agendada 
(
	id_efetuacao_nao_agendada INT NOT NULL AUTO_INCREMENT,
	data_efetuacao_nao_agendada DATE NOT NULL,
	hora TIME NOT NULL,
	desconto INT NOT NULL,
	valor_liquido FLOAT NOT NULL,
	valor_bruto FLOAT NOT NULL,
	id_forma_pagamento INT NOT NULL,
	PRIMARY KEY (id_efetuacao_nao_agendada),
	UNIQUE INDEX (id_efetuacao_nao_agendada),

	CONSTRAINT FK_FormaPagamento_EfetuacaoNaoAgendada
		FOREIGN KEY (id_forma_pagamento)
		REFERENCES db_ornatis.tbl_forma_pagamento (id_forma_pagamento)
);


-- -----------------------------------------------------
-- Table db_ornatis.tbl_servico_nao_agendado
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS db_ornatis.tbl_servico_nao_agendado
(
	id_servico_nao_agendado INT NOT NULL AUTO_INCREMENT,
	id_efetuacao_nao_agendada INT NOT NULL,
	id_servico INT NOT NULL,
	id_funcionario INT NOT NULL,
	PRIMARY KEY (id_servico_nao_agendado),
	UNIQUE INDEX (id_servico_nao_agendado),
	
	CONSTRAINT FK_EfetuacaoNaoAgendada_ServicoNaoAgendado
		FOREIGN KEY (id_efetuacao_nao_agendada)
		REFERENCES db_ornatis.tbl_efetuacao_nao_agendada (id_efetuacao_nao_agendada),
        
	CONSTRAINT FK_Servico_ServicoNaoAgendado
		FOREIGN KEY (id_servico)
		REFERENCES db_ornatis.tbl_servico (id_servico),
        
	CONSTRAINT FK_Funcionario_ServicoNaoAgendado
		FOREIGN KEY (id_funcionario)
		REFERENCES db_ornatis.tbl_funcionario (id_funcionario)
    
);
