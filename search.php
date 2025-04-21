<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';

$page_title = 'Search Results';
$search_query = isset($_GET['q']) ? htmlspecialchars(trim($_GET['q'])) : '';
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$posts_per_page = 5;

if (empty($search_query)) {
    echo '<div class="alert alert-warning">Please enter a search term.</div>';
    exit;
}

// Prepare the search query
$search_terms = '%' . $search_query . '%';
$offset = ($current_page - 1) * $posts_per_page;

// Get total number of matching posts
$count_stmt = $pdo->prepare("SELECT COUNT(DISTINCT p.id) FROM posts p WHERE (p.title LIKE ? OR p.content LIKE ?)");
$count_stmt->execute([$search_terms, $search_terms]);
$total_posts = $count_stmt->fetchColumn();
$total_pages = ceil($total_posts / $posts_per_page);

// Get posts for current page
$stmt = $pdo->prepare("SELECT p.*, GROUP_CONCAT(c.name) as category_names 
FROM posts p 
LEFT JOIN post_categories pc ON p.id = pc.post_id 
LEFT JOIN categories c ON pc.category_id = c.id 
WHERE (p.title LIKE ? OR p.content LIKE ?) 
GROUP BY p.id 
ORDER BY p.created_at DESC 
LIMIT ? OFFSET ?");
$stmt->execute([$search_terms, $search_terms, $posts_per_page, $offset]);
$posts = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title mb-4">Search Results</h1>

            <!-- Search Form -->
            <form action="search.php" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search posts..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            <?php if ($search_query): ?>
                <p class="lead mb-4">Found <?php echo $total_posts; ?> result(s) for "<?php echo htmlspecialchars($search_query); ?>"</p>

                <?php if ($posts): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="card mb-4">
                            <div class="card-body">
                                <h2 class="card-title h4">
                                    <a href="post.php?id=<?php echo htmlspecialchars($post['id']); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h2>
                                <div class="text-muted mb-2">
                                    <small>
                                        Posted in 
                                        <?php 
                                        $categories = explode(',', $post['category_names']);
                                        $category_links = [];
                                        foreach ($categories as $category) {
                                            if (!empty($category)) {
                                                $category_links[] = '<a href="category.php?name=' . urlencode(trim($category)) . '" class="text-decoration-none">' . htmlspecialchars(trim($category)) . '</a>';
                                            }
                                        }
                                        echo !empty($category_links) ? implode(', ', $category_links) : 'Uncategorized';
                                        ?>
                                        on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                    </small>
                                </div>
                                <p class="card-text"><?php echo truncate_content(strip_tags($post['content']), 200); ?></p>
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>

                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Search results navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($current_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="search.php?q=<?php echo urlencode($search_query); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
                                        <a class="page-link" href="search.php?q=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($current_page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="search.php?q=<?php echo urlencode($search_query); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info">No posts found matching your search criteria.</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center">
                    <p class="lead">Enter your search query above to find blog posts.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
