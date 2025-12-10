<?php
require_once 'includes/config.php';

$trees = loadJsonFile(TREES_FILE);
$categories = getCategories();

$pageTitle = 'Trees';
require_once 'includes/header.php';
?>

<section class="hero" style="padding: 60px 20px;">
    <div class="hero-content">
        <h1>Our Tree Collection</h1>
        <p>Discover and buy a variety of trees from around the world</p>
    </div>
</section>

<div class="container">
    <div class="search-box">
        <input type="text" id="searchInput" class="search-input" placeholder="Search trees by name or scientific name...">
    </div>

    <div class="categories">
        <?php foreach ($categories as $category): ?>
        <button class="category-btn <?php echo $category === 'All Trees' ? 'active' : ''; ?>" 
                data-category="<?php echo $category; ?>">
            <?php echo $category; ?>
        </button>
        <?php endforeach; ?>
    </div>

    <div class="trees-grid">
        <?php if (empty($trees)): ?>
        <div class="no-results">
            <h3>No trees available</h3>
            <p>Check back later for new additions</p>
        </div>
        <?php else: ?>
        <?php foreach ($trees as $tree): ?>
        <div class="tree-card" 
             data-name="<?php echo sanitize($tree['name']); ?>" 
             data-category="<?php echo sanitize($tree['category']); ?>"
             data-scientific="<?php echo sanitize($tree['scientific_name']); ?>">
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
                        <input type="hidden" name="redirect" value="/trees.php">
                        <button type="submit" class="btn btn-green btn-sm btn-block">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="noResults" class="no-results" style="display: none;">
        <h3>No trees found</h3>
        <p>Try adjusting your search or category filter</p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
