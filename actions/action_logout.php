<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../init.php');

session_unset();
session_destroy();

header('Location: ../index.php');
exit;
?>
