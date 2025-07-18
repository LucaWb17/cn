<?php
// This file can house general utility functions used across the application.
// config.php already contains some basic ones (sanitize_input, hash_password, etc.)
// We can move them here or add new ones.

if (!defined('BASE_URL')) { // Ensure config.php is loaded
    $configPath = __DIR__ . '/../config.php';
    if (file_exists($configPath)) {
        require_once $configPath;
    } else {
        die("FATAL ERROR: config.php not found from functions.php. Path checked: " . $configPath);
    }
}

// Example: Function to display formatted messages (e.g., success, error)
// This can be used in conjunction with session messages.
function display_flash_message() {
    $message_html = '';
    if (isset($_SESSION['success_message'])) {
        $message_html .= '<div class="flash-message success bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        $message_html .= '<div class="flash-message error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['info_message'])) {
        $message_html .= '<div class="flash-message info bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">' . htmlspecialchars($_SESSION['info_message']) . '</div>';
        unset($_SESSION['info_message']);
    }
    // For messages passed via GET request (less secure, use for non-sensitive info)
    if (isset($_GET['message'])) {
         $message_html .= '<div class="flash-message info bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">' . htmlspecialchars(sanitize_input($_GET['message'])) . '</div>';
    }
     if (isset($_GET['error'])) {
         $message_html .= '<div class="flash-message error bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">' . htmlspecialchars(sanitize_input($_GET['error'])) . '</div>';
    }


    return $message_html;
}


// Function to get services for dropdowns or listings
function get_all_services($mysqli_conn) {
    $services = [];
    $sql = "SELECT id, name, price, duration FROM services ORDER BY name ASC";
    if ($result = $mysqli_conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        $result->free();
    } else {
        // Handle error, e.g., log it or return an empty array with an error flag
        error_log("Error fetching services: " . $mysqli_conn->error);
    }
    return $services;
}

// Function to get user's vehicles for dropdown
function get_user_vehicles($mysqli_conn, $user_id) {
    $vehicles = [];
    $sql = "SELECT id, make, model, year, license_plate FROM vehicles WHERE user_id = ? ORDER BY make ASC, model ASC";
    if ($stmt = $mysqli_conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }
        $stmt->close();
    } else {
        error_log("Error fetching user vehicles: " . $mysqli_conn->error);
    }
    return $vehicles;
}

// Function to format date/time (can be more sophisticated)
function format_datetime_display($datetime_str, $format = "M d, Y h:i A") {
    if (empty($datetime_str) || $datetime_str === '0000-00-00 00:00:00') return 'N/A';
    try {
        $date = new DateTime($datetime_str);
        return $date->format($format);
    } catch (Exception $e) {
        return $datetime_str; // Return original if formatting fails
    }
}

function format_date_display($date_str, $format = "M d, Y") {
    if (empty($date_str) || $date_str === '0000-00-00') return 'N/A';
     try {
        $date = new DateTime($date_str);
        return $date->format($format);
    } catch (Exception $e) {
        return $date_str;
    }
}

function format_time_display($time_str, $format = "h:i A") {
    if (empty($time_str)) return 'N/A';
    try {
        // Assuming $time_str is in "HH:MM:SS" or "HH:MM" format
        $date = DateTime::createFromFormat('H:i:s', $time_str);
        if (!$date) {
            $date = DateTime::createFromFormat('H:i', $time_str);
        }
        return $date ? $date->format($format) : $time_str;
    } catch (Exception $e) {
        return $time_str;
    }
}


// Add more utility functions as needed, e.g., for pagination, email sending, etc.

// PHPMailer classes are now included in config.php, so they are available globally.
// We just need to use the namespace.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send email using PHPMailer
function send_email($to, $subject, $html_message, $from_email, $from_name) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions

    try {
        // Server settings
        // $mail->SMTPDebug = 2; // Enable verbose debug output for troubleshooting
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = SMTP_HOST;                        // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = SMTP_USERNAME;                    // SMTP username
        $mail->Password   = SMTP_PASSWORD;                    // SMTP password
        $mail->SMTPSecure = SMTP_SECURE;                       // Enable TLS/SSL encryption
        $mail->Port       = SMTP_PORT;                        // TCP port to connect to

        // Recipients
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($to);     // Add a recipient

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;

        // Add some basic styling to the HTML message
        $styled_message = "
        <html>
        <head>
          <title>" . htmlspecialchars($subject) . "</title>
          <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
            h2 { color: #2c3e50; }
            .footer { margin-top: 20px; font-size: 0.9em; text-align: center; color: #777; }
          </style>
        </head>
        <body>
          <div class='container'>
            " . $html_message . "
            <div class='footer'>
              <p>This is an automated message from " . htmlspecialchars($from_name) . ".</p>
            </div>
          </div>
        </body>
        </html>";

        $mail->Body    = $styled_message;
        // Create a plain text version of the email (for clients that don't support HTML)
        $mail->AltBody = strip_tags(str_replace("<br>", "\n", $html_message));

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log the detailed error message from PHPMailer
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
