<?php
require_once '../config.php';
require_once '../auth.php';
require_once '../controllers/AdminController.php';

$controller = new AdminController();

$action = $_GET['action'] ?? '';

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateSubscription();
} else if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->deleteSubscription();
} else if ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->sendTestEmail();
} else {
    $controller->showAdmin();
}
?>

