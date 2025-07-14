<?php
require_once 'config.php'; // Includes session_start() via config.php

$service_id = $vehicle_make = $vehicle_model = $vehicle_year = $license_plate = $booking_date = $booking_time = "";
$guest_name = $guest_email = $guest_phone = "";
$user_id = null;

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Form Data Collection and Basic Sanitization ---
    $service_id = isset($_POST['service_id']) ? (int)$_POST['service_id'] : null;
    $booking_date = isset($_POST['booking_date']) ? sanitize_input($_POST['booking_date']) : null;
    $booking_time = isset($_POST['booking_time']) ? sanitize_input($_POST['booking_time']) : null;

    // Vehicle details can come from logged-in user's vehicle or new input
    $vehicle_make = isset($_POST['vehicle_make']) ? sanitize_input($_POST['vehicle_make']) : null;
    $vehicle_model = isset($_POST['vehicle_model']) ? sanitize_input($_POST['vehicle_model']) : null;
    $vehicle_year = isset($_POST['vehicle_year']) ? (int)$_POST['vehicle_year'] : null;
    $license_plate = isset($_POST['license_plate']) ? sanitize_input($_POST['license_plate']) : null;

    $selected_vehicle_id = isset($_POST['selected_vehicle_id']) && $_POST['selected_vehicle_id'] !== 'new' ? (int)$_POST['selected_vehicle_id'] : null;

    // Guest details (if user is not logged in or chooses to book as guest)
    if (!is_logged_in() || (is_logged_in() && isset($_POST['book_as_guest']))) { // Assuming a checkbox 'book_as_guest' might exist
        $guest_name = isset($_POST['guest_name']) ? sanitize_input($_POST['guest_name']) : null;
        $guest_email = isset($_POST['guest_email']) ? sanitize_input($_POST['guest_email']) : null;
        $guest_phone = isset($_POST['guest_phone']) ? sanitize_input($_POST['guest_phone']) : null;

        if (empty($guest_name)) $errors['guest_name'] = "Guest name is required.";
        if (empty($guest_email) || !filter_var($guest_email, FILTER_VALIDATE_EMAIL)) $errors['guest_email'] = "Valid guest email is required.";
        // Phone validation can be more complex, basic check here
        if (empty($guest_phone)) $errors['guest_phone'] = "Guest phone number is required.";

    } else {
        $user_id = $_SESSION['user_id'];
    }

    // --- Validation ---
    if (empty($service_id)) $errors['service_id'] = "Please select a service.";
    if (empty($booking_date)) $errors['booking_date'] = "Please select a preferred date.";
    // TODO: Validate date format and ensure it's not in the past.
    if (empty($booking_time)) $errors['booking_time'] = "Please select a preferred time.";
    // TODO: Validate time format and ensure it's within business hours.

    if ($selected_vehicle_id) {
        // User selected an existing vehicle. Fetch its details to store with booking for denormalization/snapshot.
        // Or, decide if vehicle_id in bookings table is enough. For simplicity, we can just use vehicle_id.
        // If you want to store a snapshot of vehicle details at time of booking:
        $stmt_veh = $mysqli->prepare("SELECT make, model, year, license_plate FROM vehicles WHERE id = ? AND user_id = ?");
        if ($stmt_veh) {
            $stmt_veh->bind_param("ii", $selected_vehicle_id, $user_id); // Ensure it belongs to the user
            $stmt_veh->execute();
            $result_veh = $stmt_veh->get_result();
            if ($vehicle_data = $result_veh->fetch_assoc()) {
                $vehicle_make = $vehicle_data['make'];
                $vehicle_model = $vehicle_data['model'];
                $vehicle_year = $vehicle_data['year'];
                $license_plate = $vehicle_data['license_plate'];
            } else {
                $errors['selected_vehicle_id'] = "Selected vehicle not found or does not belong to you.";
            }
            $stmt_veh->close();
        }
    } else {
        // New vehicle details provided
        if (empty($vehicle_make)) $errors['vehicle_make'] = "Vehicle make is required.";
        if (empty($vehicle_model)) $errors['vehicle_model'] = "Vehicle model is required.";
        if (empty($vehicle_year) || !is_numeric($vehicle_year) || $vehicle_year < 1900 || $vehicle_year > (date("Y") + 1) ) {
            $errors['vehicle_year'] = "Valid vehicle year is required.";
        }
        if (empty($license_plate)) $errors['license_plate'] = "License plate is required.";
        // Optionally, save this new vehicle for logged-in users
        if (is_logged_in() && isset($_POST['save_vehicle_details'])) {
            // Check if vehicle with this license plate already exists for this user
            $check_veh_sql = "SELECT id FROM vehicles WHERE license_plate = ? AND user_id = ?";
            if($stmt_check_veh = $mysqli->prepare($check_veh_sql)){
                $stmt_check_veh->bind_param("si", $license_plate, $user_id);
                $stmt_check_veh->execute();
                $stmt_check_veh->store_result();
                if($stmt_check_veh->num_rows == 0){
                    $insert_veh_sql = "INSERT INTO vehicles (user_id, make, model, year, license_plate) VALUES (?, ?, ?, ?, ?)";
                    if ($stmt_insert_veh = $mysqli->prepare($insert_veh_sql)) {
                        $stmt_insert_veh->bind_param("issis", $user_id, $vehicle_make, $vehicle_model, $vehicle_year, $license_plate);
                        $stmt_insert_veh->execute();
                        $selected_vehicle_id = $mysqli->insert_id; // Get the ID of the newly inserted vehicle
                        $stmt_insert_veh->close();
                    } else {
                        $errors['database'] = "Error saving new vehicle: " . $mysqli->error;
                    }
                } else {
                     $stmt_check_veh->bind_result($existing_vehicle_id);
                     $stmt_check_veh->fetch();
                     $selected_vehicle_id = $existing_vehicle_id; // Use existing vehicle ID
                }
                $stmt_check_veh->close();
            }
        }
    }


    // Check for overlapping bookings (simplified check - more robust check needed for production)
    // This should ideally check against a table of available slots or business hours.
    $sql_check_overlap = "SELECT id FROM bookings WHERE booking_date = ? AND booking_time = ? AND status NOT IN ('cancelled', 'completed')";
    if ($stmt_check = $mysqli->prepare($sql_check_overlap)) {
        $stmt_check->bind_param("ss", $booking_date, $booking_time);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            // For simplicity, we'll allow some overlap, but in a real system, you'd limit concurrent bookings
            // $errors['booking_time'] = "This time slot is already booked. Please choose another time.";
        }
        $stmt_check->close();
    }


    if (empty($errors)) {
        $sql = "INSERT INTO bookings (user_id, guest_name, guest_email, guest_phone, service_id, vehicle_id, vehicle_make, vehicle_model, vehicle_year, vehicle_license_plate, booking_date, booking_time, status, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // vehicle_id might be null if it's a guest or new vehicle not saved.
            $vehicle_id_to_insert = $selected_vehicle_id ? $selected_vehicle_id : null;
            $notes = isset($_POST['notes']) ? sanitize_input($_POST['notes']) : null;

            $stmt->bind_param(
                "isssiissssss",
                $user_id,
                $guest_name,
                $guest_email,
                $guest_phone,
                $service_id,
                $vehicle_id_to_insert, // This is the actual vehicle.id from vehicles table
                $vehicle_make,         // Snapshot of make
                $vehicle_model,        // Snapshot of model
                $vehicle_year,         // Snapshot of year
                $license_plate,        // Snapshot of license
                $booking_date,
                $booking_time,
                $notes
            );

            if ($stmt->execute()) {
                $booking_id = $mysqli->insert_id;
                $_SESSION['success_message'] = "Booking successful! Your appointment is pending confirmation. Booking ID: " . $booking_id;

                // Fetch service name for email content
                $service_name_email = "Selected Service";
                $stmt_service = $mysqli->prepare("SELECT name FROM services WHERE id = ?");
                if($stmt_service){
                    $stmt_service->bind_param("i", $service_id);
                    $stmt_service->execute();
                    $result_service = $stmt_service->get_result();
                    if($service_details_email = $result_service->fetch_assoc()){
                        $service_name_email = $service_details_email['name'];
                    }
                    $stmt_service->close();
                }

                $client_email_for_notification = "";
                $client_name_for_notification = "";

                if (is_logged_in()) {
                    $client_email_for_notification = $_SESSION['user_email'];
                    $client_name_for_notification = $_SESSION['user_name'];
                } else {
                    $client_email_for_notification = $guest_email;
                    $client_name_for_notification = $guest_name;
                }

                // Send Admin Notification Email
                $admin_subject = "New Booking Received - ID: " . $booking_id;
                $admin_message = "<h2>New Booking Notification</h2>
                                  <p>A new booking has been made:</p>
                                  <ul>
                                    <li><strong>Booking ID:</strong> " . $booking_id . "</li>
                                    <li><strong>Client Name:</strong> " . htmlspecialchars($client_name_for_notification) . "</li>
                                    <li><strong>Client Email:</strong> " . htmlspecialchars($client_email_for_notification) . "</li>
                                    <li><strong>Service:</strong> " . htmlspecialchars($service_name_email) . "</li>
                                    <li><strong>Date:</strong> " . htmlspecialchars(format_date_display($booking_date)) . "</li>
                                    <li><strong>Time:</strong> " . htmlspecialchars(format_time_display($booking_time)) . "</li>
                                    <li><strong>Vehicle:</strong> " . htmlspecialchars($vehicle_make . ' ' . $vehicle_model . ' (' . $vehicle_year . ') - ' . $license_plate) . "</li>
                                    <li><strong>Notes:</strong> " . nl2br(htmlspecialchars($notes ?? 'N/A')) . "</li>
                                  </ul>
                                  <p>Please review this booking in the admin dashboard.</p>";

                if (!send_email(ADMIN_EMAIL, $admin_subject, $admin_message, FROM_EMAIL, FROM_NAME)) {
                    error_log("Failed to send admin notification email for booking ID: " . $booking_id);
                    // Optionally, you could add a message to $_SESSION for the admin to see on next login,
                    // or handle this failure more gracefully if email is critical.
                }

                // Send Client Confirmation Email
                if (!empty($client_email_for_notification)) {
                    $client_subject = "Your Booking Confirmation - ID: " . $booking_id;
                    $client_message = "<h2>Booking Confirmation</h2>
                                       <p>Dear " . htmlspecialchars($client_name_for_notification) . ",</p>
                                       <p>Thank you for your booking. Your appointment details are as follows:</p>
                                       <ul>
                                         <li><strong>Booking ID:</strong> " . $booking_id . "</li>
                                         <li><strong>Service:</strong> " . htmlspecialchars($service_name_email) . "</li>
                                         <li><strong>Date:</strong> " . htmlspecialchars(format_date_display($booking_date)) . "</li>
                                         <li><strong>Time:</strong> " . htmlspecialchars(format_time_display($booking_time)) . "</li>
                                         <li><strong>Vehicle:</strong> " . htmlspecialchars($vehicle_make . ' ' . $vehicle_model . ' (' . $vehicle_year . ') - ' . $license_plate) . "</li>
                                         <li><strong>Status:</strong> Pending Confirmation</li>
                                         <li><strong>Notes:</strong> " . nl2br(htmlspecialchars($notes ?? 'N/A')) . "</li>
                                       </ul>
                                       <p>We will notify you once your booking is confirmed by our team. If you have any questions, please contact us.</p>
                                       <p>Thank you,<br>" . FROM_NAME . "</p>";
                    if (!send_native_email($client_email_for_notification, $client_subject, $client_message, FROM_EMAIL, FROM_NAME)) {
                        error_log("Failed to send client confirmation email for booking ID: " . $booking_id . " to " . $client_email_for_notification);
                        // You might want to inform the user on the confirmation page that email sending failed.
                        $_SESSION['warning_message'] = "Booking successful, but we couldn't send a confirmation email. Please note your Booking ID: " . $booking_id;
                    }
                }

                // Redirect to a confirmation page or user's booking area
                if (is_logged_in()) {
                    redirect(BASE_URL . "/areacliente.php?tab=bookings");
                } else {
                    redirect(BASE_URL . "/bookinapp.php?success=true&booking_id=" . $booking_id);
                }

            } else {
                $_SESSION['error_message'] = "Booking failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Database error (prepare): " . $mysqli->error;
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_errors'] = $errors;
        redirect(BASE_URL . "/bookinapp.php"); // Redirect back to booking form with errors
    }
} else {
    // Not a POST request, redirect to booking page or home
    redirect(BASE_URL . "/bookinapp.php");
}

$mysqli->close();
?>
