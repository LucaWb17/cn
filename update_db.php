<?php
require_once 'config.php';

$sql = "ALTER TABLE users
        ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL,
        ADD COLUMN reset_token_expires_at DATETIME DEFAULT NULL";

if ($mysqli->query($sql) === TRUE) {
    echo "Table 'users' updated successfully.";
} else {
    echo "Error updating table: " . $mysqli->error;
}

$mysqli->close();
?>
