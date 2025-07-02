<?php
require_once 'config.php';
require_once 'auth_check.php';
require_admin(); // Only admins can manage users

header('Content-Type: application/json');
$response = ['data' => [], 'error' => null];

// Fetch all users (excluding sensitive info like password for general listing)
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";

if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['created_at_formatted'] = date("Y-m-d H:i", strtotime($row['created_at']));
            $response['data'][] = $row;
        }
    } else {
        $response['error'] = 'Failed to fetch users: ' . $stmt->error;
    }
    $stmt->close();
} else {
    $response['error'] = 'Database error (prepare GET): ' . $mysqli->error;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $user_id_to_manage = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
    $new_role = isset($_POST['new_role']) ? sanitize_input($_POST['new_role']) : null;

    if ($action === 'update_role') {
        if (!$user_id_to_manage || !$new_role) {
            $response['message'] = 'User ID and new role are required.';
            echo json_encode($response);
            exit;
        }
        if (!in_array($new_role, ['user', 'admin'])) {
            $response['message'] = 'Invalid role specified.';
            echo json_encode($response);
            exit;
        }

        // Security check: Prevent admin from accidentally removing their own admin role if they are the only admin
        if ($user_id_to_manage === $_SESSION['user_id'] && $new_role === 'user') {
            $sql_count_admins = "SELECT COUNT(*) as admin_count FROM users WHERE role = 'admin'";
            $admin_count_result = $mysqli->query($sql_count_admins);
            $admin_count_row = $admin_count_result->fetch_assoc();
            if ($admin_count_row && $admin_count_row['admin_count'] <= 1) {
                $response['message'] = 'Cannot remove admin role from the only administrator.';
                echo json_encode($response);
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
                    // If admin changes their own role away from admin, they should be logged out or redirected.
                    // However, the security check above should prevent removing the last admin.
                    // If an admin demotes another admin, that's fine.
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
    } else {
        $response['message'] = 'Invalid action for POST request.';
    }
} else {
     $response['message'] = 'Invalid request method.';
}


echo json_encode($response);
$mysqli->close();
?>
