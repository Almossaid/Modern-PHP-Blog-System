<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Pagination logic
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = POSTS_PER_PAGE;
$offset = ($current_page > 1) ? ($current_page - 1) * $per_page : 0;

// Get posts and total count
$posts = getRecentPosts($per_page, $offset);
$total_posts = getTotalPosts();
$total_pages = ceil($total_posts / $per_page);

// Ensure current page is within valid range
if ($current_page < 1) {
    $current_page = 1;
} elseif ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

$page_title = 'Home';
include 'includes/header.php';
include 'templates/home.php';
include 'includes/footer.php';
?>