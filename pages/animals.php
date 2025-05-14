<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/sidebar.php');
require_once('../templates/animals.php');
require_once('../database/connection.db.php');

$db = getDatabaseConnection();
$userId = 1; // Replace with the actual logged-in user's ID

drawHeader();
drawNavbar();
drawAnimals($db, $userId);
drawFooter();
?>