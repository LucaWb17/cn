<?php
require_once 'config.php';
require_once 'auth_check.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'data' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_admin()) {
        $response['message'] = 'Unauthorized';
        echo json_encode($response);
        exit;
    }
    verify_csrf_token();
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : null;

    if (!$booking_id) {
        $response['message'] = 'Booking ID is required.';
        echo json_encode($response);
        exit;
    }

    if ($action === 'update_status') {
        $new_status = isset($_POST['status']) ? sanitize_input($_POST['status']) : null;
        $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled', 'no-show'];

        if (!$new_status || !in_array($new_status, $valid_statuses)) {
            $response['message'] = 'Invalid status provided.';
            echo json_encode($response);
            exit;
        }

        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("si", $new_status, $booking_id);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Booking status updated successfully.';
                    // Optionally, fetch the updated booking to return
                    // For now, we'll just confirm success.
                } else {
                    $response['message'] = 'Booking not found or status unchanged.';
                }
            } else {
                $response['message'] = 'Failed to update status: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Database error (prepare): ' . $mysqli->error;
        }
    } elseif ($action === 'get_details') { // Example for fetching single booking details
        $sql = "SELECT b.*, s.name as service_name, u.name as user_name, u.email as user_email
                FROM bookings b
                JOIN services s ON b.service_id = s.id
                LEFT JOIN users u ON b.user_id = u.id
                WHERE b.id = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("i", $booking_id);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($booking_details = $result->fetch_assoc()){
                    $response['success'] = true;
                    $response['data'] = $booking_details;
                } else {
                    $response['message'] = "Booking not found.";
                }
            } else {
                 $response['message'] = 'Failed to fetch details: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Database error (prepare): ' . $mysqli->error;
        }

    } elseif ($action === 'delete_booking') {
        if (!$booking_id) { // Should have been caught earlier, but good to double check
            $response['message'] = 'Booking ID is required for deletion.';
            echo json_encode($response);
            exit;
        }
        // Optional: Add any other checks here, e.g., cannot delete 'completed' bookings older than X days, etc.
        // For now, we allow deletion of any booking by admin.

        $sql_delete = "DELETE FROM bookings WHERE id = ?";
        if ($stmt_delete = $mysqli->prepare($sql_delete)) {
            $stmt_delete->bind_param("i", $booking_id);
            if ($stmt_delete->execute()) {
                if ($stmt_delete->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Booking deleted successfully.';
                } else {
                    $response['message'] = 'Booking not found or already deleted.';
                }
            } else {
                $response['message'] = 'Failed to delete booking: ' . $stmt_delete->error;
            }
            $stmt_delete->close();
        } else {
            $response['message'] = 'Database error (prepare delete): ' . $mysqli->error;
        }

    } else {
        $response['message'] = 'Invalid action.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
$mysqli->close();
?>
