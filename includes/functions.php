<?php
require_once 'database.php';

// Generate slug from title
function generateSlug($title) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return $slug;
}

// Get recent posts
function getRecentPosts($limit = 5, $offset = 0) {
    $db = Database::getInstance(); // Get the Database instance
    $sql = "SELECT p.*, u.username as author 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC 
            LIMIT ? OFFSET ?";
    $result = $db->fetchAll($sql, [$limit, $offset]);
    return $result;
}

// Get post by slug
function getPostBySlug($slug) {
    $db = Database::getInstance(); // Get the Database instance
    $sql = "SELECT p.*, u.username as author 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.slug = ?";
    $result = $db->fetch($sql, [$slug]);
    return $result;
}

// Get total post count for pagination
function getTotalPosts() {
    $db = Database::getInstance(); // Get the Database instance
    $result = $db->fetch("SELECT COUNT(*) as total FROM posts");
    return $result['total'];
}

// Create a new post
function createPost($title, $summary, $content, $user_id, $category_id) {
    $db = Database::getInstance(); // Get the Database instance
    $slug = generateSlug($title);
    $sql = "INSERT INTO posts (user_id, title, slug, summary, content) VALUES (?, ?, ?, ?, ?)";
    $db->execute($sql, [$user_id, $title, $slug, $summary, $content]);
    $post_id = $db->lastInsertId();
    
    // Add category relationship
    $sql = "INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)";
    $db->execute($sql, [$post_id, $category_id]);
    
    return $post_id;
}

// Update an existing post
function updatePost($id, $title, $summary, $content, $category_id) {
    $db = Database::getInstance(); // Get the Database instance
    $slug = generateSlug($title);
    $sql = "UPDATE posts SET title = ?, slug = ?, summary = ?, content = ? WHERE id = ?";
    $db->execute($sql, [$title, $slug, $summary, $content, $id]);
    
    // Update category relationship
    $sql = "DELETE FROM post_categories WHERE post_id = ?";
    $db->execute($sql, [$id]);
    
    $sql = "INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)";
    $db->execute($sql, [$id, $category_id]);
    
    return $slug;
}

// Delete a post
function deletePost($id) {
    $db = Database::getInstance(); // Get the Database instance
    $sql = "DELETE FROM posts WHERE id = ?";
    return $db->execute($sql, [$id]);
}

// Truncate content to specified length while preserving words
function truncate_content($content, $length = 200) {
    if (strlen($content) <= $length) {
        return $content;
    }
    
    $pos = strrpos(substr($content, 0, $length), ' ');
    return substr($content, 0, $pos) . '...';
}
?>
