<?php
declare(strict_types = 1);
function getAnuncios(
    PDO $db,
    int $page = 1,
    int $limit = 16,
    string $location = '',
    string $search = '',
    string $duracao = '',
    string $animal = '',
    string $servico = '',
    string $sort = 'recomendados',
    ?int $userId = null
): array {
    $offset = ($page - 1) * $limit;
    $query = '
        SELECT
            Ads.ad_id as id,
            Ads.title,
            Ads.description,
            Ads.price,
            Ads.price_period,
            Ads.freelancer_id,
            Users.user_id,
            Users.username,
            Users.name,
            Users.district,
            Users.photo_id
        FROM Ads
        JOIN Users ON Ads.freelancer_id = Users.user_id
    ';
    $params = [];
    $conditions = [];

    if ($location !== '') {
        $conditions[] = 'LOWER(Users.district) = LOWER(:location)';
        $params[':location'] = $location;
    }
    if ($search !== '') {
        $words = preg_split('/\s+/', trim($search));
        $wordConds = [];
        $i = 0;
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $param = ":search_word_$i";
                $wordConds[] = "(Ads.title LIKE $param OR Ads.description LIKE $param)";
                $params[$param] = '%' . $word . '%';
                $i++;
            }
        }
        if (count($wordConds) > 0) {
            $conditions[] = '(' . implode(' OR ', $wordConds) . ')';
        }
    }
    if ($duracao !== '') {
        $conditions[] = 'Ads.price_period = :duracao';
        $params[':duracao'] = $duracao;
    }
    if ($animal !== '') {
        $query .= ' JOIN Ad_animals aa ON Ads.ad_id = aa.ad_id JOIN Animal_types at ON aa.animal_id = at.animal_id ';
        $conditions[] = 'at.animal_name = :animal';
        $params[':animal'] = $animal;
    }
    if ($servico !== '') {
        $query .= ' JOIN Ad_services asv ON Ads.ad_id = asv.ad_id JOIN Services st ON asv.service_id = st.service_id ';
        $conditions[] = 'st.service_name = :servico';
        $params[':servico'] = $servico;
    }
    if ($userId !== null) {
        $conditions[] = 'Users.user_id = :user_id';
        $params[':user_id'] = $userId;
    }
    if (count($conditions) > 0) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    // Sorting
    switch ($sort) {
        case 'preco_asc':
            $orderBy = 'Ads.price ASC';
            break;
        case 'preco_desc':
            $orderBy = 'Ads.price DESC';
            break;
        case 'recentes':
            $orderBy = 'Ads.ad_id DESC';
            break;
        default:
            $orderBy = 'Ads.ad_id DESC';
            break;
    }

    $query .= ' ORDER BY ' . $orderBy . ' LIMIT :limit OFFSET :offset';
    $params[':limit'] = $limit;
    $params[':offset'] = $offset;

    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        if ($key === ':limit' || $key === ':offset') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->execute();
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Attach media_ids as before
    $adIds = array_column($ads, 'id');
    if (count($adIds) > 0) {
        $mediaQuery = '
            SELECT am.ad_id, am.media_id
            FROM Ad_media am
            WHERE am.ad_id IN (' . implode(',', array_fill(0, count($adIds), '?')) . ')
            ORDER BY am.ad_id, am.media_id
        ';
        $mediaStmt = $db->prepare($mediaQuery);
        $mediaStmt->execute($adIds);
        $adMedia = $mediaStmt->fetchAll(PDO::FETCH_ASSOC);

        $mediaByAdId = [];
        foreach ($adMedia as $media) {
            $adId = $media['ad_id'];
            $mediaByAdId[$adId][] = $media['media_id'];
        }
        foreach ($ads as &$ad) {
            $ad['media_ids'] = $mediaByAdId[$ad['id']] ?? [];
        }
        unset($ad);
    }

    return $ads;
}

function getTotalAdCount(
    PDO $db,
    string $location = '',
    string $search = '',
    string $duracao = '',
    string $animal = '',
    string $servico = ''
): int {
    $query = 'SELECT COUNT(DISTINCT Ads.ad_id) FROM Ads JOIN Users ON Ads.freelancer_id = Users.user_id';
    $params = [];
    $conditions = [];

    if ($location !== '') {
        $conditions[] = 'LOWER(Users.district) = LOWER(:location)';
        $params[':location'] = $location;
    }
    if ($search !== '') {
        $words = preg_split('/\s+/', trim($search));
        $wordConds = [];
        $i = 0;
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $param = ":search_word_$i";
                $wordConds[] = "(Ads.title LIKE $param OR Ads.description LIKE $param)";
                $params[$param] = '%' . $word . '%';
                $i++;
            }
        }
        if (count($wordConds) > 0) {
            $conditions[] = '(' . implode(' OR ', $wordConds) . ')';
        }
    }
    if ($duracao !== '') {
        $conditions[] = 'Ads.price_period = :duracao';
        $params[':duracao'] = $duracao;
    }
    if ($animal !== '') {
        $query .= ' JOIN Ad_animals aa ON Ads.ad_id = aa.ad_id JOIN Animal_types at ON aa.animal_id = at.animal_id ';
        $conditions[] = 'at.animal_name = :animal';
        $params[':animal'] = $animal;
    }
    if ($servico !== '') {
        $query .= ' JOIN Ad_services asv ON Ads.ad_id = asv.ad_id JOIN Services st ON asv.service_id = st.service_id ';
        $conditions[] = 'st.service_name = :servico';
        $params[':servico'] = $servico;
    }
    if (count($conditions) > 0) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return (int)$stmt->fetchColumn();
}


function getAnuncio(PDO $db, int $ad_id): array {
    $stmt = $db->prepare('
        SELECT
            Ads.ad_id,
            Ads.service_id,
            Ads.freelancer_id,
            Ads.title,
            Ads.description,
            Ads.price,
            Ads.price_period
        FROM Ads
        JOIN Users ON Ads.freelancer_id = Users.user_id
        WHERE Ads.ad_id = ?
    ');
    $stmt->execute([$ad_id]);
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$ad) {
        throw new Exception('Anúncio não encontrado.');
    }

    $mediaIds = []; $mediaQuery = '
        SELECT media_id
        FROM Ad_media
        WHERE ad_id = ?
        ORDER BY media_id
    ';
    $mediaStmt = $db->prepare($mediaQuery);
    $mediaStmt->execute([$ad_id]);
    $adMedia = $mediaStmt->fetchAll(PDO::FETCH_COLUMN);
    if ($adMedia !== false) {
        $mediaIds = $adMedia;

    }
    $ad= [
        'id' => $ad['ad_id'],
        'title' => $ad['title'],
        'description' => $ad['description'],
        'service_id' => $ad['service_id'],
        'price' => $ad['price'],
        'price_period' => $ad['price_period'],
        'freelancer_id' => $ad['freelancer_id'],
        'animals' =>getAnuncioAnimals($db, $ad['ad_id']),
        'mediaIds' => $mediaIds
    ];
    return $ad;
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
            Ads.*,
            Ads.ad_id as id,
            Users.username,
            Users.user_id,
            Users.district,
            Users.photo_id
        FROM Ads
        JOIN Users ON Ads.freelancer_id = Users.user_id
        WHERE Ads.ad_id = ?
    ');
    $stmt->execute([$id]);
    $adData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adData === false) {
        return null;
    }

    $mediaIds = [];

    $mediaQuery = '
        SELECT
        AM.media_id
        FROM Ad_media AM
        WHERE AM.ad_id = ?
    ';
    $mediaStmt = $db->prepare($mediaQuery);
    $mediaStmt->execute([$id]);

    $adMedia = $mediaStmt->fetchAll(PDO::FETCH_COLUMN);
    if ($adMedia !== false) {
        $mediaIds = $adMedia;
    }

    $ad = [
        'id' => $adData['ad_id'],
        'title' => $adData['title'] ?? '',
        'description' => $adData['description'] ?? '',
        'service_id' => $adData['service_id'],
        'price' => $adData['price'],
        'price_period' => $adData['price_period'] ?? '',
        'freelancer_id' => $adData['freelancer_id'],
        'animals' => getAnuncioAnimals($db, $adData['ad_id']),
        'mediaIds' => $mediaIds,
        'username' => $adData['username'] ?? '',
        'user_id' => $adData['user_id'],
        'district' => $adData['district'] ?? '',
        'photo_id' => $adData['photo_id'] ?? ''
    ];

    return $ad;
}
?>
