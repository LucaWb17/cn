<?php
// This file is largely superseded by config.php but can be kept for semantic separation
// or if more complex DB connection logic is needed in the future.
// For now, it will just re-require config.php if not already included.

if (!defined('DB_SERVER')) { // Check if config constants are defined
    // Adjust path if utils directory is structured differently or this file is moved
    $configPath = __DIR__ . '/../config.php';
    if (file_exists($configPath)) {
        require_once $configPath;
    } else {
        die("FATAL ERROR: config.php not found from db_connect.php. Path checked: " . $configPath);
    }
}

// $mysqli is already available globally from config.php
// You can add specific connection-related functions here if needed,
// for example, a function to get the $mysqli instance:
/*
function get_db_connection() {
    global $mysqli;
    if ($mysqli && $mysqli->ping()) {
        return $mysqli;
    } else {
        // Attempt to reconnect or handle error
        // This is a basic example; robust reconnection needs more logic
        $new_mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($new_mysqli->connect_error) {
            error_log("Failed to reconnect to DB: " . $new_mysqli->connect_error);
            return null;
        }
        $GLOBALS['mysqli'] = $new_mysqli; // Update global instance
        return $new_mysqli;
    }
}
*/

// For now, config.php handles the primary connection.
?>
