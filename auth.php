<?php
// Authentication check - redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    // Get the current script path
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Check if we're in a subdirectory
    // If script is /business/admin.php or /business/something.php, we need to go up
    if (strpos($scriptName, '/business/') !== false) {
        $redirectPath = '../signin.php';
    } elseif (strpos($scriptName, 'business/') !== false) {
        $redirectPath = '../signin.php';
    } else {
        $redirectPath = 'signin.php';
    }
    
    header("Location: " . $redirectPath);
    exit();
}
?>

