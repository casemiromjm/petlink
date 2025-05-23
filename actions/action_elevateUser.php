<?php
declare(strict_types = 1);

session_start();

require_once('../database/connection.db.php');
require_once('../database/user.db.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDatabaseConnection();

        if (!isset($_SESSION['user_id']) || !isUserAdmin($db, $_SESSION['user_id'])) {
            header('Location: ../pages/login.php?error=' . urlencode('Acesso negado. Apenas administradores podem realizar esta ação.'));
            exit;
        }

        $userIdToUpdate = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $newAdminStatus = filter_input(INPUT_POST, 'new_admin_status', FILTER_VALIDATE_INT);

        if ($userIdToUpdate === false || $userIdToUpdate === null || $userIdToUpdate <= 0 ||
            ($newAdminStatus !== 0 && $newAdminStatus !== 1)) {
            header('Location: ../pages/admin.php?error=' . urlencode('Dados inválidos para atualizar o status do utilizador.'));
            exit;
        }

        if ($userIdToUpdate === $_SESSION['user_id'] && $newAdminStatus === 0) {
            header('Location: ../pages/admin.php?error=' . urlencode('Não pode remover o seu próprio status de administrador.'));
            exit;
        }

        if (updateUserAdminStatus($db, $userIdToUpdate, $newAdminStatus)) {
            $message = ($newAdminStatus === 1) ? 'Utilizador elevado a administrador com sucesso!' : 'Status de administrador removido com sucesso!';
            header('Location: ../pages/admin.php?success=' . urlencode($message));
            exit;
        } else {
            header('Location: ../pages/admin.php?error=' . urlencode('Erro ao atualizar o status do utilizador.'));
            exit;
        }

    } catch (PDOException $e) {
        error_log("Erro de PDO ao elevar/rebaixar utilizador: " . $e->getMessage());
        header('Location: ../pages/admin.php?error=' . urlencode('Ocorreu um erro na base de dados.'));
        exit;
    } catch (Exception $e) {
        error_log("Erro inesperado ao elevar/rebaixar utilizador: " . $e->getMessage());
        header('Location: ../pages/admin.php?error=' . urlencode('Ocorreu um erro inesperado.'));
        exit;
    }
} else {
    header('Location: ../pages/admin.php');
    exit;
}
?>
