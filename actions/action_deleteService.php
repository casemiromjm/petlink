<?php
declare(strict_types=1);
session_start();

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/service.class.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$db = getDatabaseConnection();

$isAdmin = User::isUserAdmin($db, (int)$_SESSION['user_id']);
if (!$isAdmin) {
    header('Location: /index.php?error=' . urlencode('Acesso negado. Apenas administradores podem eliminar serviços.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Método de requisição inválido.'));
    exit;
}

if (!isset($_POST['service_id'])) {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('ID do serviço não especificado para eliminação.'));
    exit;
}

$serviceIdToDelete = filter_input(INPUT_POST, 'service_id', FILTER_VALIDATE_INT);

if ($serviceIdToDelete === false || $serviceIdToDelete === null || $serviceIdToDelete <= 0) {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('ID do serviço inválido para eliminação.'));
    exit;
}

try {
    if (Service::deleteService($db, $serviceIdToDelete)) {
        header('Location: /pages/admin.php?tab=categories&success=' . urlencode('Serviço eliminado com sucesso!'));
        exit;
    } else {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Falha ao eliminar o serviço. O serviço pode não existir.'));
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro PDO ao eliminar serviço: " . $e->getMessage());
    if ($e->getCode() === '23000') {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Não foi possível eliminar o serviço. Existem anúncios ou pets associados a este serviço.'));
    } else {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Erro na base de dados ao eliminar o serviço.'));
    }
    exit;
} catch (Exception $e) {
    error_log("Erro inesperado ao eliminar serviço: " . $e->getMessage());
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Ocorreu um erro inesperado.'));
    exit;
}
?>
