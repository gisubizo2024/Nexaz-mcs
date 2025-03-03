<div class="single-post">
    <article class="post">
        <header class="post-header">
            <h1 class="post-title"><?php echo htmlspecialchars($singlePost['title']); ?></h1>
            <div class="post-meta">
                <span class="post-author">By <?php echo htmlspecialchars($singlePost['username']); ?></span>
                <span class="post-date"><?php echo date('M d, Y', strtotime($singlePost['created_at'])); ?></span>
                <?php if ($singlePost['category_name']): ?>
                    <span class="post-category"><?php echo htmlspecialchars($singlePost['category_name']); ?></span>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="post-content">
            <?php echo nl2br(htmlspecialchars($singlePost['content'])); ?>
        </div>
        
        <footer class="post-footer">
            <div class="post-actions">
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
                
                <?php if ($isLoggedIn && ($singlePost['user_id'] == $_SESSION['user_id'] || $isAdmin)): ?>
                    <a href="edit-post.php?id=<?php echo $singlePost['id']; ?>" class="btn btn-primary">Edit Post</a>
                <?php endif; ?>
            </div>
        </footer>
    </article>
</div>

