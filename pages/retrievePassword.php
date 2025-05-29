<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../templates/layout.php');
require_once(__DIR__ .  '/../templates/retrievePassword.php');
require_once(__DIR__ . '/../utils/security.php');
require_once(__DIR__ . '/../init.php');

// n é tao necessário pq ao gerar, ja é colocado na SESSION
$csrf_token = generate_csrf_token();

drawHeader();
drawRetrievePassword($csrf_token);
drawFooter();
?>
