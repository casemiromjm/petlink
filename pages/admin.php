<?php
declare(strict_types = 1);

session_start();

require_once('../database/connection.db.php');
require_once('../database/user.db.php'); 
require_once('../database/animal.db.php');
require_once('../templates/common.php');
require_once('../templates/sidebar.php');
require_once('../templates/admin.php');

$db = getDatabaseConnection();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$userId = $_SESSION['user_id'];

if (!isUserAdmin($db, $userId)) {
    header('Location: ../index.php?error=' . urlencode('Acesso negado. Apenas administradores podem aceder a esta página.'));
    exit;
}

$users = getAllUsers($db);

$animalTypes = getAnimalSpecies($db);

drawHeader();
?>
<body>
    <div class="main-layout">
        <aside class="side-nav">
            <?php drawNavbar(); ?>
        </aside>

        <main class="content">
            <?php
            if (isset($_GET['success'])) {
                echo '<p class="message success">' . htmlspecialchars($_GET['success']) . '</p>';
            }
            if (isset($_GET['error'])) {
                echo '<p class="message error">' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>
            <?php drawAdminPanel($users, $animalTypes); ?>
        </main>
    </div>
</body>
<?php
drawFooter();
?>
