<?php
require_once '../config.php';
require_once '../controllers/SubscriptionController.php';

$controller = new SubscriptionController();
$controller->showConfirm();
?>

