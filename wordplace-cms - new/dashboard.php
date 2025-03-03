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
$username = $_SESSION['username'];
$userRole = $_SESSION['user_role'];

// Get user's posts
$userPosts = getUserPosts($userId);

// Handle post deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $postId = $_GET['id'];
    
    // Check if the post belongs to the user or if user is admin
    if (isPostOwner($userId, $postId) || $userRole === 'admin') {
        if (deletePost($postId)) {
            // Redirect to refresh the page
            header('Location: dashboard.php?deleted=1');
            exit;
        }
    }
}

// Get notification message
$notification = '';
if (isset($_GET['deleted']) && $_GET['deleted'] === '1') {
    $notification = 'Post deleted successfully';
} elseif (isset($_GET['published']) && $_GET['published'] === '1') {
    $notification = 'Post published successfully';
} elseif (isset($_GET['updated']) && $_GET['updated'] === '1') {
    $notification = 'Post updated successfully';
}
$page_title = 'Dashboard - WordPlace CMS';
define('ABSPATH', __DIR__);
include 'templates/header.php';
?>
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
                            <li><a href="dashboard.php" class="active">Dashboard</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="site-content">
            <div class="container">
                <div class="dashboard">
                    <div class="dashboard-header">
                        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                        <a href="create-post.php" class="btn btn-primary">Create New Post</a>
                    </div>
                    
                    <?php if ($notification): ?>
                        <div class="notification"><?php echo $notification; ?></div>
                    <?php endif; ?>
                    
                    <div class="dashboard-content">
                        <div class="dashboard-sidebar">
                            <ul class="dashboard-menu">
                                <li><a href="dashboard.php" class="active">Posts</a></li>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <li><a href="manage-users.php">Users</a></li>
                                    <li><a href="settings.php">Settings</a></li>
                                <?php endif; ?>
                                <li><a href="profile.php">Profile</a></li>
                            </ul>
                        </div>
                        
                        <div class="dashboard-main">
                            <h2>Your Posts</h2>
                            
                            <?php if (empty($userPosts)): ?>
                                <p>You haven't created any posts yet.</p>
                            <?php else: ?>
                                <table class="posts-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userPosts as $post): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                                <td><?php echo ucfirst($post['status']); ?></td>
                                                <td class="actions">
                                                    <a href="index.php?page=post&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-view">View</a>
                                                    <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                                                    <a href="dashboard.php?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
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
<?php
include 'templates/footer.php';
?>

