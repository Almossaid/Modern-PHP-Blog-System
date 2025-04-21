<div class="col-lg-4">
<?php
require_once 'functions.php';
?>
    <div class="sidebar sticky-top" style="top: 1rem;">
        <!-- Search Widget -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Search</h5>
                <form action="<?php echo SITE_URL; ?>/search.php" method="get">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search...">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Widget -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?php echo SITE_URL; ?>/category.php?name=technology">Technology</a>
                        <span class="badge bg-primary rounded-pill">14</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?php echo SITE_URL; ?>/category.php?name=programming">Programming</a>
                        <span class="badge bg-primary rounded-pill">23</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="<?php echo SITE_URL; ?>/category.php?name=design">Design</a>
                        <span class="badge bg-primary rounded-pill">8</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Recent Posts Widget -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Recent Posts</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach (getRecentPosts(5) as $recent_post): ?>
                        <li class="list-group-item">
                            <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $recent_post['slug']; ?>">
                                <?php echo htmlspecialchars($recent_post['title']); ?>
                            </a>
                            <small class="text-muted d-block"><?php echo date('M j', strtotime($recent_post['created_at'])); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Archives Widget -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Archives</h5>
                <ul class="list-group list-group-flush">
                    <?php
                    $archives = $pdo->query("
                        SELECT YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count 
                        FROM posts 
                        GROUP BY YEAR(created_at), MONTH(created_at) 
                        ORDER BY created_at DESC
                    ")->fetchAll();
                    
                    foreach ($archives as $archive): 
                        $month_name = date('F', mktime(0, 0, 0, $archive['month'], 1));
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?php echo SITE_URL; ?>/archive.php?year=<?php echo $archive['year']; ?>&month=<?php echo $archive['month']; ?>">
                                <?php echo $month_name . ' ' . $archive['year']; ?>
                            </a>
                            <span class="badge bg-primary rounded-pill"><?php echo $archive['count']; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>


    </div>
</div>