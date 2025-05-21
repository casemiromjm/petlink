<?php
declare(strict_types = 1);
function getAnuncios(PDO $db, int $page = 1, int $limit = 16, string $location = '', string $search = '', string $duracao = '', string $animal = '', string $servico = '', string $sort = 'recomendados'): array {
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
    ';
    $params = [];
    $conditions = [];
    if ($location !== '') {
        $conditions[] = 'LOWER(users.district) = LOWER(:location)';
        $params[':location'] = $location;
    }
    if ($search !== '') {
        $words = preg_split('/\s+/', trim($search));
        $wordConds = [];
        $i = 0;
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $param = ":search_word_$i";
                $wordConds[] = "(ads.title LIKE $param OR ads.description LIKE $param)";
                $params[$param] = '%' . $word . '%';
                $i++;
            }
            // Sao mostrados todos os posts que tenham pelo menos uma das palavras escritas na search bar, mas estas têm de ser maiores que
            // 2 caracteres, para não incluir palavras como "de" "em" etc que iam dar muitos resultados irrelevantes
        }
        if (count($wordConds) > 0) {
            $conditions[] = '(' . implode(' OR ', $wordConds) . ')';
        }
    }
    if ($duracao !== '') {
        $conditions[] = 'ads.price_period = :duracao';
        $params[':duracao'] = $duracao;
    }
    if ($animal !== '') {
        $query .= ' JOIN Ad_animals aa ON ads.ad_id = aa.ad_id JOIN Animal_types at ON aa.animal_id = at.animal_id ';
        $conditions[] = 'at.animal_name = :animal';
        $params[':animal'] = $animal;
    }
    if ($servico !== '') {
        $query .= ' JOIN Ad_services asv ON ads.ad_id = asv.ad_id JOIN Services st ON asv.service_id = st.service_id ';
        $conditions[] = 'st.service_name = :servico';
        $params[':servico'] = $servico;
    }
    if (count($conditions) > 0) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }

    // Add sorting
    switch ($sort) {
        case 'preco_asc':
            $orderBy = 'ads.price ASC';
            break;
        case 'preco_desc':
            $orderBy = 'ads.price DESC';
            break;
        case 'recentes':
            $orderBy = 'ads.created_at DESC';
            break;
        case 'avaliacoes':
            $orderBy = 'ads.rating DESC, ads.created_at DESC';
            break;
        case 'recomendados':
        default:
            $orderBy = 'ads.created_at DESC';
            break;
    }

    $query .= ' ORDER BY ' . $orderBy . ' LIMIT :limit OFFSET :offset';
    $params[':limit'] = $limit;
    $params[':offset'] = ($page - 1) * $limit;

    $stmt = $db->prepare($query);
    foreach ($params as $key => $value) {
        if ($key === ':limit' || $key === ':offset') {
            $stmt->bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($key, $value);
        }
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalAdCount(PDO $db, string $location = '', string $search = '', string $duracao = '', string $animal = '', string $servico = ''): int {
    $query = 'SELECT COUNT(*) FROM ads JOIN users ON ads.username = users.username';
    $params = [];
    $conditions = [];

    if ($location !== '') {
        $conditions[] = 'LOWER(users.district) = LOWER(:location)';
        $params[':location'] = $location;
    }

    if ($search !== '') {
        $words = preg_split('/\s+/', trim($search));
        $wordConds = [];
        $i = 0;
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $param = ":search_word_$i";
                $wordConds[] = "(ads.title LIKE $param OR ads.description LIKE $param)";
                $params[$param] = '%' . $word . '%';
                $i++;
            }
        }
        if (count($wordConds) > 0) {
            $conditions[] = '(' . implode(' OR ', $wordConds) . ')';
        }
    }

    if ($duracao !== '') {
        $conditions[] = 'ads.price_period = :duracao';
        $params[':duracao'] = $duracao;
    }

    if ($animal !== '') {
        $query .= ' JOIN Ad_animals aa ON ads.ad_id = aa.ad_id JOIN Animal_types at ON aa.animal_id = at.animal_id ';
        $conditions[] = 'at.animal_name = :animal';
        $params[':animal'] = $animal;
    }

    if ($servico !== '') {
        $query .= ' JOIN Ad_services asv ON ads.ad_id = asv.ad_id JOIN Services st ON asv.service_id = st.service_id ';
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
    return $stmt->fetchColumn();
}

function getAnuncio(PDO $db, int $ad_id): array {
    $stmt = $db->prepare('
        SELECT Ads.ad_id, Ads.title, Ads.description, Ads.price, Ads.price_period, Ads.username
        FROM Ads
        JOIN Users ON Ads.username = Users.username
        WHERE Ads.ad_id = ?
    ');
    $stmt->execute([$ad_id]);

    $anuncio = $stmt->fetch();

    if (!$anuncio) {
        throw new Exception('Anúncio não encontrado.');
    }

    return [
        'id' => $anuncio['ad_id'],
        'title' => $anuncio['title'],
        'description' => $anuncio['description'],
        'service_type' => $anuncio['service_type'],
        'price' => $anuncio['price'],
        'price_period' => $anuncio['price_period'],
        'username' => $anuncio['username'],
        'animals' => getAnuncioAnimals($db, $anuncio['ad_id'])
    ];
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
        users.user_id,
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