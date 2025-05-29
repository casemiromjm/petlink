<?php

declare(strict_types = 1);

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/users.class.php');

$db = getDatabaseConnection();

$filename = 'relatorioUsers_' . date('Y-m-d_H-i-s') . '.csv';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Username', 'Name', 'Admin']);

$users = User::getAllUsers($db);
foreach ($users as $user) {
    fputcsv($output, [ $user['user_id'], $user['username'], $user['name'], $user['is_admin']]);
}


fclose($output);
exit;
?>
