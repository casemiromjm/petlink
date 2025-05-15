<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/sidebar.php');
require_once('../templates/animals.php');
require_once('../database/connection.db.php');

session_start();

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