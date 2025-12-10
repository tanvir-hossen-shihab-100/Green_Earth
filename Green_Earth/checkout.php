<?php
require_once 'includes/config.php';

$cart = getCart();

if (empty($cart)) {
    setFlashMessage('error', 'Your cart is empty.');
    header('Location: /cart.php');
    exit;
}

$trees = loadJsonFile(TREES_FILE);
$cartItems = [];
foreach ($cart as $treeId => $quantity) {
    foreach ($trees as $tree) {
        if ($tree['id'] == $treeId) {
            $tree['quantity'] = $quantity;
            $tree['subtotal'] = ($tree['price'] ?? 0) * $quantity;
            $cartItems[] = $tree;
            break;
        }
    }
}

$total = getCartTotal();
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name) || empty($email) || empty($phone) || empty($address)) {
            $error = 'All fields are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $orders = loadJsonFile(ORDERS_FILE);
            
            $maxId = 0;
            foreach ($orders as $order) {
                if ($order['id'] > $maxId) {
                    $maxId = $order['id'];
                }
            }
            
            $orderItems = [];
            foreach ($cartItems as $item) {
                $orderItems[] = [
                    'tree_id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal']
                ];
            }
            
            $newOrder = [
                'id' => $maxId + 1,
                'customer_name' => $name,
                'customer_email' => $email,
                'customer_phone' => $phone,
                'customer_address' => $address,
                'items' => $orderItems,
                'total' => $total,
                'status' => 'pending',
                'user_id' => $_SESSION['user_id'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $orders[] = $newOrder;
            
            if (saveJsonFile(ORDERS_FILE, $orders)) {
                clearCart();
                $success = true;
            } else {
                $error = 'Failed to place order. Please try again.';
            }
        }
    }
}

$pageTitle = 'Checkout';
require_once 'includes/header.php';
?>

<div class="container">
    <?php if ($success): ?>
    <div class="order-success">
        <div class="success-icon">✓</div>
        <h2>Order Placed Successfully!</h2>
        <p>Thank you for your order. We will contact you shortly to confirm your purchase.</p>
        <a href="/trees.php" class="btn btn-green">Continue Shopping</a>
    </div>
    <?php else: ?>
    
    <div class="section-title">
        <h2>Checkout</h2>
        <p>Complete your order</p>
    </div>
    
    <div class="checkout-container">
        <div class="checkout-form">
            <h3>Shipping Information</h3>
            
            <?php if ($error): ?>
            <div class="error-message"><?php echo sanitize($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" data-validate>
                <?php echo csrfField(); ?>
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? sanitize($_POST['name']) : (isset($_SESSION['username']) ? sanitize($_SESSION['username']) : ''); ?>"
                           placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : (isset($_SESSION['email']) ? sanitize($_SESSION['email']) : ''); ?>"
                           placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?php echo isset($_POST['phone']) ? sanitize($_POST['phone']) : ''; ?>"
                           placeholder="Enter your phone number">
                </div>
                
                <div class="form-group">
                    <label for="address">Delivery Address *</label>
                    <textarea id="address" name="address" required 
                              placeholder="Enter your complete delivery address"><?php echo isset($_POST['address']) ? sanitize($_POST['address']) : ''; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-green btn-block">Place Order - ৳<?php echo formatPrice($total); ?></button>
            </form>
        </div>
        
        <div class="order-summary">
            <h3>Order Summary</h3>
            <div class="summary-items">
                <?php foreach ($cartItems as $item): ?>
                <div class="summary-item">
                    <img src="<?php echo sanitize($item['image_url']); ?>" alt="<?php echo sanitize($item['name']); ?>">
                    <div class="summary-item-info">
                        <span class="item-name"><?php echo sanitize($item['name']); ?></span>
                        <span class="item-qty">Qty: <?php echo $item['quantity']; ?></span>
                    </div>
                    <span class="item-price">৳<?php echo formatPrice($item['subtotal']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span class="total-price">৳<?php echo formatPrice($total); ?></span>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
