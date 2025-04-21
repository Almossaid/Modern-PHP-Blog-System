<?php
require_once 'includes/config.php';
require_once 'includes/auth.php'; // Include auth.php before header.php
require_once 'includes/functions.php';

if (!isset($_GET['slug'])) {
    header("Location: " . SITE_URL);
    exit;
}

$post = getPostBySlug($_GET['slug']);

if (!$post) {
    header("Location: " . SITE_URL);
    exit;
}

$page_title = $post['title'];
include 'includes/header.php';
include 'templates/post.php';
include 'includes/footer.php';
?>