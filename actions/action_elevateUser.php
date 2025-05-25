<?php
declare(strict_types=1);
require_once(__DIR__ . '/../init.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$db = getDatabaseConnection();

$isAdmin = User::isUserAdmin($db, (int)$_SESSION['user_id']);
if (!$isAdmin) {
    header('Location: /index.php?error=' . urlencode('Acesso negado. Apenas administradores podem aceder a esta página.'));
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'toggle_admin') {
    $userIdToUpdate = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $currentAdminStatus = filter_input(INPUT_POST, 'current_status', FILTER_VALIDATE_INT);

    $newAdminStatus = ($currentAdminStatus === 1) ? 0 : 1;


    if ($userIdToUpdate === false || $userIdToUpdate === null || $userIdToUpdate <= 0 ||
        ($newAdminStatus !== 0 && $newAdminStatus !== 1)) {
        header('Location: /pages/admin.php?tab=users&error=' . urlencode('Dados inválidos para atualizar o status do utilizador.'));
        exit;
    }

    if ($userIdToUpdate === (int)$_SESSION['user_id']) {
        header('Location: /pages/admin.php?tab=users&error=' . urlencode('Não pode alterar o seu próprio status de administrador.'));
        exit;
    }

    try {
        if (User::updateUserAdminStatus($db, $userIdToUpdate, $newAdminStatus)) {
            header('Location: /pages/admin.php?tab=users&success=' . urlencode('Status de administrador atualizado com sucesso!'));
            exit;
        } else {
            header('Location: /pages/admin.php?tab=users&error=' . urlencode('Falha ao atualizar o status do utilizador. O utilizador pode não existir.'));
            exit;
        }
    }   catch (PDOException $e) {
        error_log("Erro PDO ao atualizar status do utilizador: " . $e->getMessage());
        header('Location: /pages/admin.php?tab=users&error=' . urlencode('Erro na base de dados ao atualizar o status do utilizador.'));
        exit;
    }

} else {
    header('Location: /pages/admin.php?tab=users&error=' . urlencode('Ação inválida.'));
    exit;
}
?>
