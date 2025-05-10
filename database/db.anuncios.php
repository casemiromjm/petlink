<?php
declare(strict_types = 1);

function getAnuncios(PDO $db, int $limit = 16): array {
    $query = '
        SELECT ads.id, ads.title, ads.description, ads.service_type, ads.price, ads.price_period, ads.image_path, users.username, users.name
        FROM ads
        JOIN users ON ads.username = users.username
        ORDER BY ads.id DESC
        LIMIT ' . intval($limit);

    $stmt = $db->query($query);

    $anuncios = [];
    while ($anuncio = $stmt->fetch()) {
        $anuncios[] = array(
            'id' => $anuncio['id'],
            'title' => $anuncio['title'],
            'description' => $anuncio['description'],
            'service_type' => $anuncio['service_type'],
            'price' => $anuncio['price'],
            'price_period' => $anuncio['price_period'],
            'image_path' => $anuncio['image_path'],
            'username' => $anuncio['username'],
            'name' => $anuncio['name'],
            'animals' => getAnuncioAnimals($db, $anuncio['id'])
        );
    }

    return $anuncios;
}

function getAnuncio(PDO $db, int $id): array {
    $stmt = $db->prepare('
        SELECT ads.id, ads.title, ads.description, ads.service_type, ads.price, ads.price_period, users.username 
        FROM ads 
        JOIN users ON ads.username = users.username
        WHERE ads.id = ?
    ');
    $stmt->execute([$id]);

    $anuncio = $stmt->fetch();

    if (!$anuncio) {
        throw new Exception('Anúncio não encontrado.');
    }

    return array(
        'id' => $anuncio['id'],
        'title' => $anuncio['title'],
        'description' => $anuncio['description'],
        'service_type' => $anuncio['service_type'],
        'price' => $anuncio['price'],
        'price_period' => $anuncio['price_period'],
        'username' => $anuncio['username'],
        'animals' => getAnuncioAnimals($db, $anuncio['id'])
    );
}

function getAnuncioAnimals(PDO $db, int $adId): array {
    $stmt = $db->prepare('
        SELECT animal_type 
        FROM ad_animals 
        WHERE ad_id = ?
    ');
    $stmt->execute([$adId]);

    $animals = [];
    while ($animal = $stmt->fetch()) {
        $animals[] = $animal['animal_type'];
    }

    return $animals;
}

function getAdById(PDO $db, int $id): ?array {
    $stmt = $db->prepare('
        SELECT ads.*, users.name, users.username, users.description AS user_description
        FROM ads
        JOIN users ON ads.username = users.username
        WHERE ads.id = ?
    ');
    $stmt->execute([$id]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    return $ad ?: null;
}
?>