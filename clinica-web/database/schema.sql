-- Sistema de Clinica/Consultorio - criacao do banco e das tabelas
CREATE DATABASE IF NOT EXISTS clinica_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE clinica_db;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  senha_hash VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS pacientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf VARCHAR(14) UNIQUE NOT NULL,
  telefone VARCHAR(20),
  email VARCHAR(100),
  data_nascimento DATE,
  endereco VARCHAR(200)
);

CREATE TABLE IF NOT EXISTS profissionais (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  especialidade VARCHAR(100) NOT NULL,
  registro VARCHAR(50),
  telefone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS consultas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  paciente_id INT NOT NULL,
  profissional_id INT NOT NULL,
  data_hora DATETIME NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'agendada',
  observacoes TEXT,
  FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
  FOREIGN KEY (profissional_id) REFERENCES profissionais(id)
);

-- Usuario inicial para acessar o sistema (E-mail: admin@gmail.com / Senha: 123456)
-- A senha fica gravada como hash (bcrypt), gerado com password_hash()
INSERT INTO usuarios (nome, email, senha_hash)
SELECT 'Administrador', 'admin@gmail.com', '$2y$10$zWEdHwUzT3fyl9hu/jg3P.SNBDlWHwWGW6LxT/sA.whCI0.O8B8Bu'
WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE email = 'admin@gmail.com');
