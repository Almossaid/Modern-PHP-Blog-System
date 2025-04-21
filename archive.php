<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';

// Get year and month from URL parameters
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');

// Validate year and month
if ($year < 1970 || $year > date('Y') || $month < 1 || $month > 12) {
    header('Location: ' . SITE_URL);
    exit;
}

// Set up pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get total posts for this archive
$stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?");
$stmt->execute([$year, $month]);
$total_posts = $stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_posts / $per_page);

// Get posts for current page
$stmt = $pdo->prepare("
    SELECT p.*, GROUP_CONCAT(c.name) as category_name 
    FROM posts p 
    LEFT JOIN post_categories pc ON p.id = pc.post_id 
    LEFT JOIN categories c ON pc.category_id = c.id 
    WHERE YEAR(p.created_at) = ? AND MONTH(p.created_at) = ? 
    GROUP BY p.id
    ORDER BY p.created_at DESC 
    LIMIT ? OFFSET ?
");
$stmt->execute([$year, $month, $per_page, $offset]);
$posts = $stmt->fetchAll();

// Get archive title
$archive_title = date('F Y', mktime(0, 0, 0, $month, 1, $year));

// Include header
require_once 'includes/header.php';
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="card-title mb-4">Archive: <?php echo htmlspecialchars($archive_title); ?></h1>
                    
                    <?php if (empty($posts)): ?>
                        <div class="alert alert-info">No posts found for this period.</div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="mb-4 pb-4 border-bottom">
                                <h2 class="h4">
                                    <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h2>
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-calendar"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                    <?php if ($post['category_name']): ?>
                                        <span class="mx-1">|</span>
                                        <i class="bi bi-folder"></i> 
                                        <a href="<?php echo SITE_URL; ?>/category.php?name=<?php echo urlencode($post['category_name']); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($post['category_name']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <p class="card-text"><?php echo htmlspecialchars($post['summary']); ?></p>
                                <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-primary btn-sm">Read More</a>
                            </article>
                        <?php endforeach; ?>

                        <?php if ($total_pages > 1): ?>
                            <nav aria-label="Archive navigation">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="<?php echo SITE_URL; ?>/archive.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&page=<?php echo $i; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>