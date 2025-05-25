<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/editAnimal.php');
require_once('../database/connection.db.php');

session_start();

drawHeader();
drawEditAnimal();
drawFooter();
?>