<?php
require_once 'config.php';
require_once 'auth_check.php';
require_admin(); // Only admins can access any part of this script

header('Content-Type: application/json');

// Handling GET request to fetch all users (for populating the admin user table)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['action'])) { // No specific 'action' for listing
    $response = ['success' => false, 'data' => [], 'error' => null];

    $sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
    if ($stmt = $mysqli->prepare($sql)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                // Format date directly in PHP for consistency
                $row['created_at_formatted'] = format_datetime_display($row['created_at'], "Y-m-d H:i");
                $response['data'][] = $row;
            }
            $response['success'] = true;
        } else {
            $response['error'] = 'Failed to fetch users: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['error'] = 'Database error (prepare GET users): ' . $mysqli->error;
    }
    echo json_encode($response);
    $mysqli->close();
    exit;
}

// Handling POST requests for actions
$response = ['success' => false, 'message' => '', 'errors' => []]; // Default for POST actions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $user_id_to_manage = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    // $new_role is specific to 'update_role' action
    // Password fields are specific to 'admin_change_own_password' action

    if ($action === 'update_role') {
        $new_role = isset($_POST['new_role']) ? sanitize_input($_POST['new_role']) : null;
        if (!$user_id_to_manage || !$new_role) {
            $response['message'] = 'User ID and new role are required.';
        } elseif (!in_array($new_role, ['user', 'admin'])) {
            $response['message'] = 'Invalid role specified.';
        } else {
            // Security check: Prevent admin from accidentally removing their own admin role if they are the only admin
            if ($user_id_to_manage === $_SESSION['user_id'] && $new_role === 'user') {
                $sql_count_admins = "SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'";
                $admin_count_result = $mysqli->query($sql_count_admins);
                $admin_count_row = $admin_count_result->fetch_assoc();
                if ($admin_count_row && $admin_count_row['admin_count'] <= 1) {
                    $response['message'] = 'Cannot remove admin role from the only administrator.';
                    echo json_encode($response); // Output immediately and exit
                    $mysqli->close();
                    exit;
                }
            }

            $sql_update_role = "UPDATE users SET role = ? WHERE id = ?";
            if ($stmt_update = $mysqli->prepare($sql_update_role)) {
                $stmt_update->bind_param("si", $new_role, $user_id_to_manage);
                if ($stmt_update->execute()) {
                    if ($stmt_update->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = "User role updated successfully to '{$new_role}'.";
                    } else {
                        $response['message'] = 'User not found or role unchanged.';
                    }
                } else {
                    $response['message'] = 'Failed to update user role: ' . $stmt_update->error;
                }
                $stmt_update->close();
            } else {
                $response['message'] = 'Database error (prepare update role): ' . $mysqli->error;
            }
        }
    } elseif ($action === 'admin_change_own_password') {
        // This action is for the logged-in admin to change their own password
        $current_user_id = $_SESSION['user_id']; // Admin's own ID
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';

        if (empty($current_password)) $response['errors']['current_password'] = "Current password is required.";
        if (empty($new_password)) $response['errors']['new_password'] = "New password is required.";
        else if (strlen($new_password) < 6) $response['errors']['new_password'] = "New password must be at least 6 characters.";
        if ($new_password !== $confirm_new_password) $response['errors']['confirm_new_password'] = "New passwords do not match.";

        if (empty($response['errors'])) {
            $sql_get_current_pass = "SELECT password FROM users WHERE id = ?";
            if ($stmt_get = $mysqli->prepare($sql_get_current_pass)) {
                $stmt_get->bind_param("i", $current_user_id);
                $stmt_get->execute();
                $result_get = $stmt_get->get_result();
                if ($user_data = $result_get->fetch_assoc()) {
                    if (password_verify($current_password, $user_data['password'])) {
                        $hashed_new_pass = password_hash($new_password, PASSWORD_DEFAULT);
                        $sql_update_pass = "UPDATE users SET password = ? WHERE id = ?";
                        if ($stmt_update_p = $mysqli->prepare($sql_update_pass)) {
                            $stmt_update_p->bind_param("si", $hashed_new_pass, $current_user_id);
                            if ($stmt_update_p->execute()) {
                                $response['success'] = true;
                                $response['message'] = "Password changed successfully.";
                            } else {
                                $response['message'] = "Error updating password: " . $stmt_update_p->error;
                            }
                            $stmt_update_p->close();
                        } else {
                             $response['message'] = "Database error (prepare password update): " . $mysqli->error;
                        }
                    } else {
                        $response['errors']['current_password'] = "Incorrect current password.";
                        $response['message'] = "Incorrect current password.";
                    }
                } else {
                    $response['message'] = "User not found."; // Should not happen for a logged-in admin
                }
                $stmt_get->close();
            } else {
                $response['message'] = "Database error (prepare get current pass): " . $mysqli->error;
            }
        } else {
            $response['message'] = "Please correct the errors."; // General message if specific field errors exist
        }
    } else {
        $response['message'] = 'Invalid action specified for POST request.';
    }
    echo json_encode($response);
    $mysqli->close();
    exit;
}

// Fallback for any other request method or if script is accessed directly without proper method
// (though the GET handler above should catch most direct accesses)
// $response['message'] = 'Invalid request method or no action specified.';
// echo json_encode($response);
// $mysqli->close();
// exit;
?>
