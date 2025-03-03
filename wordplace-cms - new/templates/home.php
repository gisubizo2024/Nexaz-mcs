<div class="home-content">
    <div class="hero-section">
        <h1>Welcome to WordPlace CMS</h1>
        <p>A simple and powerful content management system</p>
    </div>
    
    <div class="posts-section">
        <h2>Latest Posts</h2>
        
        <?php if (empty($posts)): ?>
            <p>No posts found.</p>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <h3 class="post-title">
                            <a href="index.php?page=post&id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <div class="post-meta">
                            <span class="post-author">By <?php echo htmlspecialchars($post['username']); ?></span>
                            <span class="post-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                            <?php if ($post['category_name']): ?>
                                <span class="post-category"><?php echo htmlspecialchars($post['category_name']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="post-excerpt">
                            <?php echo substr(strip_tags($post['content']), 0, 150) . '...'; ?>
                        </div>
                        <a href="index.php?page=post&id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

