-- 1. Criação da base de dados com acentuação correta (UTF-8)
CREATE DATABASE IF NOT EXISTS chamada
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE chamada;

-- =========================================
-- TABELA 1: TURMAS
-- =========================================
CREATE TABLE turmas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,
    professor VARCHAR(150) NOT NULL,
    turma VARCHAR(100) NOT NULL,

    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,

    ano_letivo YEAR NOT NULL,

    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

-- =========================================
-- TABELA 2: ALUNOS
-- =========================================
CREATE TABLE alunos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    turma_id INT UNSIGNED NOT NULL,

    nome VARCHAR(150) NOT NULL,
    data_nascimento DATE NULL,

    ativo BOOLEAN NOT NULL DEFAULT TRUE,


    -- Conexão com a tabela de Turmas
    CONSTRAINT fk_alunos_turma
        FOREIGN KEY (turma_id)
        REFERENCES turmas(id)
        ON DELETE CASCADE,

);

-- =========================================
-- TABELA 3: CHAMADAS (Evento da Aula)
-- =========================================
CREATE TABLE chamadas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    turma_id INT UNSIGNED NOT NULL,

    data_chamada DATE NOT NULL,
    observacao VARCHAR(255) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Conexão com a tabela de Turmas
    CONSTRAINT fk_chamadas_turma
        FOREIGN KEY (turma_id)
        REFERENCES turmas(id)
        ON DELETE CASCADE,

    -- Impede duplicar a chamada da mesma turma no mesmo dia
    UNIQUE KEY uk_chamada_turma_data (
        turma_id,
        data_chamada
    )
);

-- =========================================
-- TABELA 4: FREQUÊNCIAS (Presença/Falta)
-- =========================================
CREATE TABLE frequencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    chamada_id BIGINT UNSIGNED NOT NULL,
    aluno_id INT UNSIGNED NOT NULL,

    status ENUM(
        'C',
        'F',
        'J',
        'DSC',
        'FERIAS'
    ) NOT NULL DEFAULT 'C',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Conexão com a Chamada do dia
    CONSTRAINT fk_frequencias_chamada
        FOREIGN KEY (chamada_id)
        REFERENCES chamadas(id)
        ON DELETE CASCADE,

    -- Conexão com o Aluno
    CONSTRAINT fk_frequencias_aluno
        FOREIGN KEY (aluno_id)
        REFERENCES alunos(id)
        ON DELETE CASCADE,

    -- Impede registrar o mesmo aluno duas vezes na mesma chamada
    UNIQUE KEY uk_frequencia (
        chamada_id,
        aluno_id
    )
);