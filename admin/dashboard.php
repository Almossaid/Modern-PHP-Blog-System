<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';  // This defines isLoggedIn()
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get recent posts and stats
$recent_posts = getRecentPosts(5);
$total_posts = getTotalPosts();

$page_title = 'Dashboard';
include '../includes/header.php';
?>

<div class="dashboard">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="posts.php?action=create" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> New Post
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Stats Cards -->
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Posts</h5>
                            <h2 class="mb-0"><?php echo $total_posts; ?></h2>
                        </div>
                        <i class="bi bi-file-earmark-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Active User</h5>
                            <h2 class="mb-0">1</h2>
                        </div>
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Last Activity</h5>
                            <h2 class="mb-0">Now</h2>
                        </div>
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Recent Posts</h5>
        </div>
        <div class="card-body">
            <?php if (empty($recent_posts)): ?>
                <div class="alert alert-info">No posts found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_posts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                    <td class="text-nowrap">
                                        <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">
                                           <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="posts.php?action=edit&id=<?php echo $post['id']; ?>" 
                                           class="btn btn-sm btn-outline-secondary">
                                           <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="posts.php?action=delete&id=<?php echo $post['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this post?')">
                                           <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <a href="posts.php" class="btn btn-primary">View All Posts</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>