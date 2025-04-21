<div class="row">
    <?php if (empty($posts)): ?>
        <div class="col-12">
            <div class="alert alert-info">No posts found.</div>
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="col-lg-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (strpos($post['content'], '<img') !== false): ?>
                        <?php 
                        // Extract first image from content
                        preg_match('/<img[^>]+src="([^">]+)"/', $post['content'], $matches);
                        if (!empty($matches[1])): ?>
                            <img src="<?php echo htmlspecialchars($matches[1]); ?>" class="card-img-top" alt="Post image" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>
                        <div class="d-flex gap-3 text-muted small mb-3">
                            <span><i class="bi bi-person"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                            <span><i class="bi bi-calendar"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        
                        <!-- Enhanced content display -->
                        <div class="card-text post-preview">
                            <?php 
                            // Clean and truncate content
                            $content = strip_tags($post['content']);
                            $content = html_entity_decode($content);
                            $content = preg_replace('/\s+/', ' ', $content);
                            echo substr($content, 0, 300);
                            if (strlen($content) > 300) echo '...';
                            ?>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-primary">Read More</a>
                            <?php if (isLoggedIn()): ?>
                                <a href="<?php echo SITE_URL; ?>/admin/posts.php?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if ($total_posts > $per_page): ?>
    <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php 
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            if ($start_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                if ($start_page > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; 
            
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?page='.$total_pages.'">'.$total_pages.'</a></li>';
            }
            ?>
            
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>