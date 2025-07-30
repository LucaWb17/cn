<?php
require_once 'config.php';
require_once 'auth_check.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'data' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!is_admin()) {
        $response['message'] = 'Unauthorized';
        echo json_encode($response);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf_token();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all services
    $sql = "SELECT id, name, description, price, duration FROM services ORDER BY name ASC";
    if ($stmt = $mysqli->prepare($sql)) {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;
            }
            $response['success'] = true;
        } else {
            $response['message'] = 'Failed to fetch services: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database error (prepare): ' . $mysqli->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : null); // Allow action in GET for simplicity in forms sometimes

    $service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : null;
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : null;
    $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : null;
    $price = isset($_POST['price']) ? (float)$_POST['price'] : null;
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : null; // Duration in minutes

    if ($action === 'add') {
        if (empty($name) || $price === null || $duration === null) {
            $response['message'] = 'Service name, price, and duration are required.';
        } else {
            $sql = "INSERT INTO services (name, description, price, duration) VALUES (?, ?, ?, ?)";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssdi", $name, $description, $price, $duration);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Service added successfully.';
                    $response['data'] = ['id' => $mysqli->insert_id, 'name' => $name, 'description' => $description, 'price' => $price, 'duration' => $duration];
                } else {
                    $response['message'] = 'Failed to add service: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'Database error (prepare add): ' . $mysqli->error;
            }
        }
    } elseif ($action === 'edit') {
        if (!$service_id || empty($name) || $price === null || $duration === null) {
            $response['message'] = 'Service ID, name, price, and duration are required for editing.';
        } else {
            $sql = "UPDATE services SET name = ?, description = ?, price = ?, duration = ? WHERE id = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("ssdii", $name, $description, $price, $duration, $service_id);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $response['success'] = true;
                        $response['message'] = 'Service updated successfully.';
                        $response['data'] = ['id' => $service_id, 'name' => $name, 'description' => $description, 'price' => $price, 'duration' => $duration];
                    } else {
                        $response['message'] = 'Service not found or no changes made.';
                    }
                } else {
                    $response['message'] = 'Failed to update service: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $response['message'] = 'Database error (prepare edit): ' . $mysqli->error;
            }
        }
    } elseif ($action === 'delete') {
        if (!$service_id) {
            $response['message'] = 'Service ID is required for deletion.';
        } else {
            // Check for dependencies (bookings) before deleting
            $check_sql = "SELECT COUNT(*) as count FROM bookings WHERE service_id = ?";
            $can_delete = true;
            if($check_stmt = $mysqli->prepare($check_sql)){
                $check_stmt->bind_param("i", $service_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result()->fetch_assoc();
                if($check_result['count'] > 0){
                    $can_delete = false;
                    $response['message'] = 'Cannot delete service: It is associated with existing bookings. Consider deactivating it instead.';
                }
                $check_stmt->close();
            } else {
                 $response['message'] = 'Database error (prepare dependency check): ' . $mysqli->error;
                 $can_delete = false; // Safety first
            }

            if($can_delete){
                $sql = "DELETE FROM services WHERE id = ?";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("i", $service_id);
                    if ($stmt->execute()) {
                        if ($stmt->affected_rows > 0) {
                            $response['success'] = true;
                            $response['message'] = 'Service deleted successfully.';
                        } else {
                            $response['message'] = 'Service not found.';
                        }
                    } else {
                        $response['message'] = 'Failed to delete service: ' . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $response['message'] = 'Database error (prepare delete): ' . $mysqli->error;
                }
            }
        }
    } else {
        $response['message'] = 'Invalid action specified.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
$mysqli->close();
?>
