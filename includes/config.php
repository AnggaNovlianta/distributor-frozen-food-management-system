<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); 
define('DB_PASS', '');
define('DB_NAME', 'frozen_food_db');

// Site Configuration
define('SITE_NAME', 'Distribusi Frozen Food');
define('SITE_URL', 'http://localhost/frozen-food');

// Start session
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Include database connection
require_once 'database.php';
?>
