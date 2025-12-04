<?php
require_once '../../config.php';
require_once '../../auth.php';
require_once '../../controllers/AdminController.php';

$controller = new AdminController();
$controller->sendTestEmail();
?>

