<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php';

class AdminController {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function showAdmin() {
        // Get all subscriptions
        $subscriptions = [];
        $stmt = $this->conn->prepare("SELECT * FROM subscriptions ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $subscriptions[] = $row;
        }
        $stmt->close();
        
        // Get statistics
        $stats = [];
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM subscriptions");
        $stats['total'] = $stmt->fetch_assoc()['total'];
        $stmt->close();
        
        $stmt = $this->conn->query("SELECT COUNT(*) as active FROM subscriptions WHERE status = 'active'");
        $stats['active'] = $stmt->fetch_assoc()['active'];
        $stmt->close();
        
        $stmt = $this->conn->query("SELECT COUNT(*) as pending FROM subscriptions WHERE status = 'pending'");
        $stats['pending'] = $stmt->fetch_assoc()['pending'];
        $stmt->close();
        
        $message = $_SESSION['admin_message'] ?? '';
        $error = $_SESSION['admin_error'] ?? '';
        unset($_SESSION['admin_message']);
        unset($_SESSION['admin_error']);
        
        require_once __DIR__ . '/../views/admin.php';
    }
    
    public function updateSubscription() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /business/admin');
            exit;
        }
        
        $id = intval($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        $dogPreference = $_POST['dog_preference'] ?? 'any';
        $deliveryDay = $_POST['delivery_day'] ?? 'monday';
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        
        if (!in_array($status, ['active', 'pending', 'unsubscribed'])) {
            $_SESSION['admin_error'] = 'Invalid status';
            header('Location: business/admin.php');
            exit;
        }
        
        $stmt = $this->conn->prepare("UPDATE subscriptions SET status = ?, dog_preference = ?, delivery_day = ?, newsletter = ? WHERE id = ?");
        $stmt->bind_param("sssii", $status, $dogPreference, $deliveryDay, $newsletter, $id);
        
        if ($stmt->execute()) {
            $_SESSION['admin_message'] = 'Subscription updated successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to update subscription.';
        }
        $stmt->close();
        
        header('Location: business/admin.php');
        exit;
    }
    
    public function deleteSubscription() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /business/admin');
            exit;
        }
        
        $id = intval($_POST['id'] ?? 0);
        
        $stmt = $this->conn->prepare("DELETE FROM subscriptions WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['admin_message'] = 'Subscription deleted successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to delete subscription.';
        }
        $stmt->close();
        
        header('Location: business/admin.php');
        exit;
    }
    
    public function sendTestEmail() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: business/admin.php');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['admin_error'] = 'Valid email address is required';
            header('Location: business/admin.php');
            exit;
        }
        
        // In a real application, you would send an actual email here
        // For now, we'll just log it
        $_SESSION['admin_message'] = "Test email would be sent to: $email (Email functionality not implemented yet)";
        
        header('Location: business/admin.php');
        exit;
    }
}

