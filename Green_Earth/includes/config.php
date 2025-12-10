<?php
session_start();

define('BASE_PATH', dirname(__DIR__) . '/');
define('DATA_PATH', BASE_PATH . 'data/');
define('USERS_FILE', DATA_PATH . 'users.json');
define('TREES_FILE', DATA_PATH . 'trees.json');

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

function loadJsonFile($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

function saveJsonFile($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: /dashboard.php');
        exit;
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function getCategories() {
    return [
        'All Trees',
        'Fruit Trees',
        'Flowering Trees',
        'Shade Trees',
        'Medicinal Trees',
        'Timber Trees',
        'Evergreen Trees',
        'Ornamental Trees',
        'Bamboo',
        'Climbers',
        'Aquatic Plants'
    ];
}

function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

function validateCategories($category) {
    $validCategories = getCategories();
    array_shift($validCategories);
    return in_array($category, $validCategories);
}

function getCart() {
    return $_SESSION['cart'] ?? [];
}

function addToCart($treeId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$treeId])) {
        $_SESSION['cart'][$treeId] += $quantity;
    } else {
        $_SESSION['cart'][$treeId] = $quantity;
    }
}

function removeFromCart($treeId) {
    if (isset($_SESSION['cart'][$treeId])) {
        unset($_SESSION['cart'][$treeId]);
    }
}

function updateCartQuantity($treeId, $quantity) {
    if ($quantity <= 0) {
        removeFromCart($treeId);
    } else {
        $_SESSION['cart'][$treeId] = $quantity;
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
}

function getCartCount() {
    $cart = getCart();
    return array_sum($cart);
}

function getCartTotal() {
    $cart = getCart();
    $trees = loadJsonFile(TREES_FILE);
    $total = 0;
    
    foreach ($cart as $treeId => $quantity) {
        foreach ($trees as $tree) {
            if ($tree['id'] == $treeId) {
                $price = $tree['price'] ?? 0;
                $total += $price * $quantity;
                break;
            }
        }
    }
    
    return $total;
}

function formatPrice($price) {
    return number_format($price, 0);
}

define('ORDERS_FILE', DATA_PATH . 'orders.json');
?>
