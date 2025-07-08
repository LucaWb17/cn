<?php
// Questo partial è incluso in dashboardAdmin.php quando tab=users
// Assicura che $mysqli sia disponibile
// Assicura che l'utente sia admin (controllato da dashboardAdmin.php)
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
                    <tr><td colspan="6" class="text-center p-8 text-gray-400">Loading users...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="userManagementFlashMessage" class="fixed bottom-5 right-5 z-[100] w-full max-w-xs sm:max-w-sm"></div>
</div>

<!-- Communication Modal -->
<div id="communicationModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center p-4 hidden z-[110] backdrop-blur-sm">
    <div class="bg-[#232010] p-6 rounded-lg shadow-xl max-w-xl w-full max-h-[90vh] overflow-y-auto border border-[#4a4321]">
        <form id="communicationForm" class="space-y-4">
            <input type="hidden" name="user_id" id="communication_user_id">

            <h3 id="communicationModalTitle" class="text-xl font-semibold text-white mb-1">Send Message</h3>
            <p class="text-xs text-gray-400 mb-4 -mt-1">To: <span id="communication_recipient_email_display" class="font-medium"></span></p>
            <div id="communicationFormMessage" class="hidden p-3 mb-2 rounded-md text-sm"></div>

            <div>
                <label for="communication_subject" class="block text-sm font-medium text-[#cdc28e] mb-1">Subject</label>
                <input type="text" name="communication_subject" id="communication_subject" required
                       class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3 placeholder:text-gray-500"
                       placeholder="Subject of your message">
                <p id="error_communication_subject" class="text-red-400 text-xs mt-1"></p>
            </div>
            <div>
                <label for="communication_message" class="block text-sm font-medium text-[#cdc28e] mb-1">Message</label>
                <textarea name="communication_message" id="communication_message" rows="6" required
                          class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3 placeholder:text-gray-500"
                          placeholder="Write your message here..."></textarea>
                <p id="error_communication_message" class="text-red-400 text-xs mt-1"></p>
            </div>
            <div>
                <label for="communication_discount_code" class="block text-sm font-medium text-[#cdc28e] mb-1">Discount Code (Optional)</label>
                <input type="text" name="communication_discount_code" id="communication_discount_code"
                       class="mt-1 block w-full rounded-md bg-[#353017] border-[#4a4321] text-white shadow-sm focus:border-[#fcdd53] focus:ring-[#fcdd53] p-3 placeholder:text-gray-500"
                       placeholder="E.g., SUMMER20">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeCommunicationModal()" class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#4a4321] rounded-md hover:bg-[#5f552a]">Cancel</button>
                <button type="submit" id="sendCommunicationButton" class="px-4 py-2 text-sm font-medium text-[#232010] bg-[#fcdd53] rounded-md hover:bg-[#fadc70]">Send Message</button>
            </div>
        </form>
    </div>
</div>

<script>
function escapeHtml(unsafe) { // Rinominata per evitare conflitti se già definita altrove globalmente
    if (unsafe === null || typeof unsafe === 'undefined') return '';
    return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function formatDateForDisplayUsers(dateStr) {
    if (!dateStr) return 'N/A';
    const [year, month, day] = dateStr.split('-');
    const dateObj = new Date(year, parseInt(month, 10) - 1, day);
    if (isNaN(dateObj.getTime())) return dateStr;
    return dateObj.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}

function fetchUsers() {
    const tableBody = document.getElementById('usersTableBody');
    tableBody.innerHTML = '<tr><td colspan="6" class="text-center p-8 text-gray-400">Loading users...</td></tr>';

    fetch('<?php echo BASE_URL . "/manage_users.php"; ?>')
        .then(response => response.json())
        .then(data => {
            tableBody.innerHTML = '';
            if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-red-400">Error: ${escapeHtml(data.error)}</td></tr>`;
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
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap hidden @[800px]:table-cell">${formatDateForDisplayUsers(user.created_at_formatted.split(' ')[0])}</td>
                        <td class="px-3 py-4 text-xs sm:text-sm whitespace-nowrap user-actions-cell-${user.id}">
                            <select class="role-select text-xs p-1.5 rounded-md border-gray-600 bg-[#232010] text-white focus:ring-[#fcdd53] focus:border-[#fcdd53]" data-user-id="${user.id}" data-current-role="${user.role}">
                                <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                            <button onclick="updateUserRole(${user.id})" class="ml-1 sm:ml-2 px-2 py-1 text-xs bg-[#f4c653] text-[#232010] rounded hover:bg-[#e0b447] mb-1 sm:mb-0">Save Role</button>
                            <button onclick="openCommunicationModal(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}')" class="ml-1 sm:ml-2 mt-1 sm:mt-0 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-500">Communicate</button>
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
        selectElement.value = currentRole;
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
            selectElement.dataset.currentRole = newRole;
            const roleTextSpan = document.getElementById(`role-text-${userId}`);
            if (roleTextSpan) {
                roleTextSpan.textContent = newRole;
                roleTextSpan.className = `capitalize px-2 py-1 text-xs rounded-full ${newRole === 'admin' ? 'bg-purple-600 text-white' : 'bg-gray-500 text-white'}`;
            }
        } else {
            showUserManagementFlash('Error: ' + data.message, 'error');
            selectElement.value = currentRole;
        }
    })
    .catch(error => {
        console.error('Error updating role:', error);
        showUserManagementFlash('An unexpected error occurred.', 'error');
        selectElement.value = currentRole;
    });
}

function showUserManagementFlash(message, type = 'info') {
    const flashContainer = document.getElementById('userManagementFlashMessage');
    if (!flashContainer) return;
    const flashDiv = document.createElement('div');
    flashDiv.className = `p-3 mb-2 text-sm rounded-lg shadow-lg ${type === 'success' ? 'bg-green-700 text-green-100' : (type === 'error' ? 'bg-red-700 text-red-100' : 'bg-blue-700 text-blue-100')}`;
    flashDiv.setAttribute('role', 'alert');
    flashDiv.textContent = message;
    flashContainer.innerHTML = '';
    flashContainer.appendChild(flashDiv);
    setTimeout(() => {
        flashDiv.style.opacity = '0';
        flashDiv.style.transition = 'opacity 0.5s ease-out';
        setTimeout(() => flashDiv.remove(), 500);
    }, 4000);
}

function openCommunicationModal(userId, userName, userEmail) {
    document.getElementById('communication_user_id').value = userId;
    document.getElementById('communicationModalTitle').textContent = `Send Message to ${escapeHtml(userName)}`;
    document.getElementById('communication_recipient_email_display').textContent = escapeHtml(userEmail);
    document.getElementById('communicationModal').classList.remove('hidden');
    document.getElementById('communicationForm').reset();
    document.getElementById('communicationFormMessage').textContent = '';
    document.getElementById('communicationFormMessage').classList.add('hidden');
}

function closeCommunicationModal() {
    document.getElementById('communicationModal').classList.add('hidden');
}

document.getElementById('communicationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const messageDiv = document.getElementById('communicationFormMessage');
    const submitButton = document.getElementById('sendCommunicationButton');

    submitButton.disabled = true;
    submitButton.textContent = 'Sending...';
    messageDiv.textContent = 'Processing...';
    messageDiv.className = 'p-3 mb-2 rounded-md text-sm bg-yellow-600/30 border border-yellow-500 text-yellow-300'; // Giallo per processing
    messageDiv.classList.remove('hidden');

    document.getElementById('error_communication_subject').textContent = '';
    document.getElementById('error_communication_message').textContent = '';

    fetch('<?php echo BASE_URL . "/admin_send_communication.php"; ?>', { // Script da creare
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.textContent = data.message || 'Message sent successfully!';
            messageDiv.className = 'p-3 mb-2 rounded-md text-sm bg-green-600/30 border border-green-500 text-green-300';
            this.reset();
            setTimeout(() => { // Chiude la modale e mostra il flash globale
                 closeCommunicationModal();
                 showUserManagementFlash(data.message || 'Message sent successfully!', 'success');
            }, 1500);
        } else {
            messageDiv.textContent = data.message || 'Could not send message. Please check errors.';
            messageDiv.className = 'p-3 mb-2 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
             if (data.errors) {
                for (const key in data.errors) {
                    const errorField = document.getElementById(`error_${key}`);
                    if (errorField) {
                        errorField.textContent = data.errors[key];
                    }
                }
            }
        }
    })
    .catch(error => {
        console.error('Communication Error:', error);
        messageDiv.textContent = 'An unexpected network error occurred.';
        messageDiv.className = 'p-3 mb-2 rounded-md text-sm bg-red-600/30 border border-red-500 text-red-300';
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = 'Send Message';
    });
});

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
