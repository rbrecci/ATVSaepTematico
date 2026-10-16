-- Diário de Mesa de RPG
-- Estrutura: usuarios (login, sem relacionamento)
--            jogador 1:N personagem 1:N registro_sessao
--
-- Usuário de teste do login: mestre / grimorio
--
-- O banco se chama diario_rpg para não colidir com o saep_db da oficina.

CREATE DATABASE IF NOT EXISTS diario_rpg
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE diario_rpg;

DROP TABLE IF EXISTS registro_sessao;
DROP TABLE IF EXISTS personagem;
DROP TABLE IF EXISTS jogador;
DROP TABLE IF EXISTS usuarios;

-- Login do sistema. A senha é guardada como hash (password_hash do PHP).
CREATE TABLE usuarios (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nome  VARCHAR(50)  NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

-- Tabela 1: o "dono" da história.
CREATE TABLE jogador (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nome     VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    email    VARCHAR(100)
);

-- Tabela 2: cada personagem pertence a um jogador.
-- Excluir o jogador apaga junto os personagens dele.
CREATE TABLE personagem (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    jogador_id INT          NOT NULL,
    nome       VARCHAR(100) NOT NULL,
    raca       VARCHAR(50)  NOT NULL,
    classe     VARCHAR(50)  NOT NULL,
    nivel      INT          NOT NULL DEFAULT 1,
    FOREIGN KEY (jogador_id) REFERENCES jogador(id) ON DELETE CASCADE
);

-- Tabela 3: cada registro de sessão pertence a um personagem.
-- Excluir o personagem apaga junto o diário dele.
CREATE TABLE registro_sessao (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    personagem_id INT          NOT NULL,
    data_sessao   DATE         NOT NULL,
    titulo        VARCHAR(120) NOT NULL,
    resumo        TEXT,
    xp_ganho      INT          NOT NULL DEFAULT 0,
    FOREIGN KEY (personagem_id) REFERENCES personagem(id) ON DELETE CASCADE
);

INSERT INTO usuarios (nome, senha) VALUES
('mestre', '$2y$12$wd1uMh3cT8AoZzrmnE9AEunoNEJvf/5QuzMuEshxcBfzbV1XJTll6');

INSERT INTO jogador (nome, telefone, email) VALUES
('Ana Beatriz Lima',   '(11) 91234-5678', 'ana.lima@exemplo.com'),
('Carlos Eduardo Reis', '(11) 98765-4321', 'carlos.reis@exemplo.com'),
('Marina Souza',        '(11) 95555-1020', 'marina.souza@exemplo.com');

INSERT INTO personagem (jogador_id, nome, raca, classe, nivel) VALUES
(1, 'Kaelen Ventonegro',   'Elfo',       'Mago',      5),
(1, 'Zephyra Cinzaviva',   'Tiefling',   'Bruxa',     3),
(2, 'Brogan Punho-de-Ferro', 'Anão',     'Guerreiro', 6),
(3, 'Lyra Sombra-Lunar',   'Meio-elfa',  'Ladina',    4),
(3, 'Orin Folhaverde',     'Halfling',   'Clérigo',   2);

INSERT INTO registro_sessao (personagem_id, data_sessao, titulo, resumo, xp_ganho) VALUES
(1, '2026-08-14', 'A Biblioteca Esquecida',
    'Kaelen decifrou os selos da porta e encontrou um grimório acorrentado ao altar.', 300),
(1, '2026-09-04', 'O Pacto da Torre',
    'Um mago rival ofereceu uma troca perigosa. Kaelen recusou e perdeu um aliado.', 450),
(2, '2026-08-28', 'Sussurros na Cripta',
    'Zephyra ouviu a voz do patrono dentro da cripta e quase perdeu o controle.', 250),
(3, '2026-08-21', 'A Ponte de Ferro',
    'Brogan segurou a ponte sozinho enquanto o grupo atravessava o desfiladeiro.', 400),
(3, '2026-09-11', 'Forja Abandonada',
    'Na forja em ruínas, Brogan reacendeu a bigorna e reconheceu a marca do pai.', 350),
(4, '2026-09-04', 'O Mercado das Máscaras',
    'Lyra furtou um mapa durante o baile e fugiu pelos telhados da cidade.', 380),
(5, '2026-09-18', 'A Capela Sem Sino',
    'Orin curou os feridos da vila e descobriu que o sino fora roubado de propósito.', 200);
