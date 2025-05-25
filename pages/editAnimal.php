<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../templates/layout.php');
require_once(__DIR__ . '/../templates/editAnimal.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../security.php');
require_once(__DIR__ . '/../init.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$csrf_token = generate_csrf_token();
$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];
$animalId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$animalId) {
    die('Animal não especificado.' . ($_GET['id'] ?? 'Nenhum'));
}

$stmt = $db->prepare('SELECT animal_id, * FROM User_animals WHERE user_id = ? AND animal_id = ?');
$stmt->execute([$userId, $animalId]);
$animal = $stmt->fetch();

if (!$animal) {
    die('Animal não encontrado.');
}

$speciesStmt = $db->query('SELECT animal_id, animal_name FROM Animal_types');
$speciesList = $speciesStmt->fetchAll();

drawHeader();
drawEditAnimal($csrf_token);
drawFooter();
?>
