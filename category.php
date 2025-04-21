<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isset($_GET['name'])) {
    header("Location: " . SITE_URL);
    exit;
}

$category_name = htmlspecialchars($_GET['name']);
$page_title = ucfirst($category_name) . ' Posts';

// Get posts for this category (you'll need to modify your database structure)
$posts = [];
$category_posts = $db->query("
    SELECT p.*, u.username as author 
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN post_categories pc ON p.id = pc.post_id
    JOIN categories c ON pc.category_id = c.id
    WHERE c.name = ?
    ORDER BY p.created_at DESC
", [$category_name]);

if ($category_posts) {
    $posts = $category_posts->fetch_all(MYSQLI_ASSOC);
}

include 'includes/header.php';
?>
  
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h1 class="card-title">Posts in <?php echo ucfirst($category_name); ?></h1>
                    
                    <?php if (empty($posts)): ?>
                        <div class="alert alert-info">No posts found in this category.</div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($posts as $post): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <?php if (strpos($post['content'], '<img') !== false): ?>
                                            <?php 
                                            preg_match('/<img[^>]+src="([^"]+)"/', $post['content'], $matches);
                                            if (!empty($matches[1])): ?>
                                                <img src="<?php echo htmlspecialchars($matches[1]); ?>" class="card-img-top" alt="Post image" style="height: 200px; object-fit: cover;">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h3 class="card-title h5">
                                                <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </h3>
                                            <div class="d-flex gap-3 text-muted small mb-3">
                                                <span><i class="bi bi-person"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                                                <span><i class="bi bi-calendar"></i> <?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                            </div>
                                            <p class="card-text"><?php echo substr(strip_tags($post['content']), 0, 150); ?>...</p>
                                            <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-sm btn-primary">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>