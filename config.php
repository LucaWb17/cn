<?php
define('DB_SERVER', 'localhost'); // Or your DB server IP/hostname
define('DB_USERNAME', 'root');    // Your MySQL username
define('DB_PASSWORD', '');        // Your MySQL password
define('DB_NAME', 'cn_booking_app'); // Your database name

// Attempt to connect to MySQL database
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Base URL for the application (helps with redirects and links)
// Detect if HTTPS is used
$is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
$protocol = $is_https ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
// For local development, you might need to adjust SCRIPT_NAME if using subdirectories
$script_path = dirname($_SERVER['SCRIPT_NAME']);
// Ensure script_path doesn't become just '/' if in root, or handle it appropriately
if ($script_path === '/' || $script_path === '\\') {
    $script_path = '';
}
define('BASE_URL', $protocol . $host . $script_path);

// Error reporting (for development)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Basic input sanitization function (can be expanded)
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Password hashing (using PHP's built-in functions)
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hashed_password) {
    return password_verify($password, $hashed_password);
}

// Redirect function
function redirect($url) {
    header("Location: " . $url);
    exit;
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Email Configuration
define('ADMIN_EMAIL', 'admin@example.com'); // Replace with your admin email
define('FROM_EMAIL', 'noreply@example.com'); // Replace with your desired "From" email
define('FROM_NAME', 'CN Auto Booking');     // Replace with your desired "From" name

// SMTP Configuration for PHPMailer
define('SMTP_HOST', 'mail.example.com');      // Your SMTP server hostname (e.g., smtp.netsons.com)
define('SMTP_USERNAME', 'your_email@example.com'); // Your SMTP username (full email address)
define('SMTP_PASSWORD', 'your_smtp_password'); // Your SMTP password
define('SMTP_PORT', 465);                         // SMTP port (e.g., 465 for SSL, 587 for TLS)
define('SMTP_SECURE', 'ssl');                   // SMTP encryption type ('ssl' or 'tls')

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';

?>
