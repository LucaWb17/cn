<?php
// This file is used to protect pages that require a user to be logged in.
// It can be included at the top of such pages.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If config.php is not already included, include it.
// This might happen if auth_check.php is directly accessed or included first.
if (!defined('BASE_URL')) {
    // Adjust the path as necessary if this file is moved
    require_once 'config.php';
}

if (!is_logged_in()) {
    // Store the page they were trying to access to redirect them back after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect(BASE_URL . '/login.php?message=Please login to access this page.');
}

// Specific check for admin pages
function require_admin() {
    if (!is_admin()) {
        // If not an admin, redirect to a non-admin page or show an error
        redirect(BASE_URL . '/areacliente.php?error=Access denied. Admin privileges required.');
    }
}

?>
