<?php
// This partial is included in areacliente.php when tab=settings.
// Ensure $mysqli and $user_id are available.
// require_once '../config.php'; // Already included
// require_once '../utils/functions.php'; // Already included

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

$name_err = $email_err = $password_err = $confirm_password_err = $general_message = "";
$message_type = ""; // 'success' or 'error'

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    if (isset($_POST['update_profile'])) {
        $new_name = sanitize_input($_POST['name']);
        $new_email = sanitize_input($_POST['email']);

        if (empty($new_name)) {
            $name_err = "Name cannot be empty.";
        }
        if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Valid email is required.";
        }

        // Check if email is changed and if the new one is already taken by another user
        if ($new_email !== $user_email && empty($email_err)) {
            $sql_check_email = "SELECT id FROM users WHERE email = ? AND id != ?";
            if ($stmt_check = $mysqli->prepare($sql_check_email)) {
                $stmt_check->bind_param("si", $new_email, $user_id);
                $stmt_check->execute();
                $stmt_check->store_result();
                if ($stmt_check->num_rows > 0) {
                    $email_err = "This email is already taken by another account.";
                }
                $stmt_check->close();
            }
        }

        if (empty($name_err) && empty($email_err)) {
            $sql_update_profile = "UPDATE users SET name = ?, email = ? WHERE id = ?";
            if ($stmt_update = $mysqli->prepare($sql_update_profile)) {
                $stmt_update->bind_param("ssi", $new_name, $new_email, $user_id);
                if ($stmt_update->execute()) {
                    $_SESSION['user_name'] = $new_name; // Update session
                    $_SESSION['user_email'] = $new_email; // Update session
                    $user_name = $new_name; // Update local var for immediate display
                    $user_email = $new_email; // Update local var
                    $general_message = "Profile updated successfully!";
                    $message_type = "success";
                } else {
                    $general_message = "Error updating profile: " . $mysqli->error;
                    $message_type = "error";
                }
                $stmt_update->close();
            } else {
                $general_message = "Database error (profile update prepare): " . $mysqli->error;
                $message_type = "error";
            }
        }

    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];

        if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
            $password_err = "All password fields are required.";
        } elseif (strlen($new_password) < 6) {
            $password_err = "New password must be at least 6 characters long.";
        } elseif ($new_password !== $confirm_new_password) {
            $confirm_password_err = "New passwords do not match.";
        } else {
            // Verify current password
            $sql_get_pass = "SELECT password FROM users WHERE id = ?";
            if ($stmt_get_pass = $mysqli->prepare($sql_get_pass)) {
                $stmt_get_pass->bind_param("i", $user_id);
                $stmt_get_pass->execute();
                $result_pass = $stmt_get_pass->get_result();
                if ($user_data = $result_pass->fetch_assoc()) {
                    if (verify_password($current_password, $user_data['password'])) {
                        // Current password is correct, update to new password
                        $hashed_new_password = hash_password($new_password);
                        $sql_update_pass = "UPDATE users SET password = ? WHERE id = ?";
                        if ($stmt_update_pass = $mysqli->prepare($sql_update_pass)) {
                            $stmt_update_pass->bind_param("si", $hashed_new_password, $user_id);
                            if ($stmt_update_pass->execute()) {
                                $general_message = "Password changed successfully!";
                                $message_type = "success";
                            } else {
                                $general_message = "Error changing password: " . $mysqli->error;
                                $message_type = "error";
                            }
                            $stmt_update_pass->close();
                        } else {
                             $general_message = "Database error (password update prepare): " . $mysqli->error;
                             $message_type = "error";
                        }
                    } else {
                        $password_err = "Incorrect current password.";
                    }
                }
                $stmt_get_pass->close();
            }
        }
         if(!empty($password_err) || !empty($confirm_password_err) && empty($general_message)) {
            $message_type = "error"; // Ensure message type is error if specific password errors occurred
        }
    }
}
?>
<div class="p-4 space-y-8">
    <h2 class="text-white text-2xl font-bold tracking-tight">Account Settings</h2>

    <?php if (!empty($general_message)): ?>
        <div class="p-4 rounded-md <?php echo $message_type === 'success' ? 'bg-green-600/30 border border-green-500 text-green-300' : 'bg-red-600/30 border border-red-500 text-red-300'; ?>">
            <?php echo htmlspecialchars($general_message); ?>
        </div>
    <?php endif; ?>

    <!-- Update Profile Section -->
    <div class="bg-[#353017] p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold text-white mb-4">Update Profile</h3>
        <form method="POST" action="<?php echo BASE_URL . '/areacliente.php?tab=settings'; ?>" class="space-y-4">
            <?php echo csrf_input_field(); ?>
            <div>
                <label for="name" class="block text-sm font-medium text-[#cdc28e]">Full Name</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user_name); ?>" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3 <?php echo !empty($name_err) ? 'border-red-500' : ''; ?>">
                <?php if (!empty($name_err)): ?><p class="text-red-400 text-xs mt-1"><?php echo $name_err; ?></p><?php endif; ?>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-[#cdc28e]">Email Address</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_email); ?>" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3 <?php echo !empty($email_err) ? 'border-red-500' : ''; ?>">
                <?php if (!empty($email_err)): ?><p class="text-red-400 text-xs mt-1"><?php echo $email_err; ?></p><?php endif; ?>
            </div>
            <div class="text-right">
                <button type="submit" name="update_profile" class="px-5 py-2.5 text-sm font-medium text-[#232010] bg-[#fcdd53] rounded-lg hover:bg-[#fadc70] focus:ring-2 focus:outline-none focus:ring-[#fadc70]">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- Change Password Section -->
    <div class="bg-[#353017] p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold text-white mb-4">Change Password</h3>
        <form method="POST" action="<?php echo BASE_URL . '/areacliente.php?tab=settings'; ?>" class="space-y-4">
            <?php echo csrf_input_field(); ?>
            <div>
                <label for="current_password" class="block text-sm font-medium text-[#cdc28e]">Current Password</label>
                <input type="password" name="current_password" id="current_password" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3 <?php echo !empty($password_err) && strpos($password_err, 'current') !== false ? 'border-red-500' : ''; ?>">
                 <?php if (!empty($password_err) && (strpos($password_err, 'current') !== false || strpos($password_err, 'All password fields') !== false) ): ?><p class="text-red-400 text-xs mt-1"><?php echo $password_err; ?></p><?php endif; ?>
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-[#cdc28e]">New Password</label>
                <input type="password" name="new_password" id="new_password" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3 <?php echo !empty($password_err) && (strpos($password_err, 'New password') !== false || strpos($password_err, 'All password fields') !== false) ? 'border-red-500' : ''; ?>">
                 <?php if (!empty($password_err) && (strpos($password_err, 'New password') !== false || strpos($password_err, 'All password fields') !== false) && strpos($password_err, 'current') === false): ?><p class="text-red-400 text-xs mt-1"><?php echo $password_err; ?></p><?php endif; ?>
            </div>
            <div>
                <label for="confirm_new_password" class="block text-sm font-medium text-[#cdc28e]">Confirm New Password</label>
                <input type="password" name="confirm_new_password" id="confirm_new_password" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3 <?php echo !empty($confirm_password_err) ? 'border-red-500' : ''; ?>">
                <?php if (!empty($confirm_password_err)): ?><p class="text-red-400 text-xs mt-1"><?php echo $confirm_password_err; ?></p><?php endif; ?>
                 <?php if (!empty($password_err) && strpos($password_err, 'All password fields') !== false && empty($confirm_password_err)): ?><p class="text-red-400 text-xs mt-1"><?php echo $password_err; ?></p><?php endif; ?>
            </div>
            <div class="text-right">
                <button type="submit" name="change_password" class="px-5 py-2.5 text-sm font-medium text-[#232010] bg-[#fcdd53] rounded-lg hover:bg-[#fadc70] focus:ring-2 focus:outline-none focus:ring-[#fadc70]">Change Password</button>
            </div>
        </form>
    </div>
</div>
