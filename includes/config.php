<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_db');

// Site configuration
define('SITE_TITLE', 'Modern PHP Blog');
define('SITE_URL', '/www/blog'); // Using relative path for better portability
define('POSTS_PER_PAGE', 6);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session
session_start();

// Timezone
date_default_timezone_set('UTC');

// Initialize database connection
require_once 'database.php';
$db = Database::getInstance();
?>