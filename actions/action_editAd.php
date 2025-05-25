<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../security.php');
require_once(__DIR__ . '/../init.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    die('Invalid request method');
}



if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('CSRF token mismatch or missing for ad edition. IP: ' . $_SERVER['REMOTE_ADDR']);
    header('Location: ../pages/edit_ad.php?error=csrf');
    exit();
}
unset($_SESSION['csrf_token']);

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    die('You must be logged in to edit ads');
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('HTTP/1.1 403 Forbidden');
    die('Invalid CSRF token');
}

$adId = isset($_POST['ad_id']) ? intval($_POST['ad_id']) : null;
if (!$adId) {
    header('HTTP/1.1 400 Bad Request');
    die('Invalid ad ID');
}

$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];

try {
    $db->beginTransaction();

    $stmt = $db->prepare('SELECT freelancer_id FROM Ads WHERE ad_id = ?');
    $stmt->execute([$adId]);
    $ownerId = $stmt->fetchColumn();

    if ($ownerId !== $userId) {
        throw new Exception('You can only edit your own ads');
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $animalTypes = $_POST['animais'] ?? [];
    $current_img = $_POST['ad-picture'] ?? null;

    $imagePath = $current_img;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../resources/';
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['ad-picture']['tmp_name'], $targetPath)) {

            $stmt = $db->prepare('
                INSERT INTO Media (file_name, media_type)
                VALUES (?, ?)
            ');
            $stmt->execute([$fileName, 'image']);
            $newMediaId = $db->lastInsertId();

            $ad_img_path = './resources/adPics' . $fileName . '.png';
        }
    }

    // preciso add a parte do price_period

    $updateAd = $db->prepare('
        UPDATE Ads SET
            title = ?,
            description = ?,
            price = ?
        WHERE ad_id = ?
    ');

    if (!$updateAd->execute([$title, $description, $price, $adId])) {
        throw new Exception('Failed to update ad');
    }

    $db->prepare('DELETE FROM Ad_media WHERE ad_id = ?')->execute([$adId]);

    if ($newMediaId !== null) {
        $insertMedia = $db->prepare('INSERT INTO Ad_media (ad_id, media_id) VALUES (?, ?)');
        if (!$insertMedia->execute([$adId, $newMediaId])) {
            throw new Exception('Failed to associate media with ad');
        }
    }

    $db->prepare('DELETE FROM Ad_animals WHERE ad_id = ?')->execute([$adId]);

    if (!empty($animalTypes)) {
        $insertAnimal = $db->prepare('INSERT INTO Ad_animals (ad_id, animal_id) VALUES (?, ?)');
        foreach ($animalTypes as $animalId) {
            $animalId = filter_var($animalId, FILTER_VALIDATE_INT);
            if ($animalId) {
                if (!$insertAnimal->execute([$adId, $animalId])) {
                    throw new RuntimeException('Failed to update animal types', 500);
                }
            }
        }
    }

    $db->commit();

    $_SESSION['success_message'] = 'Anúncio editado com sucesso';
    header("Location: ../pages/adDetails.php?id=$adId&success=2");
    exit();

} catch (Exception $e) {
    $db->rollBack();
    error_log('Delete ad error: ' . $e->getMessage());
    header('HTTP/1.1 500 Internal Server Error');
    die('Error editing an ad');
}

?>
