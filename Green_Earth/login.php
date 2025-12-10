<?php
require_once 'includes/config.php';

if (isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $users = loadJsonFile(USERS_FILE);
        $foundUser = null;
        
        foreach ($users as $user) {
            if ($user['username'] === $username || $user['email'] === $username) {
                $foundUser = $user;
                break;
            }
        }
        
        if ($foundUser && password_verify($password, $foundUser['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['username'] = $foundUser['username'];
            $_SESSION['email'] = $foundUser['email'];
            $_SESSION['role'] = $foundUser['role'];
            
            setFlashMessage('success', 'Welcome back, ' . $foundUser['username'] . '!');
            header('Location: /dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password. Please try again.';
        }
    }
}

$pageTitle = 'Login';
require_once 'includes/header.php';
?>

<div class="login-container">
    <div class="login-box">
        <h1>🌳 Login</h1>
        
        <?php if ($error): ?>
        <div class="error-message"><?php echo sanitize($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" data-validate>
            <?php echo csrfField(); ?>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? sanitize($_POST['username']) : ''; ?>"
                       placeholder="Enter your username or email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       placeholder="Enter your password">
            </div>
            
            <button type="submit" class="btn btn-green">Login</button>
        </form>
        
        <div class="login-footer">
            <p>Demo Accounts:</p>
            <p><strong>Admin:</strong> admin / password</p>
            <p><strong>User:</strong> user / password</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
