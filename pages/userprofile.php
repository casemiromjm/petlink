<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../templates/layout.php');
require_once(__DIR__ . '/../templates/userprofile.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/anuncios.class.php');

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
  SELECT r.rating, r.comment, r.created_at, u.username AS client_username
  FROM Reviews r
  JOIN Users u ON r.client_id = u.user_id
  WHERE r.ad_id IN (SELECT ad_id FROM Ads WHERE freelancer_id = ?)
  ORDER BY r.created_at DESC
');
$stmt->execute([$user['user_id']]);
$reviews = $stmt->fetchAll();

// Buscar anúncios do utilizador
$stmt = $db->prepare('
  SELECT ads.ad_id, ads.title, ads.price, ads.price_period
  FROM Ads ads
  JOIN Users u ON ads.freelancer_id = u.user_id
  WHERE u.username = ?
  ORDER BY ads.created_at DESC
');
$stmt->execute([$username]);
$userAds = $stmt->fetchAll();

drawHeader();
drawUserProfile($user, $animals, $reviews, $userAds, $db);
drawFooter();
?>
