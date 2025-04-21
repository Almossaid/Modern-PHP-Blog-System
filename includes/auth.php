<?php
require_once 'config.php';
require_once 'database.php';

// Initialize database instance
$db = Database::getInstance();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function login($username, $password) {
    global $db;
    
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $result = $db->query($sql, [$username]);
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }
    
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

function registerUser($username, $email, $password) {
    global $db;
    
    // Check if username or email already exists
    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $result = $db->query($sql, [$username, $email]);
    
    if ($result->num_rows > 0) {
        return false;
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert new user
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $db->query($sql, [$username, $email, $hashedPassword]);
    
    return true;
}
?>