<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$settings = getSettings();
$error = '';
$success = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSettings = [
        'site_title' => $_POST['site_title'],
        'site_description' => $_POST['site_description'],
        'posts_per_page' => $_POST['posts_per_page'],
        'allow_comments' => isset($_POST['allow_comments']) ? 'true' : 'false',
    ];
    
    if (updateSettings($newSettings)) {
        $success = "Settings updated successfully.";
        $settings = getSettings(); // Refresh settings
    } else {
        $error = "Failed to update settings.";
    }
}

$page_title = 'Site Settings - WordPlace CMS';
define('ABSPATH', __DIR__);
include 'templates/header.php';
?>

<div class="dashboard">
    <div class="dashboard-header">
        <h1>Site Settings</h1>
    </div>
    
    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="dashboard-content">
        <div class="dashboard-main">
            <form action="settings.php" method="post" class="settings-form">
                <div class="form-group">
                    <label for="site_title">Site Title</label>
                    <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="site_description">Site Description</label>
                    <textarea id="site_description" name="site_description" rows="3" required><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="posts_per_page">Posts Per Page</label>
                    <input type="number" id="posts_per_page" name="posts_per_page" value="<?php echo htmlspecialchars($settings['posts_per_page']); ?>" required min="1">
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="allow_comments" <?php echo $settings['allow_comments'] === 'true' ? 'checked' : ''; ?>>
                        Allow Comments
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>

