<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /trees.php');
    exit;
}

$treeId = isset($_POST['tree_id']) ? (int)$_POST['tree_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!$treeId || $quantity < 1) {
    setFlashMessage('error', 'Invalid tree selection.');
    header('Location: /trees.php');
    exit;
}

$trees = loadJsonFile(TREES_FILE);
$treeName = '';

foreach ($trees as $tree) {
    if ($tree['id'] === $treeId) {
        $treeName = $tree['name'];
        break;
    }
}

if (empty($treeName)) {
    setFlashMessage('error', 'Tree not found.');
    header('Location: /trees.php');
    exit;
}

addToCart($treeId, $quantity);
setFlashMessage('success', '"' . $treeName . '" has been added to your cart!');

$redirect = $_POST['redirect'] ?? '/trees.php';
header('Location: ' . $redirect);
exit;
?>
