<?php
// Questo partial è incluso in dashboardAdmin.php per il tab 'overview' o di default.
// Assicura che $mysqli sia disponibile (da config.php)
// Assicura che l'utente sia admin (controllato da dashboardAdmin.php)
?>
<div class="p-4 sm:p-6 space-y-6">
    <h2 class="text-white text-2xl sm:text-3xl font-bold tracking-tight">Dashboard Overview</h2>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-[#353017] p-5 sm:p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Total Appointments</h3>
            <p id="statTotalAppointments" class="text-white text-3xl font-bold mt-1">Loading...</p>
            <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=appointments'; ?>" class="text-[#fcdd53] text-xs sm:text-sm mt-2 inline-block hover:underline">View All</a>
        </div>
        <div class="bg-[#353017] p-5 sm:p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Pending Appointments</h3>
            <p id="statPendingAppointments" class="text-white text-3xl font-bold mt-1">Loading...</p>
        </div>
        <div class="bg-[#353017] p-5 sm:p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Confirmed Appointments</h3>
            <p id="statConfirmedAppointments" class="text-white text-3xl font-bold mt-1">Loading...</p>
        </div>
        <div class="bg-[#353017] p-5 sm:p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Completed Appointments</h3>
            <p id="statCompletedAppointments" class="text-white text-3xl font-bold mt-1">Loading...</p>
        </div>
        <div class="bg-[#353017] p-5 sm:p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Total Users</h3>
            <p id="statTotalUsers" class="text-white text-3xl font-bold mt-1">Loading...</p>
             <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=users'; ?>" class="text-[#fcdd53] text-xs sm:text-sm mt-2 inline-block hover:underline">Manage Users</a>
        </div>
        <div class="bg-[#353017] p-5 sm:p-6 rounded-lg shadow">
            <h3 class="text-[#cdc28e] text-sm font-medium">Total Services</h3>
            <p id="statTotalServices" class="text-white text-3xl font-bold mt-1">Loading...</p>
            <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=services'; ?>" class="text-[#fcdd53] text-xs sm:text-sm mt-2 inline-block hover:underline">Manage Services</a>
        </div>
    </div>

    <!-- Quick Actions or Recent Activity could go here -->
    <div class="mt-8">
        <h3 class="text-white text-xl font-semibold mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-4">
            <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=add_booking'; ?>" class="bg-[#fcdd53] text-[#232010] hover:bg-[#fadc70] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Add New Appointment
            </a>
            <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=services#add'; ?>" class="bg-[#4a4321] text-white hover:bg-[#5f552a] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Add New Service
            </a>
             <a href="<?php echo BASE_URL . '/dashboardAdmin.php?tab=users'; ?>" class="bg-[#4a4321] text-white hover:bg-[#5f552a] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Manage Users
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function fetchDashboardStats() {
        // Fetch appointment stats
        fetch('<?php echo BASE_URL . "/get_all_bookings.php"; ?>')
            .then(response => response.json())
            .then(data => {
                if (data.stats) {
                    document.getElementById('statTotalAppointments').textContent = data.stats.total || 0;
                    document.getElementById('statPendingAppointments').textContent = data.stats.pending || 0;
                    document.getElementById('statConfirmedAppointments').textContent = data.stats.confirmed || 0;
                    document.getElementById('statCompletedAppointments').textContent = data.stats.completed || 0;
                } else if (data.error) {
                    console.error("Error fetching booking stats:", data.error);
                    ['statTotalAppointments', 'statPendingAppointments', 'statConfirmedAppointments', 'statCompletedAppointments'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = 'Error';
                    });
                }
            })
            .catch(error => {
                console.error('Network error fetching booking stats:', error);
                 ['statTotalAppointments', 'statPendingAppointments', 'statConfirmedAppointments', 'statCompletedAppointments'].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = 'N/A';
                    });
            });

        // Fetch user stats
        fetch('<?php echo BASE_URL . "/manage_users.php"; ?>') // Assuming this GET request returns all users
            .then(response => response.json())
            .then(data => {
                if (data.data) {
                    document.getElementById('statTotalUsers').textContent = data.data.length || 0;
                } else if (data.error) {
                    console.error("Error fetching user stats:", data.error);
                    const el = document.getElementById('statTotalUsers');
                    if (el) el.textContent = 'Error';
                }
            })
            .catch(error => {
                console.error('Network error fetching user stats:', error);
                const el = document.getElementById('statTotalUsers');
                if (el) el.textContent = 'N/A';
            });

        // Fetch service stats
        fetch('<?php echo BASE_URL . "/manage_services.php"; ?>') // Assuming this GET request returns all services
            .then(response => response.json())
            .then(data => {
                if (data.data) {
                    document.getElementById('statTotalServices').textContent = data.data.length || 0;
                } else if (data.error) {
                    console.error("Error fetching service stats:", data.error);
                    const el = document.getElementById('statTotalServices');
                    if (el) el.textContent = 'Error';
                }
            })
            .catch(error => {
                console.error('Network error fetching service stats:', error);
                 const el = document.getElementById('statTotalServices');
                if (el) el.textContent = 'N/A';
            });
    }

    fetchDashboardStats();
});
</script>
