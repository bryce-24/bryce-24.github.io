<?php
// Authentication check - redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}
?>

