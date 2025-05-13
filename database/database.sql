DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Ads;
DROP TABLE IF EXISTS Ad_animals;
DROP TABLE IF EXISTS Animal_types;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Ad_services;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Reviews;

-- Criar tables

CREATE TABLE Users (
    user_id INTEGER PRIMARY KEY,
    username NVARCHAR(40) UNIQUE NOT NULL,
    email NVARCHAR(60) UNIQUE NOT NULL,
    password_hash NVARCHAR(255) NOT NULL,
    name NVARCHAR(60),
    profile_photo NVARCHAR(255),
    phone NVARCHAR(24),
    district NVARCHAR(40) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Ads (
    ad_id INTEGER PRIMARY KEY,
    username NVARCHAR(40) NOT NULL,
    title NVARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image_path NVARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    price_period NVARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES Users(username) ON DELETE CASCADE
);

CREATE TABLE Animal_types (
    animal_id INTEGER PRIMARY KEY,
    animal_name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE Ad_animals (
    ad_id INT,
    animal_id INT,
    PRIMARY KEY (ad_id, animal_id),
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES Animal_types(animal_id)
);

CREATE TABLE Services (
    service_id  INTEGER PRIMARY KEY,
    service_name NVARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

CREATE TABLE Ad_services (
    ad_id INT,
    service_id INT,
    PRIMARY KEY (ad_id, service_id),
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

CREATE TABLE Orders (
    order_id INTEGER PRIMARY KEY,
    client_username NVARCHAR(40) NOT NULL,
    ad_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10, 2) NOT NULL,
    isPaid BOOLEAN DEFAULT FALSE,
    isCompleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (client_username) REFERENCES Users(username) ON DELETE CASCADE,
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE
);

CREATE TABLE Reviews (
    review_id INTEGER PRIMARY KEY,
    order_id INT NOT NULL,
    freelancer_username NVARCHAR(40) NOT NULL,
    client_username NVARCHAR(40) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_username) REFERENCES Users(username) ON DELETE CASCADE,
    FOREIGN KEY (client_username) REFERENCES Users(username) ON DELETE CASCADE
);

-- Inserir animais
INSERT INTO Animal_types (animal_name) VALUES ('Cães');
INSERT INTO Animal_types (animal_name) VALUES ('Gatos');
INSERT INTO Animal_types (animal_name) VALUES ('Pássaros');
INSERT INTO Animal_types (animal_name) VALUES ('Roedores');
INSERT INTO Animal_types (animal_name) VALUES ('Répteis');
INSERT INTO Animal_types (animal_name) VALUES ('Peixes');
INSERT INTO Animal_types (animal_name) VALUES ('Furões');
INSERT INTO Animal_types (animal_name) VALUES ('Coelhos');

-- Inserir serviços
INSERT INTO Services (service_name, description) VALUES ('Passeio', 'Serviços de passeio com animais');
INSERT INTO Services (service_name, description) VALUES ('Tosquia', 'Serviços de banho e tosquia');
INSERT INTO Services (service_name, description) VALUES ('Petsitting', 'Cuidados temporários para animais');
INSERT INTO Services (service_name, description) VALUES ('Treino', 'Treino animal');
INSERT INTO Services (service_name, description) VALUES ('Alojamento', 'Hospedagem para animais');
INSERT INTO Services (service_name, description) VALUES ('Veterinário', 'Serviços médicos para animais');
INSERT INTO Services (service_name, description) VALUES ('Transporte', 'Serviços de transporte de animais');

-- Inserir utilizadores
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('maria123', 'maria@example.com', 'Maria Silva', './resources/woman1.png', '912345678', 'Lisboa','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('joao_pets', 'joao@example.com', 'João Costa', 'default_profile.png', '913456789', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('rita_t', 'rita@example.com', 'Rita Teixeira', 'default_profile.png', '914567890', 'Coimbra','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('tomas_vv3', 'tomasthebest@example.com', 'Tomás Ribeiro', 'default_profile.png', '914191674', 'Setúbal','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('anasantos', 'ana.santos@example.com', 'Ana Santos', 'default_profile.png', '961122334', 'Aveiro','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('carlosm', 'carlos.m@example.com', 'Carlos Martins', 'default_profile.png', '923456789', 'Braga','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('sofia_l', 'sofia.l@example.com', 'Sofia Lima', 'default_profile.png', '935678901', 'Faro','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('miguel_p', 'miguel.p@example.com', 'Miguel Pereira', 'default_profile.png', '917890123', 'Guarda','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('margarida_p', 'margarida.p@example.com','Margarida Pacheco' , 'default_profile.png', 'Funchal', '927788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('joanam', 'joana.m@example.com', 'Joana Marta', 'default_profile.png', 'Beja', '939900112','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('helder_c', 'helder.c@example.com', 'Helder Carlo','default_profile.png', 'Portalegre', '916611223','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('fernando_g', 'fernando.g@example.com', 'Fernando Gonçalves', 'default_profile.png', '918899002', 'Bragança','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, phone, password_hash) VALUES ('tiagom', 'tiago.m@example.com', 'Tiago Mendes', 'Santarém', '969876543','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, phone, password_hash) VALUES ('patriciav', 'patricia.v@example.com', 'Patrícia Vicente', 'Setúbal', '921234567','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, phone, password_hash) VALUES ('brunor', 'bruno.r@example.com', 'Bruno Ribeiro', 'Viana do Castelo', '967788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, phone, password_hash) VALUES ('carla_s', 'carla.s@example.com', 'Carla Sousa', 'Vila Real', '928899001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, phone, password_hash) VALUES ('carlos_r', 'carlo.s@example.com', 'Carlos Ribeiro', 'Vila Real ', '928838001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, phone, password_hash) VALUES ('beatriz_c', 'beatriz.c@example.com', 'Beatriz Castro', 'Aveiro', '936677889','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, district, phone, password_hash) VALUES ('ricardoj', 'ricardo.j@example.com', 'Faro', '933445566','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, district, phone, password_hash) VALUES ('andreia_p', 'andreia.p@example.com', 'Porto', '915566778','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, district, phone, password_hash) VALUES ('jose_s', 'jose.s@example.com', 'Sines', '919900113','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, password_hash) VALUES ('pedror', 'pedro.r@example.com', 'Pedro Rodrigues', 'Évora','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, password_hash) VALUES ('sara_c', 'sara.c@example.com', 'Sara Costa', 'Castelo Branco','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, district, password_hash) VALUES ('manuel_a', 'manuel.a@example.com', 'Manuel Almeida', 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, district, password_hash) VALUES ('luis_g', 'luis.g@example.com', 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, district, password_hash) VALUES ('ines_f', 'ines.f@example.com', 'Viseu','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, district, password_hash) VALUES ('isabel_m', 'isabel.m@example.com', 'Guimarães','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- Inserir anúncios
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Passeios para o seu cão em Lisboa', 'maria123', 'Passeios diários no seu bairro com muito carinho.', 10.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Banho e tosquia profissional para cães', 'joao_pets', 'Experiência com raças grandes e pequenas.', 25.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Petsitting em casa ou no seu domicílio', 'rita_t', 'Cuidamos do seu animal com todo o amor.', 100.00, 'Semana');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Treino básico para cachorros', 'maria123', 'Ensino comandos básicos e boas práticas.', 75.00, 'Mês');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Passeios para o seu cão no Porto', 'joao_pets', 'Passeios experientes para os seus amigos de quatro patas.', 12.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Treino Básico de Obediência no Setúbal', 'tomas_vv3', 'Treino com reforço positivo para cachorros.', 35.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Serviços de Tosquia e Banhos em Aveiro', 'anasantos', 'Serviços completos de tosquia e banhos para cães e gatos.', 30.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Cuidados para Pássaros Durante as Suas Viagens (Braga)', 'carlosm', 'Cuido dos seus amigos de penas.', 8.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Alojamento para Pequenos Animais no Faro', 'sofia_l', 'Alojamento seguro e confortável para pequenos animais.', 10.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Treinador de Cães Experiente na Guarda', 'miguel_p', 'Treino avançado e modificação de comportamento.', 50.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Cuidados de Animais ao Domicílio em Leiria', 'luis_g', 'Alimentação, brincadeira e cuidados básicos para os seus animais em casa.', 20.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Cuidados para Coelhos e Roedores em Viseu', 'carla_s', 'Cuidados especializados para coelhos, porquinhos-da-índia, etc.', 10.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Serviços de Cuidados em Évora', 'pedror', 'Tosquia e cuidados básicos para cavalos.', 45.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Babysitting de Animais em Castelo Branco', 'sara_c', 'Cuido do seu gado enquanto estiver fora.', 60.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Tosquia Canina Móvel em Santarém', 'tiagom', 'Serviços de tosquia convenientes à sua porta.', 40.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Especialista em Tosquia de Gatos no Setúbal', 'patriciav', 'Tosquia suave e completa para gatos.', 35.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Serviços de Babysitting de Gatos em Coimbra', 'rita_t', 'Babysitting de gatos de confiança e com carinho na sua casa.', 15.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Passeios de Cães e Visitas ao Parque (Faro)', 'ricardoj', 'Passeios energéticos e brincadeira em parques locais.', 15.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Babysitting de Animais em Casa no Porto', 'andreia_p', 'Conforto e cuidado para os seus animais no ambiente familiar deles.', 25.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Cuidados para Animais Exóticos no Beja', 'sofia_l', 'Cuidados especializados para répteis, anfíbios, etc.', 20.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Treino Canino - Problemas de Comportamento (Portalegre)', 'helder_c', 'A lidar com latidos, roer e outros problemas.', 60.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Babysitting de Pequenos Animais em Viana do Castelo', 'brunor', 'Cuidado carinhoso para hamsters, gerbos, etc.', 7.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Banhos Profissionais para Cães na Vila Real', 'carla_s', 'Serviços de banho suaves e eficazes.', 20.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Serviços de Táxi para Animais em Bragança', 'fernando_g', 'Transporte seguro para os seus animais.', 15.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Aventuras de Passeio para Cães (Guimarães)', 'isabel_m', 'Passeios divertidos e estimulantes para cães ativos.', 18.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Babysitting de Animais Noturno em Leiria', 'manuel_a', 'Cuidados 24 horas para os seus amados animais.', 40.00, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Aulas de Socialização para Cachorros (Aveiro)', 'beatriz_c', 'Socialização precoce para cachorros bem ajustados.', 70.00, 'Mês');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Alimentação de Gatos e Limpeza de Caixa de Areia (Sines)', 'jose_s', 'Visitas diárias para cuidados essenciais de gatos.', 10.00, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Babysitting de Animais na Ilha (Funchal)', 'margarida_p', 'Cuide dos seus animais enquanto desfruta do seu tempo fora.', 30.00, 'Dia');

-- Associar tipos de animais aos anúncios
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (1, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (2, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 3); -- Pássaros
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 4); -- Roedores
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (4, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (5, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (6, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (7, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (7, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (8, 3); -- Pássaros
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (9, 4); -- Roedores
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (10, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (11, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (11, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (12, 4); -- Roedores
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (12, 8); -- Coelhos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (13, 5); -- Répteis
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (14, 5); -- Répteis
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (14, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (15, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (16, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (17, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (18, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (19, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (20, 5); -- Répteis
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (21, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (22, 4); -- Roedores
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (23, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (24, 7); -- Furões
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (25, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (26, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (27, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (28, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (29, 6); -- Peixes

-- Associar tipos de serviço aos anúncios
INSERT INTO Ad_services(ad_id, service_id) VALUES (1, 1); -- Passeio
INSERT INTO Ad_services(ad_id, service_id) VALUES (2, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (3, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (4, 4); -- Treino
INSERT INTO Ad_services(ad_id, service_id) VALUES (5, 1); -- Passeio
INSERT INTO Ad_services(ad_id, service_id) VALUES (6, 4); -- Treino
INSERT INTO Ad_services(ad_id, service_id) VALUES (7, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (8, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (9, 5); -- Alojamento
INSERT INTO Ad_services(ad_id, service_id) VALUES (10, 4); -- Treino
INSERT INTO Ad_services(ad_id, service_id) VALUES (11, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (12, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (13, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (14, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (15, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (16, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (17, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (18, 1); -- Passeio
INSERT INTO Ad_services(ad_id, service_id) VALUES (19, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (20, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (21, 4); -- Treino
INSERT INTO Ad_services(ad_id, service_id) VALUES (22, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (23, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (24, 7); -- Transporte
INSERT INTO Ad_services(ad_id, service_id) VALUES (25, 1); -- Passeio
INSERT INTO Ad_services(ad_id, service_id) VALUES (26, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (27, 4); -- Treino
INSERT INTO Ad_services(ad_id, service_id) VALUES (28, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (29, 3); -- Petsitting

-- Inserir orders
--INSERT INTO Orders(client_username, ad_id, total_price, isPaid, isCompleted) VALUES
--INSERT INTO Orders(client_username, ad_id, total_price, isPaid) VALUES
--INSERT INTO Orders(client_username, ad_id, total_price) VALUES

-- Inserir reviews
--INSERT INTO Reviews (order_id, freelancer_username, client_username, rating, comment) VALUES
--INSERT INTO Reviews (order_id, freelancer_username, client_username, rating) VALUES
