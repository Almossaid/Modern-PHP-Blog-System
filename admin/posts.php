<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';  // This defines isLoggedIn()
require_once '../includes/functions.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $summary = trim($_POST['summary']);
    $content = trim($_POST['content']);
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    
    if (empty($title) || empty($content) || empty($category_id)) {
        $_SESSION['error'] = "Title, content and category are required";
    } else {
        if ($action === 'create') {
            $post_id = createPost($title, $summary, $content, $_SESSION['user_id'], $category_id);
            $_SESSION['success'] = "Post created successfully!";
            header("Location: posts.php?action=edit&id=" . $post_id);
            exit;
        } elseif ($action === 'edit' && $id > 0) {
            updatePost($id, $title, $summary, $content, $category_id);
            $_SESSION['success'] = "Post updated successfully!";
            header("Location: posts.php?action=edit&id=$id");
            exit;
        }
    }
}

// Handle delete action
if ($action === 'delete' && $id > 0) {
    deletePost($id);
    $_SESSION['success'] = "Post deleted successfully!";
    header("Location: posts.php");
    exit;
}

// Get post and its category for editing
$post = null;
$post_category = null;
if ($action === 'edit' && $id > 0) {
    $result = $db->query("SELECT p.*, pc.category_id FROM posts p LEFT JOIN post_categories pc ON p.id = pc.post_id WHERE p.id = ?", [$id]);
    $post = $result->fetch_assoc();
    if ($post) {
        $post_category = $post['category_id'];
    }
    
    if (!$post) {
        $_SESSION['error'] = "Post not found";
        header("Location: posts.php");
        exit;
    }
}

$page_title = ucfirst($action) . ' Post';
include '../includes/header.php';

// Display messages
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi <?php 
                echo $action === 'create' ? 'bi-plus-circle' : 
                     ($action === 'edit' ? 'bi-pencil' : 'bi-list-ul'); 
            ?>"></i>
            <?php echo ucfirst($action); ?> Post
        </h2>
        <a href="posts.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Posts
        </a>
    </div>

    <?php if ($action === 'list'): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Posts</h5>
                <a href="posts.php?action=create" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg"></i> New Post
                </a>
            </div>
            <div class="card-body">
                <?php
                $posts = getRecentPosts(100); // Get all posts
                if (empty($posts)): ?>
                    <div class="alert alert-info">No posts found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['title']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($p['created_at'])); ?></td>
                                        <td>
                                            <span class="badge bg-success">Published</span>
                                        </td>
                                        <td class="text-nowrap">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $p['slug']; ?>" 
                                                   class="btn btn-outline-primary" 
                                                   target="_blank">
                                                   <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="posts.php?action=edit&id=<?php echo $p['id']; ?>" 
                                                   class="btn btn-outline-secondary">
                                                   <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="posts.php?action=delete&id=<?php echo $p['id']; ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this post?')">
                                                   <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="title" 
                               value="<?php echo isset($post['title']) ? htmlspecialchars($post['title']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="summary" class="form-label">Summary</label>
                        <textarea class="form-control" name="summary" id="summary" rows="3"><?php 
                            echo isset($post['summary']) ? htmlspecialchars($post['summary']) : ''; 
                        ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" name="category_id" id="category_id" required>
                            <option value="">Select a category</option>
                            <?php
                            $categories = $db->query("SELECT * FROM categories ORDER BY name");
                            while ($category = $categories->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo (isset($post_category) && $post_category == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>


                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" name="content" id="content" rows="10"><?php 
                            echo isset($post['content']) ? htmlspecialchars($post['content']) : ''; 
                        ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- This area is for rich text if you want to use it -->
      
        <!-- TinyMCE Editor -->
         <!--
        <script src="https://cdn.tiny.cloud/1/hv0oaa2z0h1ufvhrl81sg0ixkaud53yci1q1bl2wnsibzo60/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="../assests/js/editor.js"></script>
        <style>
            .tox-tinymce {
                border-radius: var(--border-radius);
                border-color: #e2e8f0;
            }
        </style>
        -->
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>