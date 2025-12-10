# Green Earth Tree Management

## Overview
A PHP-based tree selling web application with role-based access control. Features an admin dashboard for managing trees with pricing, and a public-facing catalog where users can browse, search, and purchase trees.

## Current State
- Fully functional PHP e-commerce application with JSON-based data storage
- Shopping cart with add/remove/update quantity functionality
- Checkout system with order placement
- Login system with password hashing and session management
- Admin and User role-based access
- Tree catalog with search and category filtering
- Responsive design with green nature-themed UI

## Demo Credentials
- **Admin**: username: `admin`, password: `password`
- **User**: username: `user`, password: `password`

## Project Structure
```
/
├── index.php           # Home page with featured trees
├── login.php           # Login page
├── logout.php          # Logout handler
├── dashboard.php       # User/Admin dashboard
├── trees.php           # Public tree catalog with prices
├── tree-details.php    # Individual tree details with add to cart
├── cart.php            # Shopping cart page
├── checkout.php        # Checkout and order placement
├── add-to-cart.php     # Add to cart action handler
├── update-cart.php     # Update cart quantities
├── admin/
│   ├── add-tree.php    # Add new tree with price (admin only)
│   ├── edit-tree.php   # Edit tree with price (admin only)
│   └── delete-tree.php # Delete tree (admin only)
├── includes/
│   ├── config.php      # Configuration, helpers, cart functions
│   ├── header.php      # HTML header with navigation and cart
│   └── footer.php      # HTML footer
├── data/
│   ├── users.json      # User credentials (hashed passwords)
│   ├── trees.json      # Tree catalog data with prices
│   └── orders.json     # Customer orders
└── assets/
    ├── css/style.css   # Stylesheet
    └── js/main.js      # JavaScript functionality
```

## Features
1. **Authentication**: Session-based login with password hashing and CSRF protection
2. **Admin Features**: Add, edit, delete trees with pricing
3. **Shopping Cart**: Add items, update quantities, remove items
4. **Checkout**: Place orders with customer information
5. **User Features**: Browse trees, search by name, filter by category
6. **Responsive Design**: Mobile-friendly interface

## Technical Details
- **Language**: PHP 8.4
- **Database**: JSON file storage (users.json, trees.json, orders.json)
- **Styling**: Custom CSS with Poppins font
- **Sessions**: PHP native sessions for authentication and cart
- **Security**: CSRF protection, password hashing, XSS prevention

## User Preferences
- Clean, nature-inspired green theme
- Simple and intuitive UI
- No external frameworks (vanilla PHP, CSS, JS)
- Prices in BDT (Bangladeshi Taka) with ৳ symbol
