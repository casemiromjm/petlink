<?php
declare(strict_types=1);
require_once(__DIR__ . '/../init.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/users.class.php');
require_once(__DIR__ . '/../database/service.class.php');
require_once(__DIR__ . '/../utils/security.php');

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log('CSRF token mismatch or missing for adding service. IP: ' . $_SERVER['REMOTE_ADDR']);
    header('Location: ../pages/addService.php?error=csrf');
    exit();
}
unset($_SESSION['csrf_token']);

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$db = getDatabaseConnection();

$isAdmin = User::isUserAdmin($db, (int)$_SESSION['user_id']);
if (!$isAdmin) {
    header('Location: /index.php?error=' . urlencode('Acesso negado. Apenas administradores podem adicionar serviços.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Método de requisição inválido.'));
    exit;
}

if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('O nome do serviço não pode estar vazio.'));
    exit;
}

$serviceName = trim($_POST['name']);

if (strlen($serviceName) > 50) {
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('O nome do serviço é muito longo (máx. 50 caracteres).'));
    exit;
}

try {
    if (Service::addService($db, $serviceName)) {
        header('Location: /pages/admin.php?tab=categories&success=' . urlencode('Serviço "' . htmlspecialchars($serviceName) . '" adicionado com sucesso!'));
        exit;
    } else {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Falha ao adicionar o serviço. Pode já existir.'));
        exit;
    }
} catch (PDOException $e) {
    error_log("Erro PDO ao adicionar serviço: " . $e->getMessage());

    if ($e->getCode() === '23000') {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Este serviço já existe.'));
    } else {
        header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Erro na base de dados ao adicionar o serviço.'));
    }
    exit;
} catch (Exception $e) {

    error_log("Erro inesperado ao adicionar serviço: " . $e->getMessage());
    header('Location: /pages/admin.php?tab=categories&error=' . urlencode('Ocorreu um erro inesperado.'));
    exit;
}
?>
