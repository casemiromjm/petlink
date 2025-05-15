<?php
declare(strict_types = 1);
function getAnuncios(PDO $db, int $page = 1, int $limit = 16): array {
    $offset = ($page - 1) * $limit;
    $query = '
        SELECT
            ads.ad_id as id,
            ads.title,
            ads.description,
            ads.price,
            ads.price_period,
            ads.image_path,
            users.username,
            users.name,
            users.district,
            users.profile_photo
        FROM ads
        JOIN users ON ads.username = users.username
        ORDER BY ads.ad_id DESC
        LIMIT :limit OFFSET :offset
    ';

    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
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
      SELECT
        ads.*,
        ads.image_path,
        users.username,
        users.name,
        users.district,
        users.profile_photo,
        users.created_at
      FROM ads
      JOIN users ON ads.username = users.username
      WHERE ads.ad_id = ?
    ');
    $stmt->execute([$id]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    return $ad ?: null;
}
?>
