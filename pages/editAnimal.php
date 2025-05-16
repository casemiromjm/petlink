<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/editAnimal.php');
require_once('../database/connection.db.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];
$animalId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$animalId) {
    die('Animal não especificado.');
}

$stmt = $db->prepare('SELECT rowid, * FROM user_animals WHERE user_id = ? AND rowid = ?');
$stmt->execute([$userId, $animalId]);
$animal = $stmt->fetch();

if (!$animal) {
    die('Animal não encontrado.');
}

$speciesStmt = $db->query('SELECT animal_id, animal_name FROM Animal_types');
$speciesList = $speciesStmt->fetchAll();

drawHeader();
drawEditAnimal($animal, $speciesList);
drawFooter();
?>