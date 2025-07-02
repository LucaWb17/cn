<?php
require_once 'config.php';

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = $general_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = sanitize_input($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        } else {
            // Check if email is already taken
            $sql = "SELECT id FROM users WHERE email = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $param_email);
                $param_email = $email;
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->num_rows == 1) {
                        $email_err = "This email is already taken.";
                    }
                } else {
                    $general_err = "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($general_err)) {
        $hashed_password = hash_password($password);
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sss", $param_name, $param_email, $param_password);
            $param_name = $name;
            $param_email = $email;
            $param_password = $hashed_password; // Store the hashed password

            if ($stmt->execute()) {
                // Registration successful, redirect to login page
                $_SESSION['success_message'] = "Registration successful! Please login.";
                redirect(BASE_URL . "/login.php");
            } else {
                $general_err = "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    // If there were errors, the script will fall through and the createaccount.php page
    // (which should include this script or post to it) will display the errors.
    // For a cleaner approach, error messages can be passed back via session or query parameters
    // if register.php is a standalone processing script.
    // For simplicity here, we assume createaccount.php will handle displaying these PHP variables.
}

// Close connection (optional here as it's often closed at the end of script execution automatically)
// $mysqli->close();
?>
