<?php
require_once '../includes/config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard.php');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlashMessage('error', 'Invalid request. Please try again.');
    header('Location: /dashboard.php');
    exit;
}

$treeId = isset($_POST['tree_id']) ? (int)$_POST['tree_id'] : 0;

if (!$treeId) {
    setFlashMessage('error', 'Invalid tree ID.');
    header('Location: /dashboard.php');
    exit;
}

$trees = loadJsonFile(TREES_FILE);
$treeName = '';
$newTrees = [];

foreach ($trees as $tree) {
    if ($tree['id'] === $treeId) {
        $treeName = $tree['name'];
    } else {
        $newTrees[] = $tree;
    }
}

if (empty($treeName)) {
    setFlashMessage('error', 'Tree not found.');
    header('Location: /dashboard.php');
    exit;
}

if (saveJsonFile(TREES_FILE, $newTrees)) {
    setFlashMessage('success', 'Tree "' . $treeName . '" has been deleted successfully!');
} else {
    setFlashMessage('error', 'Failed to delete tree. Please try again.');
}

header('Location: /dashboard.php');
exit;
?>
