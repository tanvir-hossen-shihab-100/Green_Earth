<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Green Earth</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-logo">
                <span class="logo-icon">🌳</span>
                <span class="logo-text">Green Earth</span>
            </a>
            <ul class="nav-menu">
                <li><a href="/" class="nav-link">Home</a></li>
                <li><a href="/trees.php" class="nav-link">Trees</a></li>
                <li>
                    <a href="/cart.php" class="nav-link cart-link">
                        🛒 Cart
                        <?php $cartCount = getCartCount(); if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="/dashboard.php" class="nav-link">Dashboard</a></li>
                    <li><a href="/logout.php" class="nav-link btn-logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login.php" class="nav-link btn-login">Login</a></li>
                <?php endif; ?>
            </ul>
            <button class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
    
    <?php 
    $flash = getFlashMessage();
    if ($flash): 
    ?>
    <div class="flash-message flash-<?php echo $flash['type']; ?>">
        <div class="flash-content">
            <?php echo sanitize($flash['message']); ?>
            <button class="flash-close">&times;</button>
        </div>
    </div>
    <?php endif; ?>
    
    <main class="main-content">
