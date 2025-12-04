<?php
require_once '../config.php';
require_once '../controllers/SubscriptionController.php';

$controller = new SubscriptionController();

$token = $_GET['token'] ?? '';
$action = $_GET['action'] ?? '';

// If token is provided and action is confirm, unsubscribe immediately
if (!empty($token) && $action === 'confirm') {
    $controller->handleUnsubscribe();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleUnsubscribeByEmail();
} else {
    $controller->showUnsubscribe();
}
?>

