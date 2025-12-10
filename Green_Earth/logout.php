<?php
require_once 'includes/config.php';

session_destroy();

session_start();
setFlashMessage('success', 'You have been logged out successfully.');

header('Location: /');
exit;
?>
