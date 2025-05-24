<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$db = getDatabaseConnection();

$isAdmin = User::isUserAdmin($db, (int)$_SESSION['user_id']);
if (!$isAdmin) {
    header('Location: /index.php?error=' . urlencode('Acesso negado. Apenas administradores podem eliminar utilizadores.'));
    exit;
}

if (!isset($_POST['user_id'])) {
    header('Location: /pages/admin.php?tab=users&error=' . urlencode('ID do utilizador não especificado para eliminação.'));
    exit;
}

$userIdToDelete = (int)$_POST['user_id'];
$adminUserId = (int)$_SESSION['user_id'];

if ($userIdToDelete === $adminUserId) {
    header('Location: /pages/admin.php?tab=users&error=' . urlencode('Não pode eliminar a sua própria conta através do painel de administração.'));
    exit;
}

try {

    if (User::deleteUser($db, $userIdToDelete)) {
        header('Location: /pages/admin.php?tab=users&success=' . urlencode('Utilizador eliminado com sucesso!'));
        exit;
    } else {
        header('Location: /pages/admin.php?tab=users&error=' . urlencode('Falha ao eliminar o utilizador.'));
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro ao eliminar utilizador: " . $e->getMessage());
    header('Location: /pages/admin.php?tab=users&error=' . urlencode('Erro na base de dados ao tentar eliminar o utilizador.'));
    exit;
}

?>
