PRAGMA foreign_keys=ON;      -- fazendo no .sql, remove comandos desnecessários

-- Criar tables

DROP TABLE IF EXISTS Users;
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

DROP TABLE IF EXISTS Roles;
CREATE TABLE Roles (
    role_id INTEGER PRIMARY KEY,
    role_type TEXT NOT NULL CHECK (role_type IN ('freelancer', 'client', 'admin'))
);

DROP TABLE IF EXISTS User_Role;
CREATE TABLE User_Role (
    role_id INTEGER,
    user_id INTEGER,

    FOREIGN KEY (role_id) REFERENCES Roles(role_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(users_id) ON DELETE CASCADE ON UPDATE CASCADE,

    PRIMARY KEY (role_id, user_id)
);

DROP TABLE IF EXISTS Freelancers;
CREATE TABLE Freelancers (
    freelancer_id INTEGER PRIMARY KEY,
    user_id INTEGER UNIQUE NOT NULL,

    -- infos especificas
    description TEXT,

    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Clients;
CREATE TABLE Clients (
    client_id INTEGER PRIMARY KEY,
    user_id INTEGER UNIQUE NOT NULL,

    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Ads;
CREATE TABLE Ads (
    ad_id INTEGER PRIMARY KEY,
    username NVARCHAR(40) NOT NULL,
    title NVARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image_path NVARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    price_period NVARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (username) REFERENCES Users(username) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Animal_types;
CREATE TABLE Animal_types (
    animal_id INTEGER PRIMARY KEY,
    animal_name VARCHAR(50) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS Ad_animals;
CREATE TABLE Ad_animals (
    ad_id INT,
    animal_id INT,

    PRIMARY KEY (ad_id, animal_id),

    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES Animal_types(animal_id)
);

DROP TABLE IF EXISTS Services;
CREATE TABLE Services (
    service_id  INTEGER PRIMARY KEY,
    service_name NVARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

DROP TABLE IF EXISTS Ad_services;
CREATE TABLE Ad_services (
    ad_id INT,
    service_id INT,

    PRIMARY KEY (ad_id, service_id),

    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id)
);

DROP TABLE IF EXISTS Orders;
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

DROP TABLE IF EXISTS Reviews;
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

DROP TABLE IF EXISTS user_animals;
CREATE TABLE user_animals (
    user_id INT NOT NULL,
    name NVARCHAR(50) NOT NULL,
    age INT NOT NULL,
    species INT NOT NULL,
    animal_picture NVARCHAR(255),

    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (species) REFERENCES Animal_types(animal_id)
);

-- Populating db

-- inserir roles
INSERT INTO Roles (role_type) VALUES ('admin');
INSERT INTO Roles (role_type) VALUES ('client');
INSERT INTO Roles (role_type) VALUES ('freelancer');

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
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('casemiro', 'casemiro@example.com', 'Casemiro', '../resources/man1.png', '912345678', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('francisca', 'francisca@example.com', 'Francisca', '../resources/woman1.png', '912345678', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('maria123', 'maria@example.com', 'Maria Silva', '../resources/woman1.png', '912345678', 'Lisboa','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('joao_pets', 'joao@example.com', 'João Costa', '../resources/man1.png', '913456789', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('rita_t', 'rita@example.com', 'Rita Teixeira', '../resources/woman2.png', '914567890', 'Coimbra','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('tomas_vv3', 'tomasthebest@example.com', 'Tomás Ribeiro', '../resources/man2.png', '914191674', 'Setúbal','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('anasantos', 'ana.santos@example.com', 'Ana Santos', '../resources/woman2.png', 'Aveiro', '961122334','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('carlosm', 'carlos.m@example.com', 'Carlos Martins', '../resources/man3.png', 'Braga' ,'923456789','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('sofia_l', 'sofia.l@example.com', 'Sofia Lima', '../resources/woman1.png', 'Faro','935678901','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('miguel_p', 'miguel.p@example.com', 'Miguel Pereira', '../resources/man1.png', 'Guarda','917890123','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('margarida_p', 'margarida.p@example.com','Margarida Pacheco' , '../resources/woman3.png', 'Funchal', '927788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('joanam', 'joana.m@example.com', 'Joana Marta', '../resources/woman1.png', 'Beja', '939900112','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('helder_c', 'helder.c@example.com', 'Helder Carlo', '../resources/man2.png', 'Portalegre', '916611223','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('fernando_g', 'fernando.g@example.com', 'Fernando Gonçalves', '../resources/man3.png', '918899002', 'Bragança','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('tiagom', 'tiago.m@example.com', 'Tiago Mendes', '../resources/man1.png', 'Santarém', '969876543','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('patriciav', 'patricia.v@example.com', 'Patrícia Vicente', '../resources/woman2.png', 'Setúbal', '921234567','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('brunor', 'bruno.r@example.com', 'Bruno Ribeiro', '../resources/man2.png', 'Viana do Castelo', '967788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('carla_s', 'carla.s@example.com', 'Carla Sousa', '../resources/woman3.png', 'Vila Real', '928899001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('carlos_r', 'carlo.s@example.com', 'Carlos Ribeiro', '../resources/man3.png', 'Vila Real ', '928838001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, phone, password_hash) VALUES ('beatriz_c', 'beatriz.c@example.com', 'Beatriz Castro', '../resources/woman2.png', 'Aveiro', '936677889','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, profile_photo, district, phone, password_hash) VALUES ('ricardoj', 'ricardo.j@example.com', '../resources/man1.png', 'Faro', '933445566','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, profile_photo, district, phone, password_hash) VALUES ('andreia_p', 'andreia.p@example.com', '../resources/woman1.png', 'Porto', '915566778','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, profile_photo, district, phone, password_hash) VALUES ('jose_s', 'jose.s@example.com', '../resources/man2.png', 'Sines', '919900113','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, password_hash) VALUES ('pedror', 'pedro.r@example.com', 'Pedro Rodrigues', '../resources/man3.png', 'Évora','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, password_hash) VALUES ('sara_c', 'sara.c@example.com', 'Sara Costa', '../resources/woman2.png', 'Castelo Branco','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, district, password_hash) VALUES ('manuel_a', 'manuel.a@example.com', 'Manuel Almeida', '../resources/man1.png', 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, profile_photo, district, password_hash) VALUES ('luis_g', 'luis.g@example.com', '../resources/man2.png', 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, profile_photo, district, password_hash) VALUES ('ines_f', 'ines.f@example.com', '../resources/woman3.png', 'Viseu','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, profile_photo, district, password_hash) VALUES ('isabel_m', 'isabel.m@example.com', '../resources/woman1.png', 'Guimarães','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- Inserir freelancers
CREATE TEMP TABLE temp_tab AS SELECT role_id FROM Roles WHERE role_type = 'freelancer';

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'maria123'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'maria123'), 'Olá, sou a Maria e adoro animais. Tenho 10 anos de experiência profissional e seria um prazer tomar conta do seu bichinho.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'joao_pets'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'joao_pets'), 'Sou o João, apaixonado por todo o tipo de animais. Tenho experiência em cuidar de cães, gatos e outros pequenos animais.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'rita_t'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'rita_t'), 'Amo passar tempo com animais e tenho disponibilidade para ajudar com passeios e cuidados básicos.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'tomas_vv3'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'tomas_vv3'), 'Olá! Sou o Tomás, um estudante que adora animais e tem tempo livre para cuidar dos seus.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'anasantos'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'anasantos'), 'Sou a Ana, tenho experiência com animais de estimação e ofereço serviços de babysitting de animais na zona de Aveiro.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'carlosm'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'carlosm'), 'Sou o Carlos, gosto muito de animais e estou disponível para passeios e companhia.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'sofia_l'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'sofia_l'), 'Olá, chamo-me Sofia e adoro cuidar de animais. Tenho experiência com cães e gatos.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'miguel_p'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'miguel_p'), 'Sou o Miguel, um amante de animais pronto para ajudar na sua ausência.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'margarida_p'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'margarida_p'), 'Olá, sou a Margarida e tenho muito carinho por animais. Disponível para cuidar do seu na zona do Funchal.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'joanam'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'joanam'), 'Sou a Joana, uma pessoa responsável e dedicada, pronta para cuidar do seu animal de estimação.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'helder_c'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'helder_c'), 'Sou o Hélder, tenho experiência com diversos tipos de animais e estou disponível em Portalegre.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'fernando_g'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'fernando_g'), 'Chamo-me Fernando e sou um apaixonado por animais. Tenho disponibilidade para cuidar do seu em Bragança.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'tiagom'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'tiagom'), 'Sou o Tiago, gosto muito de animais e estou disponível para ajudar na zona de Santarém.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'patriciav'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'patriciav'), 'Olá, sou a Patrícia e adoro animais. Tenho experiência em cuidar de cães e gatos em Setúbal.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'brunor'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'brunor'), 'Sou o Bruno, um jovem responsável que adora animais e vive em Viana do Castelo.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'carla_s'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'carla_s'), 'Olá, sou a Carla e tenho muito prazer em cuidar de animais na zona de Vila Real.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'carlos_r'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'carlos_r'), 'Sou o Carlos, um amante de animais com disponibilidade em Vila Real.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'beatriz_c'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'beatriz_c'), 'Olá, sou a Beatriz e adoro animais. Tenho experiência e disponibilidade em Aveiro.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'ricardoj'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'ricardoj'), 'Sou o Ricardo, um apaixonado por animais pronto a ajudar no distrito de Faro.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'andreia_p'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'andreia_p'), 'Olá, sou a Andreia e adoro animais. Tenho disponibilidade no Porto para cuidar dos seus.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'jose_s'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'jose_s'), 'Sou o José, gosto muito de animais e estou disponível para ajudar em Sines.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'pedror'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'pedror'), 'Olá, sou o Pedro e tenho experiência com animais em Évora.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'sara_c'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'sara_c'), 'Sou a Sara, uma pessoa carinhosa com animais, disponível em Castelo Branco.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'manuel_a'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'manuel_a'), 'Olá, chamo-me Manuel e gosto muito de animais. Disponível em Leiria.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'luis_g'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'luis_g'), 'Sou o Luís, um amante de animais pronto para ajudar na zona de Leiria.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'ines_f'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'ines_f'), 'Olá, sou a Inês e tenho disponibilidade para cuidar de animais em Viseu.');

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'isabel_m'), (SELECT role_id FROM temp_tab));
INSERT INTO Freelancers (user_id, description) VALUES ((SELECT user_id FROM Users WHERE username = 'isabel_m'), 'Sou a Isabel, gosto muito de animais e estou disponível em Guimarães.');

DROP TABLE temp_tab;

-- Inserir clients
CREATE TEMP TABLE temp_tab AS SELECT role_id FROM Roles WHERE role_type = 'client';

DROP TABLE temp_tab;

-- Inserir admins
CREATE TEMP TABLE temp_tab AS SELECT role_id FROM Roles WHERE role_type = 'admin';

INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'casemiro'), (SELECT role_id FROM temp_tab));
INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'staragarica'), (SELECT role_id FROM temp_tab));
INSERT INTO User_Role (user_id, role_id) VALUES ((SELECT user_id FROM Users WHERE username = 'francisca'), (SELECT role_id FROM temp_tab));

DROP TABLE temp_tab;

-- Inserir anúncios
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Passeios para o seu cão em Lisboa', 'maria123', 'Passeios diários no seu bairro com muito carinho.', 10.00, 'Hora', '../resources/passeio.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Banho e tosquia profissional para cães', 'joao_pets', 'Experiência com raças grandes e pequenas.', 25.00, 'Dia', '../resources/tosquia.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Petsitting em casa ou no seu domicílio', 'rita_t', 'Cuidamos do seu animal com todo o amor.', 100.00, 'Semana', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Treino básico para cachorros', 'maria123', 'Ensino comandos básicos e boas práticas.', 75.00, 'Mês', '../resources/treino.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Passeios para o seu cão no Porto', 'joao_pets', 'Passeios experientes para os seus amigos de quatro patas.', 12.00, 'Hora', '../resources/passeio.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Treino Básico de Obediência no Setúbal', 'tomas_vv3', 'Treino com reforço positivo para cachorros.', 35.00, 'Dia', '../resources/treino.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Serviços de Tosquia e Banhos em Aveiro', 'anasantos', 'Serviços completos de tosquia e banhos para cães e gatos.', 30.00, 'Hora', '../resources/tosquia.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Cuidados para Pássaros Durante as Suas Viagens (Braga)', 'carlosm', 'Cuido dos seus amigos de penas.', 8.00, 'Dia', '../resources/alojamento.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Alojamento para Pequenos Animais no Faro', 'sofia_l', 'Alojamento seguro e confortável para pequenos animais.', 10.00, 'Dia', '../resources/alojamento.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Treinador de Cães Experiente na Guarda', 'miguel_p', 'Treino avançado e modificação de comportamento.', 50.00, 'Hora', '../resources/treino.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Cuidados de Animais ao Domicílio em Leiria', 'luis_g', 'Alimentação, brincadeira e cuidados básicos para os seus animais em casa.', 20.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Cuidados para Coelhos e Roedores em Viseu', 'carla_s', 'Cuidados especializados para coelhos, porquinhos-da-índia, etc.', 10.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Serviços de Cuidados em Évora', 'pedror', 'Tosquia e cuidados básicos para caes.', 45.00, 'Hora', '../resources/tosquia.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Babysitting de Animais em Castelo Branco', 'sara_c', 'Cuido do seu animal enquanto estiver fora.', 60.00, 'Dia', '../resources/alojamento.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Tosquia Canina Móvel em Santarém', 'tiagom', 'Serviços de tosquia convenientes à sua porta.', 40.00, 'Hora', '../resources/tosquia.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Especialista em Tosquia de Gatos no Setúbal', 'patriciav', 'Tosquia suave e completa para gatos.', 35.00, 'Hora', '../resources/tosquia.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Serviços de Babysitting de Gatos em Coimbra', 'rita_t', 'Babysitting de gatos de confiança e com carinho na sua casa.', 15.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Passeios de Cães e Visitas ao Parque (Faro)', 'ricardoj', 'Passeios energéticos e brincadeira em parques locais.', 15.00, 'Hora', '../resources/passeio.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Babysitting de Animais em Casa no Porto', 'andreia_p', 'Conforto e cuidado para os seus animais no ambiente familiar deles.', 25.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Cuidados para Animais Exóticos no Beja', 'sofia_l', 'Cuidados especializados para répteis, anfíbios, etc.', 20.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Treino Canino - Problemas de Comportamento (Portalegre)', 'helder_c', 'A lidar com latidos, roer e outros problemas.', 60.00, 'Hora', '../resources/treino.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Babysitting de Pequenos Animais em Viana do Castelo', 'brunor', 'Cuidado carinhoso para hamsters, gerbos, etc.', 7.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Banhos Profissionais para Cães na Vila Real', 'carla_s', 'Serviços de banho suaves e eficazes.', 20.00, 'Hora', '../resources/tosquia.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Serviços de Táxi para Animais em Bragança', 'fernando_g', 'Transporte seguro para os seus animais.', 15.00, 'Hora', '../resources/transporte.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Aventuras de Passeio para Cães (Guimarães)', 'isabel_m', 'Passeios divertidos e estimulantes para cães ativos.', 18.00, 'Hora','../resources/passeio.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Babysitting de Animais Noturno em Leiria', 'manuel_a', 'Cuidados 24 horas para os seus amados animais.', 40.00, 'Dia', '../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Aulas de Socialização para Cachorros (Aveiro)', 'beatriz_c', 'Socialização precoce para cachorros bem ajustados.', 70.00, 'Mês','../resources/treino.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Alimentação de Gatos e Limpeza de Caixa de Areia (Sines)', 'jose_s', 'Visitas diárias para cuidados essenciais de gatos.', 10.00, 'Hora','../resources/petsitting.png');
INSERT INTO Ads (title, username, description, price, price_period, image_path) VALUES ('Babysitting de Animais na Ilha (Funchal)', 'margarida_p', 'Cuide dos seus animais enquanto desfruta do seu tempo fora.', 30.00, 'Dia', '../resources/alojamento.png');

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

-- Query para obter nome e foto de animais de um utilizador específico
SELECT name, animal_picture FROM user_animals WHERE user_id = 1;
