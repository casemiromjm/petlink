<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/userprofile.php');
require_once('../database/connection.db.php');
require_once(__DIR__ . '/../database/db.anuncios.php');

$db = getDatabaseConnection();

$username = $_GET['username'] ?? null;
if (!$username) {
  die('Utilizador não especificado.');
}

// Buscar dados do utilizador
$stmt = $db->prepare('SELECT * FROM Users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
  die('Utilizador não encontrado.');
}

// Buscar animais do utilizador
$stmt = $db->prepare('
  SELECT ua.name, ua.age, at.animal_name, ua.animal_picture
  FROM user_animals ua
  JOIN Animal_types at ON ua.species = at.animal_id
  WHERE ua.user_id = ?
');
$stmt->execute([$user['user_id']]);
$animals = $stmt->fetchAll();

// Buscar reviews do utilizador (como freelancer)
$stmt = $db->prepare('
  SELECT rating, comment, created_at, client_username
  FROM Reviews
  WHERE freelancer_username = ?
  ORDER BY created_at DESC
');
$stmt->execute([$username]);
$reviews = $stmt->fetchAll();

// Buscar anúncios do utilizador
$stmt = $db->prepare('
  SELECT ads.ad_id, ads.title, ads.image_path, ads.price, ads.price_period
  FROM Ads ads
  WHERE ads.username = ?
  ORDER BY ads.created_at DESC
');
$stmt->execute([$username]);
$userAds = $stmt->fetchAll();

drawHeader();
drawUserProfile($user, $animals, $reviews, $userAds, $db);
drawFooter();
?>