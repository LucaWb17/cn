<?php
// Initialize the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If config.php is not already included, include it.
if (!defined('BASE_URL')) {
    // Adjust the path as necessary if this file is moved
    require_once 'config.php';
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
if (session_destroy()) {
    // Optionally, also destroy the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}

// Redirect to login page or home page
redirect(BASE_URL . "/home.php?message=You have been logged out successfully.");
exit;
?>
