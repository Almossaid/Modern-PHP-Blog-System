<article class="blog-post">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div class="d-flex gap-4 text-muted mb-4">
                <span><i class="bi bi-person"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                <span><i class="bi bi-calendar"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
            </div>
            
            <div class="post-content">
                <?php echo $post['content']; ?>
            </div>
            
            <div class="mt-5">
                <a href="<?php echo SITE_URL; ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</article>