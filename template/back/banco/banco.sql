-- =====================================================
-- [BANCO]
-- =====================================================

CREATE DATABASE IF NOT EXISTS meu_projeto
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE meu_projeto;


-- =====================================================
-- [TABELAS]
-- =====================================================


CREATE TABLE IF NOT EXISTS usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    senha      VARCHAR(255)  NOT NULL,
    nivel      ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    ativo      TINYINT(1)    NOT NULL DEFAULT 1,
    criado_em  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- =====================================================
-- [DADOS]
-- =====================================================



INSERT INTO usuarios (nome, email, senha, nivel) VALUES
('Administrador', 'admin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Maria Silva', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'usuario');
-- senha padrao: password