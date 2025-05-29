<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../templates/layout.php');
require_once(__DIR__ . '/../templates/edit_ad.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/security.php');
require_once(__DIR__ . '/../init.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$csrf_token = generate_csrf_token();

drawHeader();
drawEditAd($csrf_token);
drawFooter();
?>
