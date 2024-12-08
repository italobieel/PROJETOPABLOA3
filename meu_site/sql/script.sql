--Servidor: 127.0.0.1 via TCP/IP
--Tipo de servidor: MariaDB
--Conexão do servidor: O SSL não está sendo usado Documentação
--Versão do servidor: 10.4.32-MariaDB - mariadb.org binary distribution
--Versão de protocolo: 10
--Usuário: root@localhost
--Charset do servidor: UTF-8 Unicode (utf8mb4)
--Servidor web
--Apache/2.4.58 (Win64) OpenSSL/3.1.3 PHP/8.2.12
--Versão do cliente de banco de dados: libmysql - mysqlnd 8.2.12
--Extensão do PHP: mysqli Documentação curl Documentação mbstring Documentação
--Versão do PHP: 8.2.12
--phpMyAdmin
--Informações da versão: 5.2.1 (atualizado(a))



CREATE DATABASE meusite;

USE meusite;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Inserir um admin padrão
INSERT INTO usuarios (nome, email, senha, data_cadastro)
VALUES ('Administrador', 'admin@example.com', '<senha_hash>', NOW());