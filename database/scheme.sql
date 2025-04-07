-- Criação do banco de dados, somente se ainda não existir
CREATE DATABASE IF NOT EXISTS db_realtors;

-- Seleção do banco de dados
USE db_realtors;

-- Criação da tabela realtors, somente se ainda não existir
CREATE TABLE IF NOT EXISTS realtors (
    realtor_id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único do corretor',

    name VARCHAR(100) NOT NULL COMMENT 'Nome completo do corretor',

    cpf CHAR(11) NOT NULL COMMENT 'Cadastro de Pessoa Física do corretor, 11 dígitos numéricos sem pontuação',

    creci VARCHAR(25) NOT NULL COMMENT 'Registro CRECI no formato: UF-número-categoria',

    -- Restrições de unicidade
    UNIQUE KEY unique_cpf (cpf),
    UNIQUE KEY unique_creci (creci)

) COMMENT = 'Tabela de corretores imobiliários com CPF e CRECI únicos';
