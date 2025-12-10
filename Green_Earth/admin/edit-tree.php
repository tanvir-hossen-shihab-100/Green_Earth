<?php
require_once '../includes/config.php';
requireAdmin();

$treeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$treeId) {
    header('Location: /dashboard.php');
    exit;
}

$trees = loadJsonFile(TREES_FILE);
$tree = null;
$treeIndex = null;

foreach ($trees as $index => $t) {
    if ($t['id'] === $treeId) {
        $tree = $t;
        $treeIndex = $index;
        break;
    }
}

if (!$tree) {
    setFlashMessage('error', 'Tree not found.');
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $scientificName = trim($_POST['scientific_name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (empty($name) || empty($scientificName) || empty($category) || empty($imageUrl) || empty($description)) {
            $error = 'All fields are required.';
        } elseif ($price <= 0) {
            $error = 'Price must be greater than 0.';
        } elseif (!validateCategories($category)) {
            $error = 'Invalid category selected.';
        } else {
            $trees[$treeIndex]['name'] = $name;
            $trees[$treeIndex]['scientific_name'] = $scientificName;
            $trees[$treeIndex]['category'] = $category;
            $trees[$treeIndex]['price'] = $price;
            $trees[$treeIndex]['image_url'] = $imageUrl;
            $trees[$treeIndex]['description'] = $description;
            $trees[$treeIndex]['updated_at'] = date('Y-m-d H:i:s');
            
            if (saveJsonFile(TREES_FILE, $trees)) {
                setFlashMessage('success', 'Tree "' . $name . '" has been updated successfully!');
                header('Location: /dashboard.php');
                exit;
            } else {
                $error = 'Failed to update tree. Please try again.';
            }
        }
    }
}

$categories = getCategories();
array_shift($categories);

$pageTitle = 'Edit Tree';
require_once '../includes/header.php';
?>

<div class="container">
    <a href="/dashboard.php" class="back-link">← Back to Dashboard</a>
    
    <div class="login-box" style="max-width: 600px;">
        <h1>Edit Tree</h1>
        
        <?php if ($error): ?>
        <div class="error-message"><?php echo sanitize($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" data-validate>
            <?php echo csrfField(); ?>
            <div class="form-group">
                <label for="name">Tree Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo sanitize($tree['name']); ?>"
                       placeholder="e.g., Mango Tree">
            </div>
            
            <div class="form-group">
                <label for="scientific_name">Scientific Name *</label>
                <input type="text" id="scientific_name" name="scientific_name" required 
                       value="<?php echo sanitize($tree['scientific_name']); ?>"
                       placeholder="e.g., Mangifera indica">
            </div>
            
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat; ?>" 
                            <?php echo ($tree['category'] === $cat) ? 'selected' : ''; ?>>
                        <?php echo $cat; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="price">Price (BDT) *</label>
                <input type="number" id="price" name="price" required min="1" step="1"
                       value="<?php echo sanitize($tree['price'] ?? 0); ?>"
                       placeholder="e.g., 500">
            </div>
            
            <div class="form-group">
                <label for="image_url">Image URL *</label>
                <input type="url" id="image_url" name="image_url" required 
                       value="<?php echo sanitize($tree['image_url']); ?>"
                       placeholder="https://example.com/image.jpg">
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required 
                          placeholder="Describe the tree, its characteristics, uses, etc."><?php echo sanitize($tree['description']); ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-green">Update Tree</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
