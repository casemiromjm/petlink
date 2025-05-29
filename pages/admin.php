<?php
declare(strict_types = 1);

require_once(__DIR__.'/../database/connection.db.php');
require_once(__DIR__.'/../database/users.class.php');
require_once(__DIR__.'/../database/animal.class.php');
require_once(__DIR__.'/../database/service.class.php');
require_once(__DIR__.'/../templates/admin.php');
require_once(__DIR__.'/../templates/layout.php');
require_once(__DIR__ . '/../utils/security.php');


$db = getDatabaseConnection();
$csrf_token = generate_csrf_token();

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
$services = Service::getAllServices($db);
$currentTab = $_GET['tab'] ?? 'users';

$overview = [
    'total_users' => count($users),
    'total_animals' => count($animalTypes),
    'total_services' => count($services),
    'recent_activity' => [],
];

drawHeader();
?>

<body>
    <div class="main-layout" style="display: flex;">
        <aside>
          <nav class="side-nav">
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
          </nav>
        </aside>

        <main class="content" >
            <?php
            if (isset($_GET['success'])) {
                echo '<div class="success-message" id="success-message">' . htmlspecialchars($_GET['success']) . '</div>';
            }
            if (isset($_GET['error'])) {
                echo '<div class="error-bar" id="error-message">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>
            <?php drawAdminPanel($currentTab, $users, $animalTypes, $services, $overview, $csrf_token); ?>
        </main>
    </div>
    <script src="/javascript/visibility.js"></script>
    <script src="/javascript/script.js"></script>
</body>
<?php
drawFooter();
?>
