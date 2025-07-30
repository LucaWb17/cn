<?php
// Questo partial è incluso in dashboardAdmin.php quando tab=settings
// Assicura che $mysqli sia disponibile (da config.php)
// Assicura che l'utente sia admin (controllato da dashboardAdmin.php)
?>
<div class="p-4 sm:p-6 space-y-6">
    <h2 class="text-white text-2xl sm:text-3xl font-bold tracking-tight">Admin Settings</h2>

    <!-- Change Password Section -->
    <div class="bg-[#353017] p-4 sm:p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold text-white mb-4">Change Your Password</h3>

        <div id="adminChangePasswordMessage" class="hidden p-3 mb-4 rounded-md text-sm"></div>

        <form id="adminChangePasswordForm" class="space-y-4">
            <?php echo csrf_input_field(); ?>
            <div>
                <label for="current_password_admin" class="block text-sm font-medium text-[#cdc28e] mb-1">Current Password</label>
                <input type="password" name="current_password" id="current_password_admin" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
                <p id="error_current_password_admin" class="text-red-400 text-xs mt-1"></p>
            </div>
            <div>
                <label for="new_password_admin" class="block text-sm font-medium text-[#cdc28e] mb-1">New Password</label>
                <input type="password" name="new_password" id="new_password_admin" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
                <p id="error_new_password_admin" class="text-red-400 text-xs mt-1"></p>
            </div>
            <div>
                <label for="confirm_new_password_admin" class="block text-sm font-medium text-[#cdc28e] mb-1">Confirm New Password</label>
                <input type="password" name="confirm_new_password" id="confirm_new_password_admin" required
                       class="mt-1 block w-full rounded-md bg-[#232010] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring focus:ring-[#fcdd53] focus:ring-opacity-50 p-3">
                <p id="error_confirm_new_password_admin" class="text-red-400 text-xs mt-1"></p>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-[#232010] bg-[#fcdd53] rounded-lg hover:bg-[#fadc70] focus:ring-2 focus:outline-none focus:ring-[#fadc70]">
                    Change Password
                </button>
            </div>
        </form>
    </div>

    <!-- Altre impostazioni admin potrebbero andare qui -->

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changePasswordForm = document.getElementById('adminChangePasswordForm');
    const messageDiv = document.getElementById('adminChangePasswordMessage');

    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clear previous messages
            messageDiv.textContent = '';
            messageDiv.className = 'hidden p-3 mb-4 rounded-md text-sm';
            document.getElementById('error_current_password_admin').textContent = '';
            document.getElementById('error_new_password_admin').textContent = '';
            document.getElementById('error_confirm_new_password_admin').textContent = '';

            const formData = new FormData(this);
            formData.append('action', 'admin_change_own_password');

            messageDiv.textContent = 'Processing...';
            messageDiv.className = 'p-3 mb-4 rounded-md text-sm bg-yellow-600/30 border border-yellow-500 text-yellow-300';
            messageDiv.classList.remove('hidden');

            fetch('<?php echo BASE_URL . "/manage_users.php"; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageDiv.textContent = data.message || 'Password changed successfully!';
                    messageDiv.className = 'p-3 mb-4 rounded-md text-sm bg-green-600/30 border border-green-500 text-green-300';
                    changePasswordForm.reset();
                    // Potrebbe essere una buona idea reindirizzare al login o mostrare un messaggio più persistente
                    // Per ora, solo un messaggio e reset.
                } else {
                    messageDiv.textContent = data.message || 'Could not change password. Please check errors below.';
                    messageDiv.className = 'p-3 mb-4 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
                    if (data.errors) {
                        for (const key in data.errors) {
                            const errorField = document.getElementById(`error_${key}_admin`);
                            if (errorField) {
                                errorField.textContent = data.errors[key];
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'An unexpected network error occurred.';
                messageDiv.className = 'p-3 mb-4 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
            });
        });
    }
});
</script>
