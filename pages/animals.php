<?php
declare(strict_types = 1);

require_once('../templates/layout.php');
require_once('../templates/sidebar.php');
require_once('../templates/animals.php');
require_once('../database/connection.db.php');

$db = getDatabaseConnection();
$userId = 1; // Replace with the actual logged-in user's ID

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