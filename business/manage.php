<?php
require_once '../config.php';
require_once '../controllers/SubscriptionController.php';

$controller = new SubscriptionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleManage();
} else {
    $controller->showManage();
}
?>

