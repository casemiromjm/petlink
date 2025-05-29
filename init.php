<?php
declare(strict_types = 1);

require_once(__DIR__ . '/database/connection.db.php');
require_once(__DIR__ . '/utils/security.php');

/**
 * Wrapper for session_start()
 */
function init() : void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

?>
