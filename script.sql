CREATE DATABASE sgd;
USE sgd;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    tipo ENUM('aluno', 'professor'),
    turma VARCHAR(50), -- Nulo se for professor
    codigo_validacao VARCHAR(50) -- Apenas para professores
);

CREATE TABLE termos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria ENUM('portugues', 'matematica'),
    termo VARCHAR(100),
    definicao TEXT,
    imagem_url VARCHAR(255),
    status ENUM('pendente', 'aprovado') DEFAULT 'pendente',
    autor_id INT,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id)
);