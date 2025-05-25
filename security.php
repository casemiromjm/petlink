<?php
// security.php
declare(strict_types = 1);

function generate_csrf_token(): string {
    // Check if session is active before trying to access $_SESSION
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("CRITICAL ERROR: generate_csrf_token called when session is NOT active!");
        // Fallback: If session isn't active, generate a dummy token (this shouldn't happen if init.php is correct)
        return 'dummy_token_session_inactive';
    }

    if (empty($_SESSION['csrf_token'])) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        error_log("DEBUG: CSRF - NEW token generated and stored: " . $token . " (from " . debug_backtrace()[0]['file'] . ":" . debug_backtrace()[0]['line'] . ")");
    } else {
        error_log("DEBUG: CSRF - Using EXISTING token from session: " . $_SESSION['csrf_token'] . " (from " . debug_backtrace()[0]['file'] . ":" . debug_backtrace()[0]['line'] . ")");
    }
    return $_SESSION['csrf_token'];
}

// You might also have a validation function here, something like:
function validate_csrf_token(string $postedToken): bool {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        error_log("CRITICAL ERROR: validate_csrf_token called when session is NOT active!");
        return false;
    }

    $sessionToken = $_SESSION['csrf_token'] ?? null;

    // Log the values *before* comparison
    error_log("DEBUG: CSRF Validation - Posted: [" . $postedToken . "]");
    error_log("DEBUG: CSRF Validation - Session: [" . ($sessionToken ?? 'NULL') . "]");
    error_log("DEBUG: CSRF Validation - Match? " . ($postedToken === $sessionToken ? 'YES' : 'NO'));

    // Clear the token regardless of validation outcome to prevent replay attacks
    unset($_SESSION['csrf_token']); // IMPORTANT: Do this *after* logging the session token

    return ($postedToken !== null && $postedToken === $sessionToken);
}

?>
