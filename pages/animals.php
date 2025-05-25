<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../templates/layout.php');
require_once(__DIR__ . '/../templates/sidebar.php');
require_once(__DIR__ . '/../templates/animals.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../init.php');


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = getDatabaseConnection();
$userId = $_SESSION['user_id'];

drawHeader();
?>
<body>
    <div class="animals-layout">
        <aside class="side-nav">
            <?php drawNavbar(); ?>
        </aside>

        <main class="animals-content">
            <?php drawAnimals($db, $userId); ?>
        </main>
    </div>
</body>
<?php
drawFooter();
?>
