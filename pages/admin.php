<?php
declare(strict_types = 1);

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/users.class.php');
require_once(__DIR__.'/../database/animal.class.php');
require_once(__DIR__.'/../templates/admin.php');
require_once(__DIR__.'/../templates/layout.php');

$db = getDatabaseConnection();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?message=' . urlencode('Acesso restrito. Por favor, faça login.'));
    exit;
}

$userId = $_SESSION['user_id'];

if (!User::isUserAdmin($db, $userId)) {
    header('Location: ../index.php?error=' . urlencode('Acesso negado. Apenas administradores podem aceder a esta página.'));
    exit;
}

$users = User::getAllUsers($db);
$animalTypes = Animal_type::getAnimalSpecies($db);
$currentTab = $_GET['tab'] ?? 'users';

$overview = [
    'total_users' => $users,
    'total_animals' => $animalTypes,
    'recent_activity' => [],
];

drawHeader();
?>

<body>
    <div class="main-layout" style="display: flex;">
        <aside class="side-nav">
        <ul>
            <li class="<?= ($currentTab === 'users') ? 'active' : '' ?>">
                <a href="/pages/admin.php?tab=users">Gerir Utilizadores</a>
            </li>

            <li class="<?= ($currentTab === 'categories') ? 'active' : '' ?>">
                <a href="/pages/admin.php?tab=categories">Gerir Categorias</a>
            </li>

            <li class="<?= ($currentTab === 'overview') ? 'active' : '' ?>">
                <a href="/pages/admin.php?tab=overview">Visão Geral do Sistema</a>
            </li>

            </ul>
        </aside>

        <main class="content" >
            <?php
            if (isset($_GET['success'])) {
                echo '<p class="message success">' . htmlspecialchars($_GET['success']) . '</p>';
            }
            if (isset($_GET['error'])) {
                echo '<p class="message error">' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>
            <?php drawAdminPanel($currentTab, $users, $animalTypes, $overview); ?>
        </main>
    </div>
</body>
<?php
drawFooter();
?>
