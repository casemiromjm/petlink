PRAGMA foreign_keys=ON;      -- fazendo no .sql, remove comandos desnecessários

-- Drop Tables
DROP TABLE IF EXISTS Messages;
DROP TABLE IF EXISTS User_animals;
DROP TABLE IF EXISTS Reviews;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Ad_animals;
DROP TABLE IF EXISTS Animal_types;
DROP TABLE IF EXISTS Ads;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Ad_media;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Media;

-- Create Tables
CREATE TABLE Media (
    media_id INTEGER PRIMARY KEY AUTOINCREMENT,
    file_name TEXT NOT NULL,
    media_type TEXT NOT NULL,
    uploaded DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT valid_media_type CHECK (media_type IN ('image', 'video'))
);

CREATE TABLE Users (
    user_id         INTEGER PRIMARY KEY AUTOINCREMENT,
    is_admin        BOOLEAN DEFAULT FALSE,
    username        TEXT UNIQUE NOT NULL,
    email           TEXT UNIQUE NOT NULL,
    password_hash   TEXT NOT NULL,
    name            TEXT NOT NULL,
    photo_id        INTEGER,
    phone           TEXT,
    district        TEXT NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_description TEXT DEFAULT '', -- <-- add this line

    FOREIGN KEY (photo_id) REFERENCES Media(media_id) ON DELETE CASCADE
);

CREATE TABLE Ad_media (
    ad_id       INTEGER,
    media_id    INTEGER,

    PRIMARY KEY (ad_id, media_id),
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (media_id) REFERENCES Media(media_id) ON DELETE CASCADE

);

CREATE TABLE Services (
    service_id      INTEGER PRIMARY KEY AUTOINCREMENT,
    service_name    TEXT UNIQUE NOT NULL,
    description     TEXT
);

CREATE TABLE Ads (
    ad_id           INTEGER PRIMARY KEY AUTOINCREMENT,
    service_id      INTEGER NOT NULL,
    freelancer_id   INTEGER NOT NULL,
    title           TEXT NOT NULL,
    description     TEXT NOT NULL,
    price           REAL NOT NULL,
    price_period    TEXT NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (freelancer_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id) ON DELETE RESTRICT,
    CONSTRAINT valid_price_period CHECK (price_period IN ('hora', 'semana', 'dia', 'mês'))
);

CREATE TABLE Animal_types (
    animal_id   INTEGER PRIMARY KEY AUTOINCREMENT,
    animal_name TEXT UNIQUE NOT NULL
);

CREATE TABLE Ad_animals (
    ad_id       INTEGER,
    animal_id   INTEGER,

    PRIMARY KEY (ad_id, animal_id),
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (animal_id) REFERENCES Animal_types(animal_id) ON DELETE RESTRICT
);

CREATE TABLE Orders (
    order_id        INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id       INTEGER NOT NULL,
    ad_id           INTEGER NOT NULL,
    order_date      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price     REAL NOT NULL,
    isPaid          BOOLEAN DEFAULT FALSE,
    isCompleted     BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE
);

CREATE TABLE Reviews (
    review_id       INTEGER PRIMARY KEY AUTOINCREMENT,
    ad_id           INTEGER NOT NULL,
    client_id       INTEGER NOT NULL,
    rating          INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment         TEXT,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

CREATE TABLE User_animals (
    animal_id       INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id         INTEGER NOT NULL,
    species         INTEGER NOT NULL,
    name            TEXT NOT NULL,
    age             INTEGER NOT NULL,
    animal_picture  TEXT,

    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (species) REFERENCES Animal_types(animal_id) ON DELETE RESTRICT
);

CREATE TABLE Messages (
    message_id      INTEGER PRIMARY KEY AUTOINCREMENT,
    ad_id           INTEGER NOT NULL,
    from_user_id    INTEGER NOT NULL,
    to_user_id      INTEGER NOT NULL,
    text            TEXT NOT NULL,
    sent_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read         BOOLEAN DEFAULT 0,

    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (from_user_id) REFERENCES Users (user_id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES Users (user_id) ON DELETE CASCADE
);

CREATE TABLE Ad_services (
    ad_id      INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    PRIMARY KEY (ad_id, service_id),
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES Services(service_id) ON DELETE CASCADE
);

-- Populating db

-- Inserir animal types
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

-- Inserir Media
INSERT INTO Media (file_name, media_type) VALUES ('1', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('2', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('3', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('4', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('5', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('6', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('7', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('8', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('9', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('10', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('11', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('12', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('13', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('ln', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('mn', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('nn', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('ot', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('pb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('fb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('gb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('hb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('jb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('ab', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('lb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('re', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('rt', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('br', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('rb', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('ie', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('jh', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('kn', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('ln', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('mn', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('nn', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('ot', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('pb', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('fb', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('gb', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('hb', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('jb', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('ab', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('lb', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('re', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('rt', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('br', 'video');
INSERT INTO Media (file_name, media_type) VALUES ('rb', 'video');

-- Inserir utilizadores
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('casemiro', 'casemiro@example.com', 'Casemiro', 1 , '912345678', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('francisca', 'francisca@example.com', 'Francisca', 2, '912345378', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('staragarcia', 'sara@example.com', 'Sara García', 3, '936737882', 'Porto','$2y$10$bo2fazm9RBwKYDs.ZUbDNOza8phRWEo7bPyI5LTM8SjGJX5PvNf2G', 'Olá! Sou uma das vítimas do projeto de LTW! Sou administradora deste site :) (mais um merge conflict e vou administrar a minha própria morte) Gosto muito de todos os animais, especialmente aqueles com um ar meio estúpido!! Neste moment tenho 3 ratazanas domésticas de estimação, e gostava muito de ter um pombo.');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('rita_t', 'rita@example.com', 'Rita Teixeira', 4, '914567890', 'Coimbra','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('joao_pets', 'joao@example.com', 'João Costa', 5, '913456789', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('maria123', 'maria@example.com', 'Maria Silva',6 , '912345678', 'Lisboa','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('tomas_vv3', 'tomasthebest@example.com', 'Tomás Ribeiro', 1, '914191674', 'Setúbal','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('anasantos', 'ana.santos@example.com', 'Ana Santos', 2, '961122334', 'Aveiro','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('carlosm', 'carlos.m@example.com', 'Carlos Martins',3, 'Braga' ,'923456789','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('sofia_l', 'sofia.l@example.com', 'Sofia Lima', 4 , 'Faro','935678901','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('miguel_p', 'miguel.p@example.com', 'Miguel Pereira', 5, 'Guarda','917890123','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('margarida_p', 'margarida.p@example.com','Margarida Pacheco' ,6 , 'Funchal', '927788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('joanam', 'joana.m@example.com', 'Joana Marta', 1, 'Beja', '939900112','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('helder_c', 'helder.c@example.com', 'Helder Carlo', 2, 'Portalegre', '916611223','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('fernando_g', 'fernando.g@example.com', 'Fernando Gonçalves',3 , '918899002', 'Bragança','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash, user_description) VALUES ('tiagom', 'tiago.m@example.com', 'Tiago Mendes', 4, 'Santarém', '969876543','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('patriciav', 'patricia.v@example.com', 'Patrícia Vicente',5, 'Setúbal', '921234567','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('brunor', 'bruno.r@example.com', 'Bruno Ribeiro', 6, 'Viana do Castelo', '967788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('carla_s', 'carla.s@example.com', 'Carla Sousa', 1, 'Vila Real', '928899001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('carlos_r', 'carlo.s@example.com', 'Carlos Ribeiro', 2, 'Vila Real ', '928838001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('beatriz_c', 'beatriz.c@example.com', 'Beatriz Castro',3, 'Aveiro', '936677889','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('ricardoj', 'ricardo.j@example.com', 'Ricardo Sousa',4, 'Faro', '933445566','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('andreia_p', 'andreia.p@example.com','Andreia Sousa', 5, 'Porto', '915566778','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('jose_s', 'jose.s@example.com', 'Jose Sousa',4, 'Sines', '919900113','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, password_hash, user_description) VALUES ('pedror', 'pedro.r@example.com', 'Pedro Rodrigues', 5, 'Évora','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, password_hash, user_description) VALUES ('sara_c', 'sara.c@example.com', 'Sara Costa', 6, 'Castelo Branco','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, password_hash, user_description) VALUES ('manuel_a', 'manuel.a@example.com', 'Manuel Almeida',1, 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('luis_g', 'luis.g@example.com', 'Luis Sousa', 2, 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('ines_f', 'ines.f@example.com', 'Ines Fagundes',3, 'Viseu','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash, user_description) VALUES ('isabel_m', 'isabel.m@example.com', 'Isabel Moreira',4, 'Guimarães','7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '');

-- Inserir admins

UPDATE Users SET is_admin = TRUE WHERE username = 'casemiro';
UPDATE Users SET is_admin = TRUE WHERE username = 'francisca';
UPDATE Users SET is_admin = TRUE WHERE username = 'staragarica';

-- Inserir anúncios
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4, 'Treino básico para cachorros', 4, 'Ensino comandos básicos e boas práticas.', 75.00, 'mês');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Petsitting em casa ou no seu domicílio', 5, 'Cuidamos do seu animal com todo o amor.', 100.00, 'semana');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Banho e tosquia profissional para cães', 6, 'Experiência com raças grandes e pequenas.', 25.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Passeios para o seu cão em Lisboa', 7, 'Passeios diários no seu bairro com muito carinho.', 10.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Passeios para o seu cão' , 5, 'Passeios experientes para os seus amigos de quatro patas.', 12.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Treino Básico de Obediência ',4, 'Treino com reforço positivo para cachorros.', 35.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Serviços de Tosquia e Banhos em Aveiro', 8, 'Serviços completos de tosquia e banhos para cães e gatos.', 30.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (5, 'Cuidados para Pássaros Durante as Suas Viagens', 12, 'Cuido dos seus amigos de penas.', 8.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (5,'Alojamento para Pequenos Animais ', 13, 'Alojamento seguro e confortável para pequenos animais.', 10.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Treinador de Cães Experiente na Guarda',15, 'Treino avançado e modificação de comportamento.', 50.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Cuidados de Animais ao Domicílio em Leiria',5, 'Alimentação, brincadeira e cuidados básicos para os seus animais em casa.', 20.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Cuidados para Coelhos e Roedores em Viseu', 8, 'Cuidados especializados para coelhos, porquinhos-da-índia, etc.', 10.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Serviços de Cuidados em Évora', 12, 'Tosquia e cuidados básicos para caes.', 45.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais em Castelo Branco',21, 'Cuido do seu animal enquanto estiver fora.', 60.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Tosquia Canina Móvel em Santarém', 12, 'Serviços de tosquia convenientes à sua porta.', 40.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Especialista em Tosquia de Gatos no Setúbal',19, 'Tosquia suave e completa para gatos.', 35.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Serviços de Babysitting de Gatos em Coimbra', 11, 'Babysitting de gatos de confiança e com carinho na sua casa.', 15.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Passeios de Cães e Visitas ao Parque (Faro)', 5, 'Passeios energéticos e brincadeira em parques locais.', 15.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais em Casa no Porto', 7, 'Conforto e cuidado para os seus animais no ambiente familiar deles.', 25.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Cuidados para Animais Exóticos no Beja', 8, 'Cuidados especializados para répteis, anfíbios, etc.', 20.00, 'dia' );
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Treino Canino - Problemas de Comportamento', 6, 'A lidar com latidos, roer e outros problemas.', 60.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Pequenos Animais em Viana do Castelo',9, 'Cuidado carinhoso para hamsters, gerbos, etc.', 7.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Banhos Profissionais para Cães na Vila Real', 14, 'Serviços de banho suaves e eficazes.', 20.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (7,'Serviços de Táxi para Animais em Bragança', 16, 'Transporte seguro para os seus animais.', 15.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Aventuras de Passeio para Cães (Guimarães)', 17, 'Passeios divertidos e estimulantes para cães ativos.', 18.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais Noturno em Leiria', 18, 'Cuidados 24 horas para os seus amados animais.', 40.00, 'dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Aulas de Socialização para Cachorros (Aveiro)', 16, 'Socialização precoce para cachorros bem ajustados.', 70.00, 'mês');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Alimentação de Gatos e Limpeza de Caixa de Areia (Sines)',18, 'Visitas diárias para cuidados essenciais de gatos.', 10.00, 'hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais na Ilha (Funchal)', 15, 'Cuide dos seus animais enquanto desfruta do seu tempo fora.', 30.00, 'dia');

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


-- Associar media aos anúncios
INSERT INTO Ad_media (ad_id, media_id) VALUES (1, 8);
INSERT INTO Ad_media (ad_id, media_id) VALUES (2, 7);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 7);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 9);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 8);
INSERT INTO Ad_media (ad_id, media_id) VALUES (4, 7);
INSERT INTO Ad_media (ad_id, media_id) VALUES (5, 8);
INSERT INTO Ad_media (ad_id, media_id) VALUES (6, 9);
INSERT INTO Ad_media (ad_id, media_id) VALUES (7, 12);
INSERT INTO Ad_media (ad_id, media_id) VALUES (7, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (8, 12);
INSERT INTO Ad_media (ad_id, media_id) VALUES (9, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (10, 11);
INSERT INTO Ad_media (ad_id, media_id) VALUES (11, 12);
INSERT INTO Ad_media (ad_id, media_id) VALUES (12, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (13, 11);
INSERT INTO Ad_media (ad_id, media_id) VALUES (14, 12);
INSERT INTO Ad_media (ad_id, media_id) VALUES (15, 10);
INSERT INTO Ad_media (ad_id, media_id) VALUES (16, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (17, 11);
INSERT INTO Ad_media (ad_id, media_id) VALUES (18, 12);
INSERT INTO Ad_media (ad_id, media_id) VALUES (19, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (20, 7);
INSERT INTO Ad_media (ad_id, media_id) VALUES (21, 8);
INSERT INTO Ad_media (ad_id, media_id) VALUES (22, 9);
INSERT INTO Ad_media (ad_id, media_id) VALUES (23, 10);
INSERT INTO Ad_media (ad_id, media_id) VALUES (24, 11);
INSERT INTO Ad_media (ad_id, media_id) VALUES (25, 12);
INSERT INTO Ad_media (ad_id, media_id) VALUES (26, 13);
INSERT INTO Ad_media (ad_id, media_id) VALUES (27, 7);
INSERT INTO Ad_media (ad_id, media_id) VALUES (29, 8);

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

-- Inserir reviews
INSERT INTO Reviews (ad_id, client_id, rating, comment) VALUES
(1, 1, 5, 'Excelente serviço! Meu cachorro voltou muito feliz do treino.'),
(1, 2, 4, 'Treinador muito paciente e eficaz. Recomendo!'),
(1, 3, 4, 'Meu cachorro aprendeu muito rápido. Ótimo trabalho!'),
(1, 9, 5, 'Profissionalismo e carinho com os animais. Perfeito!'),
(1, 10, 4, 'Bom custo-benefício. O cão obedece mais.'),

(2, 1, 5, 'Cuidaram muito bem do meu gato. Serviço impecável!'),
(2, 2, 4, 'Confiável e atencioso. Meu pet ficou super bem.'),
(2, 3, 5, 'Profissionalismo e amor pelos animais. Recomendo demais!'),
(2, 11, 2, 'Serviço ok, mas o preço é um pouco alto.'),
(2, 12, 5, 'Meu cão adorou o petsitting. Muito carinho e atenção.'),

(3, 1, 5, 'Banho e tosquia perfeitos! Minha poodle ficou linda.'),
(3, 2, 4, 'Ótimo serviço, mas demorou um pouco mais do que o esperado.'),
(3, 3, 5, 'Profissional muito cuidadoso e atencioso. Super recomendo!'),
(3, 13, 5, 'Excelente! Meu pet ficou cheiroso e bem tosado.'),
(3, 14, 4, 'Bom trabalho, mas a comunicação poderia ser melhor.'),

(4, 1, 5, 'Passeios maravilhosos! Meu cão voltou exausto e feliz.'),
(4, 2, 5, 'Passeador muito responsável e pontual. Adorei!'),
(4, 3, 4, 'Meu cão se divertiu muito. Ótimo serviço!'),
(4, 15, 5, 'Passeios com muita energia e carinho. Recomendo!'),
(4, 16, 4, 'Bom para o dia a dia, mas a rota é sempre a mesma.'),

(5, 1, 5, 'Passeios incríveis! Meu cachorro adorou a experiência.'),
(5, 2, 5, 'Profissional atencioso e dedicado. Meu pet voltou muito feliz!'),
(5, 3, 4, 'Bom serviço, mas o preço é um pouco elevado.'),
(5, 17, 5, 'Passeios com muita atenção e segurança. Recomendo!'),
(5, 18, 4, 'Pontual e profissional. Meu cão se adaptou bem.'),

(6, 1, 5, 'Treino eficaz! Meu cachorro está mais obediente.'),
(6, 2, 5, 'Treinador paciente e com ótimas técnicas. Recomendo!'),
(6, 3, 4, 'Bom para comandos básicos, mas esperava mais progresso.'),
(6, 19, 5, 'Resultados visíveis em pouco tempo. Excelente!'),
(6, 20, 4, 'Profissionalismo e dedicação. Meu cão está melhorando.'),

(7, 1, 5, 'Serviços de tosquia e banhos excelentes. Minha gata ficou linda!'),
(7, 2, 4, 'Bom trabalho, mas o agendamento foi um pouco complicado.'),
(7, 3, 5, 'Profissional muito cuidadoso e atencioso. Recomendo!'),
(7, 4, 5, 'Meu pet ficou impecável. Ótimo serviço!'),
(7, 5, 4, 'Qualidade boa, mas o local é um pouco barulhento.'),

(8, 1, 5, 'Cuidaram muito bem dos meus pássaros. Super recomendo!'),
(8, 2, 5, 'Profissional atencioso e com conhecimento sobre aves.'),
(8, 3, 4, 'Bom para viagens curtas, mas o preço é um pouco alto.'),
(8, 6, 5, 'Meus pássaros estavam muito bem cuidados. Excelente!'),
(8, 7, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(9, 1, 5, 'Alojamento seguro e confortável. Meus pequenos animais adoraram!'),
(9, 2, 5, 'Ótimo lugar para deixar seus pets. Recomendo!'),
(9, 3, 4, 'Bom para pequenos animais, mas o espaço é um pouco limitado.'),
(9, 8, 5, 'Meus roedores ficaram muito bem cuidados. Excelente!'),
(9, 9, 4, 'Confiável, mas a localização é um pouco distante.'),

(10, 1, 5, 'Treinador experiente e com ótimas dicas. Meu cão melhorou muito!'),
(10, 2, 5, 'Profissionalismo e paixão pelos animais. Recomendo!'),
(10, 3, 4, 'Bom para problemas de comportamento, mas o preço é um pouco alto.'),
(10, 10, 5, 'Resultados impressionantes. Muito satisfeito!'),
(10, 11, 4, 'Eficaz, mas o tempo de resposta é um pouco lento.'),

(11, 1, 5, 'Cuidados ao domicílio excelentes. Meus pets ficaram muito bem!'),
(11, 2, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(11, 3, 4, 'Bom para cuidados básicos, mas esperava mais interação.'),
(11, 12, 5, 'Meus animais receberam muito carinho. Excelente!'),
(11, 13, 4, 'Confiável, mas a disponibilidade é um pouco limitada.'),

(12, 1, 5, 'Cuidados especializados para coelhos. Meu coelho adorou!'),
(12, 2, 5, 'Profissional com conhecimento sobre roedores. Recomendo!'),
(12, 3, 4, 'Bom para coelhos, mas o preço é um pouco alto.'),
(12, 14, 5, 'Meus roedores ficaram muito bem cuidados. Excelente!'),
(12, 15, 4, 'Atencioso, mas a comunicação poderia ser mais frequente.'),

(13, 1, 5, 'Serviços de tosquia e cuidados excelentes. Meu cão ficou lindo!'),
(13, 2, 4, 'Bom trabalho, mas o agendamento foi um pouco complicado.'),
(13, 3, 5, 'Profissional muito cuidadoso e atencioso. Recomendo!'),
(13, 16, 5, 'Meu pet ficou impecável. Ótimo serviço!'),
(13, 17, 4, 'Qualidade boa, mas o local é um pouco barulhento.'),

(14, 1, 5, 'Babysitting excelente! Meu animal ficou muito bem cuidado.'),
(14, 2, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(14, 3, 4, 'Bom para viagens curtas, mas o preço é um pouco alto.'),
(14, 18, 5, 'Meus pets estavam muito bem cuidados. Excelente!'),
(14, 19, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(15, 1, 5, 'Tosquia móvel muito conveniente. Meu cão ficou lindo!'),
(15, 2, 4, 'Bom trabalho, mas o agendamento foi um pouco complicado.'),
(15, 3, 5, 'Profissional muito cuidadoso e atencioso. Recomendo!'),
(15, 20, 5, 'Meu pet ficou impecável. Ótimo serviço!'),
(15, 21, 4, 'Qualidade boa, mas o tempo de espera foi um pouco longo.'),

(16, 1, 5, 'Especialista em tosquia de gatos. Minha gata ficou linda!'),
(16, 2, 4, 'Bom trabalho, mas o agendamento foi um pouco complicado.'),
(16, 3, 5, 'Profissional muito cuidadoso e atencioso. Recomendo!'),
(16, 1, 5, 'Minha gata ficou impecável. Ótimo serviço!'),
(16, 2, 4, 'Qualidade boa, mas o preço é um pouco alto.'),

(17, 3, 5, 'Babysitting de gatos de confiança. Meus gatos adoraram!'),
(17, 4, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(17, 5, 4, 'Bom para gatos, mas o preço é um pouco alto.'),
(17, 6, 5, 'Meus gatos estavam muito bem cuidados. Excelente!'),
(17, 7, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(18, 8, 5, 'Passeios energéticos e divertidos. Meu cão adorou!'),
(18, 9, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(18, 10, 4, 'Bom para cães ativos, mas o preço é um pouco alto.'),
(18, 11, 5, 'Meu cão voltou exausto e feliz. Excelente!'),
(18, 12, 4, 'Confiável, mas a disponibilidade é um pouco limitada.'),

(19, 13, 5, 'Babysitting em casa muito conveniente. Meus animais adoraram!'),
(19, 14, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(19, 15, 4, 'Bom para viagens, mas o preço é um pouco alto.'),
(19, 16, 5, 'Meus animais estavam muito bem cuidados. Excelente!'),
(19, 17, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(20, 18, 5, 'Cuidados especializados para animais exóticos. Meu réptil adorou!'),
(20, 19, 5, 'Profissional com conhecimento sobre animais exóticos. Recomendo!'),
(20, 20, 4, 'Bom para animais exóticos, mas o preço é um pouco alto.'),
(20, 21, 5, 'Meus animais exóticos ficaram muito bem cuidados. Excelente!'),
(20, 1, 4, 'Atencioso, mas a disponibilidade é um pouco limitada.'),

(21, 2, 5, 'Treino eficaz para problemas de comportamento. Meu cão melhorou muito!'),
(21, 3, 5, 'Profissionalismo e paixão pelos animais. Recomendo!'),
(21, 4, 4, 'Bom para problemas de comportamento, mas o preço é um pouco alto.'),
(21, 5, 5, 'Resultados impressionantes. Muito satisfeito!'),
(21, 6, 4, 'Eficaz, mas o tempo de resposta é um pouco lento.'),

(22, 7, 5, 'Babysitting de pequenos animais muito carinhoso. Meus hamsters adoraram!'),
(22, 8, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(22, 9, 4, 'Bom para pequenos animais, mas o preço é um pouco alto.'),
(22, 10, 5, 'Meus roedores ficaram muito bem cuidados. Excelente!'),
(22, 11, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(23, 12, 5, 'Banhos profissionais para cães. Meu cão ficou lindo!'),
(23, 13, 4, 'Bom trabalho, mas o agendamento foi um pouco complicado.'),
(23, 14, 5, 'Profissional muito cuidadoso e atencioso. Recomendo!'),
(23, 15, 5, 'Meu pet ficou impecável. Ótimo serviço!'),
(23, 16, 4, 'Qualidade boa, mas o local é um pouco barulhento.'),

(24, 17, 5, 'Táxi para animais muito seguro e confortável. Recomendo!'),
(24, 18, 5, 'Profissional atencioso e pontual. Meu pet viajou tranquilo!'),
(24, 19, 4, 'Bom para transporte, mas o preço é um pouco alto.'),
(24, 20, 5, 'Meu pet chegou em segurança. Excelente!'),
(24, 21, 4, 'Confiável, mas a disponibilidade é um pouco limitada.'),

(25, 1, 5, 'Aventuras de passeio muito divertidas. Meu cão adorou!'),
(25, 2, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(25, 3, 4, 'Bom para cães ativos, mas o preço é um pouco alto.'),
(25, 4, 5, 'Meu cão voltou exausto e feliz. Excelente!'),
(25, 5, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(26, 6, 5, 'Babysitting noturno muito seguro. Meus animais ficaram muito bem!'),
(26, 7, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(26, 8, 4, 'Bom para cuidados 24 horas, mas o preço é um pouco alto.'),
(26, 9, 5, 'Meus animais estavam muito bem cuidados. Excelente!'),
(26, 10, 4, 'Confiável, mas a disponibilidade é um pouco limitada.'),

(27, 11, 5, 'Aulas de socialização excelentes. Meu cachorro está mais sociável!'),
(27, 12, 5, 'Profissionalismo e paixão pelos animais. Recomendo!'),
(27, 13, 4, 'Bom para socialização, mas o preço é um pouco alto.'),
(27, 14, 5, 'Resultados impressionantes. Muito satisfeito!'),
(27, 15, 4, 'Eficaz, mas o tempo de resposta é um pouco lento.'),

(28, 16, 5, 'Alimentação de gatos e limpeza de caixa de areia. Serviço impecável!'),
(28, 17, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(28, 18, 4, 'Bom para gatos, mas o preço é um pouco alto.'),
(28, 19, 5, 'Meus gatos estavam muito bem cuidados. Excelente!'),
(28, 20, 4, 'Confiável, mas a comunicação poderia ser mais frequente.'),

(29, 21, 5, 'Babysitting na ilha muito conveniente. Meus animais adoraram!'),
(29, 1, 5, 'Profissional atencioso e dedicado. Recomendo!'),
(29, 2, 4, 'Bom para viagens, mas o preço é um pouco alto.'),
(29, 3, 5, 'Meus animais estavam muito bem cuidados. Excelente!'),
(29, 4, 4, 'Confiável, mas a comunicação poderia ser mais frequente.');

-- Query para obter nome e foto de animais de um utilizador específico
SELECT name, animal_picture FROM user_animals WHERE user_id = 1;

INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (1, 1, 'Bobby', 3, './resources/bobby.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (1, 2, 'Mimi', 2, './resources/mimi.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (2, 1, 'Thor', 5, './resources/thor.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (3, 3, 'PiuPiu', 1, './resources/piupiu.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (4, 4, 'Jerry', 2, './resources/jerry.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (5, 5, 'Rex', 4, './resources/rex.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (6, 6, 'Nemo', 1, './resources/nemo.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (7, 7, 'Furãozinho', 2, './resources/furaozinho.png');
INSERT INTO User_animals (user_id, species, name, age, animal_picture) VALUES (8, 8, 'Coelhinho', 3, './resources/coelhinho.png');
