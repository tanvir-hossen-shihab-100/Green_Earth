<?php
require_once 'includes/config.php';
requireLogin();

$trees = loadJsonFile(TREES_FILE);
$users = loadJsonFile(USERS_FILE);
$categories = getCategories();

$treeCount = count($trees);
$categoryCount = count(array_unique(array_column($trees, 'category')));

$pageTitle = 'Dashboard';
require_once 'includes/header.php';
?>

<div class="dashboard-header">
    <h1>Welcome, <?php echo sanitize($_SESSION['username']); ?>!</h1>
    <p><?php echo isAdmin() ? 'Admin Dashboard' : 'User Dashboard'; ?></p>
</div>

<div class="container">
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3><?php echo $treeCount; ?></h3>
            <p>Total Trees</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $categoryCount; ?></h3>
            <p>Categories</p>
        </div>
        <?php if (isAdmin()): ?>
        <div class="stat-card">
            <h3><?php echo count($users); ?></h3>
            <p>Registered Users</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if (isAdmin()): ?>
    <div class="section-title">
        <h2>Manage Trees</h2>
        <p>Add, edit, or delete trees from the database</p>
    </div>

    <div class="admin-actions">
        <a href="/admin/add-tree.php" class="btn btn-green">+ Add New Tree</a>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Scientific Name</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($trees)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No trees found. Add your first tree!</td>
                </tr>
                <?php else: ?>
                <?php foreach ($trees as $tree): ?>
                <tr>
                    <td>
                        <img src="<?php echo sanitize($tree['image_url']); ?>" alt="<?php echo sanitize($tree['name']); ?>">
                    </td>
                    <td><?php echo sanitize($tree['name']); ?></td>
                    <td><em><?php echo sanitize($tree['scientific_name']); ?></em></td>
                    <td><?php echo sanitize($tree['category']); ?></td>
                    <td>
                        <a href="/admin/edit-tree.php?id=<?php echo $tree['id']; ?>" class="btn btn-green btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" 
                                data-id="<?php echo $tree['id']; ?>" 
                                data-name="<?php echo sanitize($tree['name']); ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete "<span id="deleteTreeName"></span>"?</p>
            <p>This action cannot be undone.</p>
            <form id="deleteForm" method="POST" action="/admin/delete-tree.php">
                <?php echo csrfField(); ?>
                <input type="hidden" id="deleteTreeId" name="tree_id">
                <div class="modal-actions">
                    <button type="button" class="btn" id="cancelDelete">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <?php else: ?>
    
    <div class="section-title">
        <h2>Browse Trees</h2>
        <p>Explore our collection of trees</p>
    </div>

    <div class="search-box">
        <input type="text" id="searchInput" class="search-input" placeholder="Search trees by name...">
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
        <?php foreach ($trees as $tree): ?>
        <div class="tree-card" 
             data-name="<?php echo sanitize($tree['name']); ?>" 
             data-category="<?php echo sanitize($tree['category']); ?>"
             data-scientific="<?php echo sanitize($tree['scientific_name']); ?>">
            <img src="<?php echo sanitize($tree['image_url']); ?>" alt="<?php echo sanitize($tree['name']); ?>" class="tree-card-image">
            <div class="tree-card-content">
                <h3><?php echo sanitize($tree['name']); ?></h3>
                <p class="scientific-name"><?php echo sanitize($tree['scientific_name']); ?></p>
                <span class="category"><?php echo sanitize($tree['category']); ?></span>
                <p class="description"><?php echo sanitize($tree['description']); ?></p>
                <div class="tree-card-actions">
                    <a href="/tree-details.php?id=<?php echo $tree['id']; ?>" class="btn btn-green btn-sm">View Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="noResults" class="no-results" style="display: none;">
        <h3>No trees found</h3>
        <p>Try adjusting your search or category filter</p>
    </div>

    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
