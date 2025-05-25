<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/edit_ad.php');
require_once('../database/connection.db.php');

session_start();

drawHeader();
drawEditAd();
drawFooter();
?>
