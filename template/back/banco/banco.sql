-- =====================================================
-- [BANCO]
-- =====================================================

CREATE DATABASE IF NOT EXISTS chamada
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE chamada;


-- =====================================================
-- [TABELAS]
-- =====================================================

CREATE TABLE IF NOT EXISTS turmas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    professor VARCHAR(150) NOT NULL,
    turma VARCHAR(100) NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    ano_letivo YEAR NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS alunos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    turma_id INT UNSIGNED NOT NULL,
    nome VARCHAR(150) NOT NULL,
    data_nascimento DATE NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT fk_alunos_turma
        FOREIGN KEY (turma_id)
        REFERENCES turmas(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS chamadas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    turma_id INT UNSIGNED NOT NULL,
    data_chamada DATE NOT NULL,
    observacao VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_chamadas_turma
        FOREIGN KEY (turma_id)
        REFERENCES turmas(id)
        ON DELETE CASCADE,

    UNIQUE KEY uk_chamada_turma_data (
        turma_id,
        data_chamada
    )
);

CREATE TABLE IF NOT EXISTS frequencias (
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

    CONSTRAINT fk_frequencias_chamada
        FOREIGN KEY (chamada_id)
        REFERENCES chamadas(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_frequencias_aluno
        FOREIGN KEY (aluno_id)
        REFERENCES alunos(id)
        ON DELETE CASCADE,

    UNIQUE KEY uk_frequencia (
        chamada_id,
        aluno_id
    )
);


-- =====================================================
-- [DADOS]
-- =====================================================

INSERT INTO turmas
(nome, professor, turma, horario_inicio, horario_fim, ano_letivo)
VALUES
('Matemática', 'João Silva', '1º A', '07:00:00', '08:00:00', 2026),
('Português', 'Maria Oliveira', '1º B', '08:00:00', '09:00:00', 2026),
('História', 'Carlos Santos', '2º A', '09:00:00', '10:00:00', 2026);

INSERT INTO alunos
(turma_id, nome, data_nascimento)
VALUES
(1, 'Ana Souza', '2010-03-15'),
(1, 'Carlos Oliveira', '2010-06-20'),
(1, 'Mariana Santos', '2010-09-12'),
(2, 'João Pereira', '2011-01-10'),
(2, 'Lucas Almeida', '2011-04-22'),
(3, 'Pedro Costa', '2009-11-05');

INSERT INTO chamadas
(turma_id, data_chamada, observacao)
VALUES
(1, '2026-08-10', 'Aula normal'),
(2, '2026-08-10', 'Aula normal'),
(3, '2026-08-10', 'Aula normal');

INSERT INTO frequencias
(chamada_id, aluno_id, status)
VALUES
(1, 1, 'C'),
(1, 2, 'C'),
(1, 3, 'F'),
(2, 4, 'C'),
(2, 5, 'J'),
(3, 6, 'C');