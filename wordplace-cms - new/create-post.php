<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$error = '';
$categories = getCategories();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    
    // Validate input
    if (empty($title) || empty($content)) {
        $error = 'Title and content are required';
    } else {
        // Create the post
        $result = createPost([
            'user_id' => $userId,
            'title' => $title,
            'content' => $content,
            'category_id' => $category,
            'status' => $status
        ]);
        
        if ($result) {
            // Redirect to dashboard
            header('Location: dashboard.php?published=1');
            exit;
        } else {
            $error = 'Failed to create post. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - WordPlace CMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="site-container">
        <!-- Header -->
        <header class="site-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <a href="index.php">WordPlace</a>
                    </div>
                    <nav class="main-nav">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <div class="post-editor">
                    <h1>Create New Post</h1>
                    
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="create-post.php" method="post">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea id="content" name="content" rows="15" required></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <select id="category" name="category">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Publish</button>
                            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> WordPlace CMS. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script src="assets/js/editor.js"></script>
</body>
</html>

