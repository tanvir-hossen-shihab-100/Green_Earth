<?php
require_once 'includes/config.php';
$pageTitle = 'Home';
$trees = loadJsonFile(TREES_FILE);
$featuredTrees = array_slice($trees, 0, 6);
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Plant a Tree, Grow a Future</h1>
        <p>Join our mission to plant 1 million trees and make the Earth greener for future generations.</p>
        <a href="/trees.php" class="btn btn-primary">Explore Trees</a>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <h2>Why Plant Trees?</h2>
        <p>Discover the incredible benefits of tree planting</p>
    </div>
    
    <div class="features">
        <div class="feature-card">
            <div class="feature-icon">🌍</div>
            <h3>Combat Climate Change</h3>
            <p>Trees absorb CO2 and release oxygen, helping to reduce greenhouse gases and fight global warming.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🦋</div>
            <h3>Protect Wildlife</h3>
            <p>Forests provide essential habitats for countless species of birds, insects, and animals.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💧</div>
            <h3>Preserve Water</h3>
            <p>Trees help maintain the water cycle, prevent soil erosion, and keep our rivers and streams clean.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🏡</div>
            <h3>Support Communities</h3>
            <p>Tree planting creates jobs, provides resources, and improves the quality of life for local communities.</p>
        </div>
    </div>
</section>

<section class="about-section">
    <div class="about-content">
        <div class="about-text">
            <h2>About Green Earth</h2>
            <p>Green Earth is a global tree plantation initiative dedicated to fighting climate change. Since our start, we've planted over 500,000 trees worldwide.</p>
            <p>By joining our campaign, you help restore forests, create habitats for wildlife, and combat global warming.</p>
            <ul>
                <li>Restoration of natural habitats</li>
                <li>Improvement of air quality</li>
                <li>Support for local communities</li>
                <li>Educational programs for sustainability</li>
            </ul>
        </div>
        <div class="about-image">
            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=500" alt="Green forest">
        </div>
    </div>
</section>

<section class="container">
    <div class="section-title">
        <h2>Featured Trees</h2>
        <p>Explore some of our popular tree varieties</p>
    </div>
    
    <div class="trees-grid">
        <?php foreach ($featuredTrees as $tree): ?>
        <div class="tree-card">
            <img src="<?php echo sanitize($tree['image_url']); ?>" alt="<?php echo sanitize($tree['name']); ?>" class="tree-card-image">
            <div class="tree-card-content">
                <h3><?php echo sanitize($tree['name']); ?></h3>
                <p class="scientific-name"><?php echo sanitize($tree['scientific_name']); ?></p>
                <div class="tree-card-meta">
                    <span class="category"><?php echo sanitize($tree['category']); ?></span>
                    <span class="price">৳<?php echo formatPrice($tree['price'] ?? 0); ?></span>
                </div>
                <p class="description"><?php echo sanitize($tree['description']); ?></p>
                <div class="tree-card-actions">
                    <a href="/tree-details.php?id=<?php echo $tree['id']; ?>" class="btn btn-sm">Details</a>
                    <form method="POST" action="/add-to-cart.php" style="flex: 1;">
                        <input type="hidden" name="tree_id" value="<?php echo $tree['id']; ?>">
                        <input type="hidden" name="redirect" value="/">
                        <button type="submit" class="btn btn-green btn-sm btn-block">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div style="text-align: center; margin-top: 40px;">
        <a href="/trees.php" class="btn btn-green">View All Trees</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
