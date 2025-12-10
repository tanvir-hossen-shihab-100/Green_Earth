<?php
require_once 'includes/config.php';

$treeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$treeId) {
    header('Location: /trees.php');
    exit;
}

$trees = loadJsonFile(TREES_FILE);
$tree = null;

foreach ($trees as $t) {
    if ($t['id'] === $treeId) {
        $tree = $t;
        break;
    }
}

if (!$tree) {
    setFlashMessage('error', 'Tree not found.');
    header('Location: /trees.php');
    exit;
}

$pageTitle = $tree['name'];
require_once 'includes/header.php';
?>

<div class="container">
    <a href="/trees.php" class="back-link">← Back to Trees</a>
    
    <div class="tree-detail">
        <img src="<?php echo sanitize($tree['image_url']); ?>" alt="<?php echo sanitize($tree['name']); ?>" class="tree-detail-image">
        <div class="tree-detail-content">
            <h1><?php echo sanitize($tree['name']); ?></h1>
            <p class="scientific-name"><?php echo sanitize($tree['scientific_name']); ?></p>
            <span class="category"><?php echo sanitize($tree['category']); ?></span>
            <p class="price-tag">৳<?php echo formatPrice($tree['price'] ?? 0); ?></p>
            <p class="description"><?php echo sanitize($tree['description']); ?></p>
            
            <form method="POST" action="/add-to-cart.php" class="add-to-cart-form">
                <input type="hidden" name="tree_id" value="<?php echo $tree['id']; ?>">
                <input type="hidden" name="redirect" value="/tree-details.php?id=<?php echo $tree['id']; ?>">
                <button type="submit" class="btn btn-green">Add to Cart</button>
                <a href="/trees.php" class="btn" style="margin-left: 10px;">Continue Shopping</a>
            </form>
            
            <?php if (isAdmin()): ?>
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <a href="/admin/edit-tree.php?id=<?php echo $tree['id']; ?>" class="btn btn-green">Edit Tree</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
