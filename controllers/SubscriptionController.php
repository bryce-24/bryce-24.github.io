<?php
require_once __DIR__ . '/../config.php';

class SubscriptionController {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function showSubscribe() {
        $message = $_SESSION['subscription_message'] ?? '';
        $error = $_SESSION['subscription_error'] ?? '';
        unset($_SESSION['subscription_message']);
        unset($_SESSION['subscription_error']);
        
        require_once __DIR__ . '/../views/subscribe.php';
    }
    
    public function handleSubscribe() {
        // Start output buffering to catch any errors
        ob_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_end_clean();
            header('Location: business.php');
            exit;
        }
        
        error_log("handleSubscribe called - POST data: " . print_r($_POST, true));
        
        $firstName = trim($_POST['firstName'] ?? '');
        $lastName = trim($_POST['lastName'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $dogPreference = $_POST['dogPreference'] ?? 'any';
        $deliveryDay = $_POST['deliveryDay'] ?? 'monday';
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        $terms = isset($_POST['terms']) ? 1 : 0;
        
        error_log("Parsed data - Name: $firstName $lastName, Email: $email");
        
        // Validation
        $errors = [];
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email address is required';
        }
        if (!$terms) {
            $errors[] = 'You must agree to the Terms of Service';
        }
        
        if (!empty($errors)) {
            ob_end_clean();
            $_SESSION['subscription_error'] = implode('<br>', $errors);
            error_log("Validation errors: " . implode(', ', $errors));
            header('Location: business.php');
            exit;
        }
        
        // Check if email already exists
        $stmt = $this->conn->prepare("SELECT id, status FROM subscriptions WHERE email = ?");
        if (!$stmt) {
            $_SESSION['subscription_error'] = 'Database error: ' . $this->conn->error;
            header('Location: business.php');
            exit;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            $stmt->close(); // Close the SELECT statement
            
            if ($existing['status'] === 'active') {
                $_SESSION['subscription_error'] = 'This email is already subscribed!';
                header('Location: business.php');
                exit;
            } else {
                // Reactivate subscription
                $unsubscribeToken = bin2hex(random_bytes(32));
                $stmt = $this->conn->prepare("UPDATE subscriptions SET first_name = ?, last_name = ?, dog_preference = ?, delivery_day = ?, newsletter = ?, status = 'active', unsubscribe_token = ? WHERE email = ?");
                if (!$stmt) {
                    $_SESSION['subscription_error'] = 'Database error: ' . $this->conn->error;
                    header('Location: business.php');
                    exit;
                }
                $stmt->bind_param("ssssiss", $firstName, $lastName, $dogPreference, $deliveryDay, $newsletter, $unsubscribeToken, $email);
                if ($stmt->execute()) {
                    $stmt->close();
                    $_SESSION['subscription_message'] = 'Your subscription has been reactivated!';
                    header('Location: business/confirm.php');
                    exit;
                } else {
                    $errorMsg = $stmt->error ? $stmt->error : $this->conn->error;
                    $stmt->close();
                    $_SESSION['subscription_error'] = 'Database error: ' . $errorMsg;
                    header('Location: business.php');
                    exit;
                }
            }
        } else {
            $stmt->close(); // Close the SELECT statement
            
            // Create new subscription
            $unsubscribeToken = bin2hex(random_bytes(32));
            $stmt = $this->conn->prepare("INSERT INTO subscriptions (first_name, last_name, email, dog_preference, delivery_day, newsletter, status, unsubscribe_token) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");
            
            if (!$stmt) {
                $_SESSION['subscription_error'] = 'Database error: ' . $this->conn->error;
                header('Location: business.php');
                exit;
            }
            
            $stmt->bind_param("sssssis", $firstName, $lastName, $email, $dogPreference, $deliveryDay, $newsletter, $unsubscribeToken);
            
            if ($stmt->execute()) {
                $insertId = $stmt->insert_id;
                $affectedRows = $stmt->affected_rows;
                $stmt->close();
                
                // Log for debugging (remove in production)
                error_log("Subscription insert - ID: $insertId, Affected rows: $affectedRows, Email: $email");
                
                if ($insertId > 0) {
                    ob_end_clean();
                    $_SESSION['subscription_message'] = 'Thank you for subscribing! Check your email for confirmation.';
                    error_log("Subscription successful - ID: $insertId, redirecting to confirm");
                    header('Location: business/confirm.php');
                    exit;
                } else {
                    ob_end_clean();
                    $_SESSION['subscription_error'] = 'Subscription insert returned ID 0. Database may not have saved the record.';
                    error_log("Subscription failed - insert_id was 0");
                    header('Location: business.php');
                    exit;
                }
            } else {
                $errorMsg = $stmt->error ? $stmt->error : $this->conn->error;
                $stmt->close();
                ob_end_clean();
                error_log("Subscription insert failed - Error: $errorMsg, Email: $email");
                $_SESSION['subscription_error'] = 'Database error: ' . $errorMsg;
                header('Location: business.php');
                exit;
            }
        }
    }
    
    public function showManage() {
        $email = $_GET['email'] ?? '';
        $subscription = null;
        
        if (!empty($email)) {
            $stmt = $this->conn->prepare("SELECT * FROM subscriptions WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $subscription = $result->fetch_assoc();
            }
            $stmt->close();
        }
        
        require_once __DIR__ . '/../views/manage.php';
    }
    
    public function handleManage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: business/manage.php');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $dogPreference = $_POST['dogPreference'] ?? 'any';
        $deliveryDay = $_POST['deliveryDay'] ?? 'monday';
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['subscription_error'] = 'Valid email address is required';
            header('Location: business/manage.php?email=' . urlencode($email));
            exit;
        }
        
        $stmt = $this->conn->prepare("UPDATE subscriptions SET dog_preference = ?, delivery_day = ?, newsletter = ? WHERE email = ? AND status = 'active'");
        $stmt->bind_param("ssis", $dogPreference, $deliveryDay, $newsletter, $email);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['subscription_message'] = 'Your subscription preferences have been updated!';
        } else {
            $_SESSION['subscription_error'] = 'Subscription not found or inactive.';
        }
        $stmt->close();
        
        header('Location: business/manage.php?email=' . urlencode($email));
        exit;
    }
    
    public function showUnsubscribe() {
        $token = $_GET['token'] ?? '';
        $subscription = null;
        
        if (!empty($token)) {
            $stmt = $this->conn->prepare("SELECT * FROM subscriptions WHERE unsubscribe_token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $subscription = $result->fetch_assoc();
            }
            $stmt->close();
        }
        
        require_once __DIR__ . '/../views/unsubscribe.php';
    }
    
    public function handleUnsubscribe() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['subscription_error'] = 'Invalid unsubscribe link';
            header('Location: /business/unsubscribe.php');
            exit;
        }
        
        $stmt = $this->conn->prepare("UPDATE subscriptions SET status = 'unsubscribed' WHERE unsubscribe_token = ? AND status != 'unsubscribed'");
        $stmt->bind_param("s", $token);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['subscription_message'] = 'You have been successfully unsubscribed.';
        } else {
            // Check if already unsubscribed
            $checkStmt = $this->conn->prepare("SELECT status FROM subscriptions WHERE unsubscribe_token = ?");
            $checkStmt->bind_param("s", $token);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['status'] === 'unsubscribed') {
                    $_SESSION['subscription_message'] = 'You are already unsubscribed.';
                } else {
                    $_SESSION['subscription_error'] = 'Invalid or expired unsubscribe link.';
                }
            } else {
                $_SESSION['subscription_error'] = 'Invalid or expired unsubscribe link.';
            }
            $checkStmt->close();
        }
        $stmt->close();
        
        header('Location: /business/unsubscribe.php');
        exit;
    }
    
    public function handleUnsubscribeByEmail() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /business/unsubscribe.php');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['subscription_error'] = 'Valid email address is required';
            header('Location: /business/unsubscribe.php');
            exit;
        }
        
        $stmt = $this->conn->prepare("UPDATE subscriptions SET status = 'unsubscribed' WHERE email = ?");
        $stmt->bind_param("s", $email);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['subscription_message'] = 'You have been successfully unsubscribed.';
        } else {
            $_SESSION['subscription_error'] = 'Email not found in our subscription list.';
        }
        $stmt->close();
        
        header('Location: /business/unsubscribe.php');
        exit;
    }
    
    public function showConfirm() {
        $message = $_SESSION['subscription_message'] ?? 'Thank you for subscribing!';
        unset($_SESSION['subscription_message']);
        
        require_once __DIR__ . '/../views/confirm.php';
    }
}

