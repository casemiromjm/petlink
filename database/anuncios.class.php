<?php
declare(strict_types = 1);
function getAnuncios(PDO $db, int $page = 1, int $limit = 16): array {
    $offset = ($page - 1) * $limit;
    $query = '
        SELECT
            Ads.ad_id as id,
            Ads.title,
            Ads.description,
            Ads.price,
            Ads.price_period,
            Users.user_id,
            Users.username,
            Users.name,
            Users.district,
            Users.photo_id
        FROM Ads
        JOIN Users ON Ads.freelancer_id = Users.user_id
        ORDER BY Ads.ad_id DESC
        LIMIT :limit OFFSET :offset
    ';

    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $ads = $stmt->fetchAll();
    $adIds = array_column($ads, 'id');
        $mediaQuery = '
            SELECT
                am.ad_id,
                am.media_id
            FROM Ad_media am
            WHERE am.ad_id IN (' . implode(',', array_fill(0, count($adIds), '?')) . ')
            ORDER BY am.ad_id, am.media_id
        ';

        $mediaStmt = $db->prepare($mediaQuery);
        $mediaStmt->execute($adIds);
        $adMedia = $mediaStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($adMedia === false)
        {
            return $ads;
        }

        $mediaByAdId = [];
        foreach ($adMedia as $media) {
            $adId = $media['ad_id'];
            $mediaByAdId[$adId][] = $media['media_id'];
        }

        foreach ($ads as &$ad) {
            $ad['media_ids'] = $mediaByAdId[$ad['id']] ?? [];
        }
        unset($ad);

        return $ads;
}

function getTotalAdCount(PDO $db): int {
    $query = 'SELECT COUNT(*) FROM Ads';
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchColumn();
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
