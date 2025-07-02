<?php
// This partial is included in dashboardAdmin.php when tab=users
// Ensure $mysqli is available
// Ensure user is admin (checked by dashboardAdmin.php)
?>
<div class="p-4 sm:p-6 space-y-6">
    <h2 class="text-white text-2xl sm:text-3xl font-bold tracking-tight">Manage Users</h2>

    <div id="usersTableContainer" class="bg-[#353017] p-2 sm:p-4 rounded-lg shadow @container">
        <div class="overflow-x-auto">
            <table id="usersTable" class="min-w-full divide-y divide-[#4a4321]">
                <thead class="bg-[#4a4321]">
                    <tr>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">ID</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Name</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[600px]:table-cell">Email</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Role</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white hidden @[800px]:table-cell">Registered</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs sm:text-sm font-semibold text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#4a4321] text-gray-300" id="usersTableBody">
                    <!-- Rows will be populated by JavaScript -->
                    <tr><td colspan="6" class="text-center p-8 text-gray-400">Loading users...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="userManagementFlashMessage" class="fixed bottom-5 right-5 z-[100]"></div>
</div>

<script>
function fetchUsers() {
    const tableBody = document.getElementById('usersTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center p-8 text-gray-400">Loading users...</td></tr>';

    fetch('<?php echo BASE_URL . "/manage_users.php"; ?>')
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = ''; // Clear loading message
            if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-red-400">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.data && data.data.length > 0) {
                data.data.forEach(user => {
                    const row = tableBody.insertRow();
                    row.innerHTML = `
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">${user.id}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap font-medium text-white">${escapeHtml(user.name)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[600px]:table-cell">${escapeHtml(user.email)}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">
                            <span id="role-text-${user.id}" class="capitalize px-2 py-1 text-xs rounded-full ${user.role === 'admin' ? 'bg-purple-600 text-white' : 'bg-gray-500 text-white'}">
                                ${escapeHtml(user.role)}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[800px]:table-cell">${formatDateForDisplay(user.created_at_formatted.split(' ')[0])}</td>
                        <td class_id="${user.id}" class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap">
                            <select class="role-select text-xs p-1.5 rounded-md border-gray-600 bg-[#232010] text-white focus:ring-[#fcdd53] focus:border-[#fcdd53]" data-user-id="${user.id}" data-current-role="${user.role}">
                                <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                            <button onclick="updateUserRole(${user.id})" class="ml-2 px-2 py-1 text-xs bg-[#fcdd53] text-[#232010] rounded hover:bg-[#fadc70]">Save Role</button>
                        </td>
                    `;
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center p-8 text-gray-400">No users found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching users:', error);
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-red-400">Failed to load users.</td></tr>`;
        });
}

function updateUserRole(userId) {
    const selectElement = document.querySelector(`.role-select[data-user-id='${userId}']`);
    const newRole = selectElement.value;
    const currentRole = selectElement.dataset.currentRole;

    if (newRole === currentRole) {
        showUserManagementFlash('No change in role.', 'info');
        return;
    }

    if (!confirm(`Are you sure you want to change the role for user ID ${userId} to "${newRole}"?`)) {
        selectElement.value = currentRole; // Revert if cancelled
        return;
    }

    const formData = new FormData();
    formData.append('action', 'update_role');
    formData.append('user_id', userId);
    formData.append('new_role', newRole);

    fetch('<?php echo BASE_URL . "/manage_users.php"; ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showUserManagementFlash(data.message, 'success');
            selectElement.dataset.currentRole = newRole; // Update the stored current role
            // Update the visual badge
            const roleTextSpan = document.getElementById(`role-text-${userId}`);
            if (roleTextSpan) {
                roleTextSpan.textContent = newRole;
                roleTextSpan.className = `capitalize px-2 py-1 text-xs rounded-full ${newRole === 'admin' ? 'bg-purple-600 text-white' : 'bg-gray-500 text-white'}`;
            }
        } else {
            showUserManagementFlash('Error: ' + data.message, 'error');
            selectElement.value = currentRole; // Revert dropdown on error
        }
    })
    .catch(error => {
        console.error('Error updating role:', error);
        showUserManagementFlash('An unexpected error occurred.', 'error');
        selectElement.value = currentRole; // Revert dropdown on error
    });
}

function showUserManagementFlash(message, type = 'info') {
    const flashContainer = document.getElementById('userManagementFlashMessage');
    if (!flashContainer) return;

    const flashDiv = document.createElement('div');
    flashDiv.className = `p-3 mb-2 text-sm rounded-lg shadow-lg ${type === 'success' ? 'bg-green-700 text-green-100' : (type === 'error' ? 'bg-red-700 text-red-100' : 'bg-blue-700 text-blue-100')}`;
    flashDiv.setAttribute('role', 'alert');
    flashDiv.textContent = message;

    flashContainer.appendChild(flashDiv);

    setTimeout(() => {
        flashDiv.style.opacity = '0';
        flashDiv.style.transition = 'opacity 0.5s ease-out';
        setTimeout(() => flashDiv.remove(), 500);
    }, 4000);
}

function escapeHtml(unsafe) {
    if (unsafe === null || typeof unsafe === 'undefined') return '';
    return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
function formatDateForDisplay(dateStr) { // Expects YYYY-MM-DD
    if (!dateStr) return 'N/A';
    const [year, month, day] = dateStr.split('-');
    const dateObj = new Date(year, month - 1, day); // Month is 0-indexed
    if (isNaN(dateObj.getTime())) return dateStr; // Return original if invalid
    return dateObj.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}


document.addEventListener('DOMContentLoaded', fetchUsers);
</script>
<style>
    .role-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>
