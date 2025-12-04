<?php
// Enable error display for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'controllers/SubscriptionController.php';

$controller = new SubscriptionController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleSubscribe();
} else {
    $controller->showSubscribe();
}
?>
