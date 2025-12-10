<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cart.php');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    setFlashMessage('error', 'Invalid request. Please try again.');
    header('Location: /cart.php');
    exit;
}

$treeId = isset($_POST['tree_id']) ? (int)$_POST['tree_id'] : 0;
$action = $_POST['action'] ?? '';

if (!$treeId) {
    header('Location: /cart.php');
    exit;
}

$cart = getCart();

switch ($action) {
    case 'increase':
        $currentQty = $cart[$treeId] ?? 0;
        updateCartQuantity($treeId, $currentQty + 1);
        break;
        
    case 'decrease':
        $currentQty = $cart[$treeId] ?? 0;
        if ($currentQty > 1) {
            updateCartQuantity($treeId, $currentQty - 1);
        } else {
            removeFromCart($treeId);
        }
        break;
        
    case 'remove':
        removeFromCart($treeId);
        setFlashMessage('success', 'Item removed from cart.');
        break;
}

header('Location: /cart.php');
exit;
?>
