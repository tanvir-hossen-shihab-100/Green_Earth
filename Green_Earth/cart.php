<?php
require_once 'includes/config.php';

$cart = getCart();
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

$pageTitle = 'Shopping Cart';
require_once 'includes/header.php';
?>

<div class="container">
    <div class="section-title">
        <h2>🛒 Your Shopping Cart</h2>
        <p>Review your selected trees before checkout</p>
    </div>

    <?php if (empty($cartItems)): ?>
    <div class="empty-cart">
        <div class="empty-cart-icon">🌱</div>
        <h3>Your cart is empty</h3>
        <p>Start shopping and add some trees to your cart!</p>
        <a href="/trees.php" class="btn btn-green">Browse Trees</a>
    </div>
    <?php else: ?>
    
    <div class="cart-container">
        <div class="cart-items">
            <?php foreach ($cartItems as $item): ?>
            <div class="cart-item">
                <img src="<?php echo sanitize($item['image_url']); ?>" alt="<?php echo sanitize($item['name']); ?>" class="cart-item-image">
                <div class="cart-item-details">
                    <h3><?php echo sanitize($item['name']); ?></h3>
                    <p class="scientific-name"><?php echo sanitize($item['scientific_name']); ?></p>
                    <span class="category"><?php echo sanitize($item['category']); ?></span>
                </div>
                <div class="cart-item-price">
                    <span class="price">৳<?php echo formatPrice($item['price'] ?? 0); ?></span>
                </div>
                <div class="cart-item-quantity">
                    <form method="POST" action="/update-cart.php" class="quantity-form">
                        <?php echo csrfField(); ?>
                        <input type="hidden" name="tree_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="action" value="decrease" class="qty-btn">-</button>
                        <span class="qty-value"><?php echo $item['quantity']; ?></span>
                        <button type="submit" name="action" value="increase" class="qty-btn">+</button>
                    </form>
                </div>
                <div class="cart-item-subtotal">
                    <span class="subtotal">৳<?php echo formatPrice($item['subtotal']); ?></span>
                </div>
                <form method="POST" action="/update-cart.php">
                    <?php echo csrfField(); ?>
                    <input type="hidden" name="tree_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" name="action" value="remove" class="remove-btn">✕</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="cart-summary">
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Items (<?php echo getCartCount(); ?>)</span>
                <span>৳<?php echo formatPrice($total); ?></span>
            </div>
            <div class="summary-row total-row">
                <span>Total</span>
                <span class="total-price">৳<?php echo formatPrice($total); ?></span>
            </div>
            <a href="/checkout.php" class="btn btn-green btn-block">Proceed to Checkout</a>
            <a href="/trees.php" class="btn btn-block" style="margin-top: 10px;">Continue Shopping</a>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
