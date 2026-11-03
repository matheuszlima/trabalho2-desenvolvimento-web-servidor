-- OPCIONAL: dados de exemplo para testar o sistema (execute depois do schema.sql)
USE clinica_db;

INSERT INTO pacientes (nome, cpf, telefone, email, data_nascimento, endereco) VALUES
('Maria Souza',  '52998224725', '(42) 99999-1111', 'maria@email.com', '1990-05-20', 'Rua das Flores, 100'),
('Joao Pereira', '11144477735', '(42) 98888-2222', 'joao@email.com',  '1985-11-02', 'Av. Brasil, 250');

INSERT INTO profissionais (nome, especialidade, registro, telefone) VALUES
('Dra. Ana Lima',  'Clinico Geral', 'CRM-PR 12345', '(42) 3222-0001'),
('Dr. Carlos Dias', 'Cardiologia',  'CRM-PR 67890', '(42) 3222-0002');

INSERT INTO consultas (paciente_id, profissional_id, data_hora, status, observacoes) VALUES
(1, 1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'agendada',   'Primeira consulta'),
(2, 2, DATE_ADD(NOW(), INTERVAL 5 DAY), 'confirmada', 'Retorno'),
(1, 2, DATE_SUB(NOW(), INTERVAL 10 DAY), 'realizada', 'Check-up');
