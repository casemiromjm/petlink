-- Inserir utilizadores
INSERT INTO users (username, email, name, profile_photo, phone, location) VALUES
('maria123', 'maria@example.com', 'Maria Silva', 'maria.jpg', '912345678', 'Lisboa'),
('joao_pets', 'joao@example.com', 'João Costa', 'joao.png', '913456789', 'Porto'),
('rita_t', 'rita@example.com', 'Rita Teixeira', 'rita.jpeg', '914567890', 'Coimbra');

-- Inserir anúncios
INSERT INTO ads (title, username, description, service_type, price, price_period) VALUES
('Passeios para o seu cão em Lisboa', 'maria123', 'Passeios diários no seu bairro com muito carinho.', 'Passeio', 10, 'Hora'),
('Banho e tosquia profissional para cães', 'joao_pets', 'Experiência com raças grandes e pequenas.', 'Tosquia', 25, 'Dia'),
('Petsitting em casa ou no seu domicílio', 'rita_t', 'Cuidamos do seu animal com todo o amor.', 'Petsitting', 100, 'Semana'),
('Treino básico para cachorros', 'maria123', 'Ensino comandos básicos e boas práticas.', 'Treino', 40, 'Mês');

-- Associar tipos de animais aos anúncios
INSERT INTO ad_animals (ad_id, animal_type) VALUES
(1, 'Cães'),
(2, 'Cães'),
(3, 'Cães'), (3, 'Gatos'), (3, 'Pássaros'), (3, 'Roedores'),
(4, 'Cães');