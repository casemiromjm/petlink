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
    CONSTRAINT valid_price_period CHECK (price_period IN ('Hora', 'Semana', 'Dia', 'Mês'))
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
INSERT INTO Media (file_name, media_type) VALUES ('eg', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('fg', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('gg', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('hg', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('ie', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('jh', 'image');
INSERT INTO Media (file_name, media_type) VALUES ('kn', 'image');
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
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('casemiro', 'casemiro@example.com', 'Casemiro', 1 , '912345678', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('francisca', 'francisca@example.com', 'Francisca', 2, '912345378', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('staragarcia', 'sara@example.com', 'Sarita', 3, '912344678', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('rita_t', 'rita@example.com', 'Rita Teixeira', 4, '914567890', 'Coimbra','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('joao_pets', 'joao@example.com', 'João Costa', 5, '913456789', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('maria123', 'maria@example.com', 'Maria Silva',6 , '912345678', 'Lisboa','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('tomas_vv3', 'tomasthebest@example.com', 'Tomás Ribeiro', 1, '914191674', 'Setúbal','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('anasantos', 'ana.santos@example.com', 'Ana Santos', 2, 'Aveiro', '961122334','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('carlosm', 'carlos.m@example.com', 'Carlos Martins',3, 'Braga' ,'923456789','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('sofia_l', 'sofia.l@example.com', 'Sofia Lima', 4 , 'Faro','935678901','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('miguel_p', 'miguel.p@example.com', 'Miguel Pereira', 5, 'Guarda','917890123','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('margarida_p', 'margarida.p@example.com','Margarida Pacheco' ,6 , 'Funchal', '927788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('joanam', 'joana.m@example.com', 'Joana Marta', 1, 'Beja', '939900112','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('helder_c', 'helder.c@example.com', 'Helder Carlo', 2, 'Portalegre', '916611223','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, phone, district, password_hash) VALUES ('fernando_g', 'fernando.g@example.com', 'Fernando Gonçalves',3 , '918899002', 'Bragança','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('tiagom', 'tiago.m@example.com', 'Tiago Mendes', 4, 'Santarém', '969876543','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('patriciav', 'patricia.v@example.com', 'Patrícia Vicente',5, 'Setúbal', '921234567','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('brunor', 'bruno.r@example.com', 'Bruno Ribeiro', 6, 'Viana do Castelo', '967788990','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('carla_s', 'carla.s@example.com', 'Carla Sousa', 1, 'Vila Real', '928899001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('carlos_r', 'carlo.s@example.com', 'Carlos Ribeiro', 2, 'Vila Real ', '928838001','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('beatriz_c', 'beatriz.c@example.com', 'Beatriz Castro',3, 'Aveiro', '936677889','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('ricardoj', 'ricardo.j@example.com', 'Ricardo Sousa',4, 'Faro', '933445566','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('andreia_p', 'andreia.p@example.com','Andreia Sousa', 5, 'Porto', '915566778','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, phone, password_hash) VALUES ('jose_s', 'jose.s@example.com', 'Jose Sousa',4, 'Sines', '919900113','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, password_hash) VALUES ('pedror', 'pedro.r@example.com', 'Pedro Rodrigues', 5, 'Évora','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, password_hash) VALUES ('sara_c', 'sara.c@example.com', 'Sara Costa', 6, 'Castelo Branco','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, password_hash) VALUES ('manuel_a', 'manuel.a@example.com', 'Manuel Almeida',1, 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, password_hash) VALUES ('luis_g', 'luis.g@example.com', 'Luis Sousa', 2, 'Leiria','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, password_hash) VALUES ('ines_f', 'ines.f@example.com', 'Ines Fagundes',3, 'Viseu','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, photo_id, district, password_hash) VALUES ('isabel_m', 'isabel.m@example.com', 'Isabel Moreira',4, 'Guimarães','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- Inserir admins

UPDATE Users SET is_admin = TRUE WHERE username = 'casemiro';
UPDATE Users SET is_admin = TRUE WHERE username = 'francisca';
UPDATE Users SET is_admin = TRUE WHERE username = 'staragarica';

-- Inserir anúncios
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4, 'Treino básico para cachorros', 4, 'Ensino comandos básicos e boas práticas.', 75.00, 'Mês');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Petsitting em casa ou no seu domicílio', 5, 'Cuidamos do seu animal com todo o amor.', 100.00, 'Semana' );
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Banho e tosquia profissional para cães', 6, 'Experiência com raças grandes e pequenas.', 25.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Passeios para o seu cão em Lisboa', 7, 'Passeios diários no seu bairro com muito carinho.', 10.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Passeios para o seu cão' , 5, 'Passeios experientes para os seus amigos de quatro patas.', 12.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Treino Básico de Obediência ',4, 'Treino com reforço positivo para cachorros.', 35.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Serviços de Tosquia e Banhos em Aveiro', 8, 'Serviços completos de tosquia e banhos para cães e gatos.', 30.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (5, 'Cuidados para Pássaros Durante as Suas Viagens', 12, 'Cuido dos seus amigos de penas.', 8.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (5,'Alojamento para Pequenos Animais ', 13, 'Alojamento seguro e confortável para pequenos animais.', 10.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Treinador de Cães Experiente na Guarda',15, 'Treino avançado e modificação de comportamento.', 50.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Cuidados de Animais ao Domicílio em Leiria',5, 'Alimentação, brincadeira e cuidados básicos para os seus animais em casa.', 20.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Cuidados para Coelhos e Roedores em Viseu', 8, 'Cuidados especializados para coelhos, porquinhos-da-índia, etc.', 10.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Serviços de Cuidados em Évora', 12, 'Tosquia e cuidados básicos para caes.', 45.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais em Castelo Branco',21, 'Cuido do seu animal enquanto estiver fora.', 60.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Tosquia Canina Móvel em Santarém', 12, 'Serviços de tosquia convenientes à sua porta.', 40.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Especialista em Tosquia de Gatos no Setúbal',19, 'Tosquia suave e completa para gatos.', 35.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Serviços de Babysitting de Gatos em Coimbra', 11, 'Babysitting de gatos de confiança e com carinho na sua casa.', 15.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Passeios de Cães e Visitas ao Parque (Faro)', 5, 'Passeios energéticos e brincadeira em parques locais.', 15.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais em Casa no Porto', 7, 'Conforto e cuidado para os seus animais no ambiente familiar deles.', 25.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (6,'Cuidados para Animais Exóticos no Beja', 8, 'Cuidados especializados para répteis, anfíbios, etc.', 20.00, 'Dia' );
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Treino Canino - Problemas de Comportamento', 6, 'A lidar com latidos, roer e outros problemas.', 60.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Pequenos Animais em Viana do Castelo',9, 'Cuidado carinhoso para hamsters, gerbos, etc.', 7.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (2,'Banhos Profissionais para Cães na Vila Real', 14, 'Serviços de banho suaves e eficazes.', 20.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (7,'Serviços de Táxi para Animais em Bragança', 16, 'Transporte seguro para os seus animais.', 15.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (1,'Aventuras de Passeio para Cães (Guimarães)', 17, 'Passeios divertidos e estimulantes para cães ativos.', 18.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais Noturno em Leiria', 18, 'Cuidados 24 horas para os seus amados animais.', 40.00, 'Dia');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (4,'Aulas de Socialização para Cachorros (Aveiro)', 16, 'Socialização precoce para cachorros bem ajustados.', 70.00, 'Mês');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Alimentação de Gatos e Limpeza de Caixa de Areia (Sines)',18, 'Visitas diárias para cuidados essenciais de gatos.', 10.00, 'Hora');
INSERT INTO Ads (service_id, title, freelancer_id, description, price, price_period) VALUES (3,'Babysitting de Animais na Ilha (Funchal)', 15, 'Cuide dos seus animais enquanto desfruta do seu tempo fora.', 30.00, 'Dia');

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
INSERT INTO Ad_media (ad_id, media_id) VALUES (1, 31);
INSERT INTO Ad_media (ad_id, media_id) VALUES (2, 32);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 33);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 34);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 35);
INSERT INTO Ad_media (ad_id, media_id) VALUES (3, 36);
INSERT INTO Ad_media (ad_id, media_id) VALUES (4, 37);
INSERT INTO Ad_media (ad_id, media_id) VALUES (5, 38);
INSERT INTO Ad_media (ad_id, media_id) VALUES (6, 39);
INSERT INTO Ad_media (ad_id, media_id) VALUES (7, 40);
INSERT INTO Ad_media (ad_id, media_id) VALUES (7, 41);
INSERT INTO Ad_media (ad_id, media_id) VALUES (8, 42);
INSERT INTO Ad_media (ad_id, media_id) VALUES (9, 43);
INSERT INTO Ad_media (ad_id, media_id) VALUES (10, 44);
INSERT INTO Ad_media (ad_id, media_id) VALUES (11, 45);
INSERT INTO Ad_media (ad_id, media_id) VALUES (11, 46);
INSERT INTO Ad_media (ad_id, media_id) VALUES (21, 31);
INSERT INTO Ad_media (ad_id, media_id) VALUES (22, 32);
INSERT INTO Ad_media (ad_id, media_id) VALUES (23, 35);
INSERT INTO Ad_media (ad_id, media_id) VALUES (24, 43);
INSERT INTO Ad_media (ad_id, media_id) VALUES (26, 41);
INSERT INTO Ad_media (ad_id, media_id) VALUES (27, 46);
INSERT INTO Ad_media (ad_id, media_id) VALUES (29, 35);

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
--INSERT INTO Reviews (order_id, freelancer_username, client_username, rating, comment) VALUES
--INSERT INTO Reviews (order_id, freelancer_username, client_username, rating) VALUES

-- Query para obter nome e foto de animais de um utilizador específico
SELECT name, animal_picture FROM user_animals WHERE user_id = 1;
