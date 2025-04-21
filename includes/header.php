<?php
// Include required files
require_once 'config.php';
require_once 'database.php';
require_once 'auth.php';

// Initialize database connection
$db = Database::getInstance();

// This is the main header template file
// It should only contain the common HTML header structure

// Ensure page title is set
if (!isset($page_title)) {
    $page_title = 'Blog';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - My Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/assests/css/style.css" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>">My Blog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Categories
                            </a>
                            <ul class="dropdown-menu" style="z-index: 1021;">
                                <?php
                                $categories = $db->query("SELECT * FROM categories ORDER BY name");
                                while ($category = $categories->fetch_assoc()): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo SITE_URL; ?>/category.php?name=<?php echo $category['slug']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </li>
                        <?php if (isLoggedIn()): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/admin/logout.php">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo SITE_URL; ?>/admin/login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto social-links">
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-facebook"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-twitter"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4">
        <?php if (isset($page_message)): ?>
            <div class="alert alert-<?php echo $page_message['type']; ?>">
                <?php echo htmlspecialchars($page_message['text']); ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-8">