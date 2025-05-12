<?php
declare(strict_types = 1);
function getAnuncios(PDO $db,int $page = 1, int $limit = 16): array {
    $offset = ($page - 1) * $limit;
    $query = '
        SELECT ads.ad_id, ads.title, ads.description, ads.price, ads.price_period, ads.image_path, users.username, users.name
        FROM ads
        JOIN users ON ads.username = users.username
        ORDER BY ads.ad_id DESC
        LIMIT ' . intval($limit); // This part is the main issue for pagination
    $stmt = $db->query($query); // Using $db->query directly

    $anuncios = [];
    while ($anuncio = $stmt->fetch()) {
        $anuncios[] = array(
            'id' => $anuncio['ad_id'],
            'title' => $anuncio['title'],
            'description' => $anuncio['description'],
            'price' => $anuncio['price'],
            'price_period' => $anuncio['price_period'],
            'image_path' => $anuncio['image_path'],
            'username' => $anuncio['username'],
            'name' => $anuncio['name'],
            'animals' => getAnuncioAnimals($db, $anuncio['ad_id'])
        );
    }
    return $anuncios;
}

function getTotalAdCount(PDO $db): int {
    $query = 'SELECT COUNT(*) FROM ads';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getAnuncio(PDO $db, int $id): array {
    $stmt = $db->prepare('
        SELECT ads.ad_id, ads.title, ads.description, ads.price, ads.price_period, ads.username
        FROM ads
        JOIN users ON ads.username = Users.username
        WHERE ads.ad_id = ?
    ');
    $stmt->execute([$ad_id]);

    $anuncio = $stmt->fetch();

    if (!$anuncio) {
        throw new Exception('Anúncio não encontrado.');
    }

    return array(
        'id' => $anuncio['ad_id'],
        'title' => $anuncio['title'],
        'description' => $anuncio['description'],
        'service_type' => $anuncio['service_type'],
        'price' => $anuncio['price'],
        'price_period' => $anuncio['price_period'],
        'username' => $anuncio['username'],
        'animals' => getAnuncioAnimals($db, $anuncio['ad_id'])
    );
}
function getAnuncioAnimals(PDO $db, int $adId): array {
    $stmt = $db->prepare('
        SELECT at.animal_name
        FROM Ad_animals aa
        JOIN Animal_types at ON aa.animal_id = at.animal_id
        WHERE aa.ad_id = ?
    ');
    $stmt->execute([$adId]);

    $animals = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $animals[] = $row['animal_name'];
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
