<?php
require_once 'config.php';
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    
    // Prevent deleting yourself
    if ($userId == $_SESSION['user_id']) {
        $_SESSION['delete_error'] = 'You cannot delete your own account!';
    } else {
        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        
        if ($stmt->execute()) {
            $_SESSION['delete_success'] = 'User deleted successfully!';
        } else {
            $_SESSION['delete_error'] = 'Failed to delete user!';
        }
        $stmt->close();
    }
    
    header("Location: users.php");
    exit();
} else {
    header("Location: users.php");
    exit();
}
?>

