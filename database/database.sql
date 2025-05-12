DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Ads;
DROP TABLE IF EXISTS Ad_animals;
DROP TABLE IF EXISTS Animal_types;
DROP TABLE IF EXISTS Services;
DROP TABLE IF EXISTS Ad_services;
DROP TABLE IF EXISTS Orders;

-- Criar tables

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username NVARCHAR(40) UNIQUE NOT NULL,
    email NVARCHAR(60) UNIQUE NOT NULL,
    password_hash NVARCHAR(255) NOT NULL,
    name NVARCHAR(60),
    profile_photo NVARCHAR(255),
    phone NVARCHAR(24),
    district NVARCHAR(40) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);

CREATE TABLE Ads (
    ad_id INT AUTO_INCREMENT PRIMARY KEY,
    username NVARCHAR(40) NOT NULL,
    title NVARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    price_period NVARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (username) REFERENCES Users(username) ON DELETE CASCADE
);

CREATE TABLE Animal_types (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,
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
    service_id  INT AUTO_INCREMENT PRIMARY KEY,
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
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    client_username NVARCHAR(40) NOT NULL,
    ad_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10, 2) NOT NULL,
    isPaid BOOLEAN DEFAULT FALSE,
    isCompleted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (client_username) REFERENCES Users(username) ON DELETE CASCADE,
    FOREIGN KEY (ad_id) REFERENCES Ads(ad_id) ON DELETE CASCADE
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

-- Inserir utilizadores
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('maria123', 'maria@example.com', 'Maria Silva', 'default_profile.png', '912345678', 'Lisboa','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('joao_pets', 'joao@example.com', 'João Costa', 'default_profile.png', '913456789', 'Porto','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('rita_t', 'rita@example.com', 'Rita Teixeira', 'default_profile.png', '914567890', 'Coimbra','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');
INSERT INTO Users (username, email, name, profile_photo, phone, district, password_hash) VALUES ('tomas_vv3', 'tomasthebest@example.com', 'Tomás Ribeiro', 'default_profile.png', '914191674', 'Setúbal','7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- Inserir anúncios
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Passeios para o seu cão em Lisboa', 'maria123', 'Passeios diários no seu bairro com muito carinho.', 10, 'Hora');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Banho e tosquia profissional para cães', 'joao_pets', 'Experiência com raças grandes e pequenas.', 25, 'Dia');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Petsitting em casa ou no seu domicílio', 'rita_t', 'Cuidamos do seu animal com todo o amor.', 100, 'Semana');
INSERT INTO Ads (title, username, description, price, price_period) VALUES ('Treino básico para cachorros', 'maria123', 'Ensino comandos básicos e boas práticas.', 40, 'Mês');

-- Associar tipos de animais aos anúncios
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (1, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (2, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 1); -- Cães
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 2); -- Gatos
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 3); -- Pássaros
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (3, 4); -- Roedores
INSERT INTO Ad_animals (ad_id, animal_id) VALUES (4, 1); -- Cães

-- Associar tipos de serviço aos anúncios
INSERT INTO Ad_services(ad_id, service_id) VALUES (1, 1); -- Passeio
INSERT INTO Ad_services(ad_id, service_id) VALUES (2, 2); -- Tosquia
INSERT INTO Ad_services(ad_id, service_id) VALUES (3, 3); -- Petsitting
INSERT INTO Ad_services(ad_id, service_id) VALUES (4, 4); -- Treino

-- Inserir orders
INSERT INTO Orders(client_username, ad_id, total_price) VALUES
INSERT INTO Orders(client_username, ad_id, total_price, isPaid) VALUES
INSERT INTO Orders(client_username, ad_id, total_price, isPaid, isCompleted) VALUES
