<?php
// Debug page to test form submission
require_once 'config.php';
require_once 'controllers/SubscriptionController.php';

echo "<h1>Debug Subscription Submission</h1>";

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "<p style='color: green;'>✓ Database connected</p>";

// Check if table exists
$result = $conn->query("SHOW TABLES LIKE 'subscriptions'");
if ($result->num_rows == 0) {
    die("<p style='color: red;'>✗ Subscriptions table does NOT exist! Run the database migration first.</p>");
}
echo "<p style='color: green;'>✓ Subscriptions table exists</p>";

// Simulate a form submission
echo "<h2>Simulating Form Submission</h2>";

// Set POST data
$_POST['firstName'] = 'Debug';
$_POST['lastName'] = 'Test';
$_POST['email'] = 'debug@test.com';
$_POST['dogPreference'] = 'any';
$_POST['deliveryDay'] = 'monday';
$_POST['newsletter'] = '1';
$_POST['terms'] = '1';

$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<p>Form data:</p><ul>";
echo "<li>First Name: " . $_POST['firstName'] . "</li>";
echo "<li>Last Name: " . $_POST['lastName'] . "</li>";
echo "<li>Email: " . $_POST['email'] . "</li>";
echo "<li>Dog Preference: " . $_POST['dogPreference'] . "</li>";
echo "<li>Delivery Day: " . $_POST['deliveryDay'] . "</li>";
echo "<li>Newsletter: " . ($_POST['newsletter'] ?? '0') . "</li>";
echo "<li>Terms: " . ($_POST['terms'] ?? '0') . "</li>";
echo "</ul>";

// Check if email already exists
$testEmail = $_POST['email'];
$stmt = $conn->prepare("SELECT id, status FROM subscriptions WHERE email = ?");
$stmt->bind_param("s", $testEmail);
$stmt->execute();
$result = $stmt->get_result();
$exists = $result->num_rows > 0;
$stmt->close();

if ($exists) {
    echo "<p style='color: orange;'>⚠ Email already exists in database. Deleting it first...</p>";
    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE email = ?");
    $stmt->bind_param("s", $testEmail);
    $stmt->execute();
    $stmt->close();
}

// Try direct insert
echo "<h2>Testing Direct Database Insert</h2>";
$unsubscribeToken = bin2hex(random_bytes(32));
$stmt = $conn->prepare("INSERT INTO subscriptions (first_name, last_name, email, dog_preference, delivery_day, newsletter, status, unsubscribe_token) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");

if (!$stmt) {
    echo "<p style='color: red;'>✗ Prepare failed: " . $conn->error . "</p>";
} else {
    echo "<p style='color: green;'>✓ Statement prepared successfully</p>";
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $dogPreference = $_POST['dogPreference'];
    $deliveryDay = $_POST['deliveryDay'];
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    
    $stmt->bind_param("sssssis", $firstName, $lastName, $email, $dogPreference, $deliveryDay, $newsletter, $unsubscribeToken);
    echo "<p>✓ Parameters bound</p>";
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✓ Insert successful! ID: " . $stmt->insert_id . "</p>";
        $stmt->close();
        
        // Verify it was inserted
        $checkStmt = $conn->prepare("SELECT * FROM subscriptions WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            echo "<p style='color: green;'>✓ Verified in database:</p>";
            echo "<pre>";
            print_r($row);
            echo "</pre>";
        }
        $checkStmt->close();
        
        // Clean up
        $deleteStmt = $conn->prepare("DELETE FROM subscriptions WHERE email = ?");
        $deleteStmt->bind_param("s", $email);
        $deleteStmt->execute();
        $deleteStmt->close();
        echo "<p>Test data cleaned up.</p>";
    } else {
        echo "<p style='color: red;'>✗ Insert failed!</p>";
        echo "<p>Error: " . $stmt->error . "</p>";
        echo "<p>Connection error: " . $conn->error . "</p>";
        $stmt->close();
    }
}

// Now test the controller
echo "<h2>Testing Controller</h2>";
session_start();

// Capture output
ob_start();
try {
    $controller = new SubscriptionController();
    $controller->handleSubscribe();
    $output = ob_get_clean();
    echo "<p>Controller executed. Check redirect or output.</p>";
    if (!empty($output)) {
        echo "<p>Output: " . htmlspecialchars($output) . "</p>";
    }
    
    // Check session messages
    if (isset($_SESSION['subscription_message'])) {
        echo "<p style='color: green;'>Session message: " . $_SESSION['subscription_message'] . "</p>";
    }
    if (isset($_SESSION['subscription_error'])) {
        echo "<p style='color: red;'>Session error: " . $_SESSION['subscription_error'] . "</p>";
    }
} catch (Exception $e) {
    ob_end_clean();
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
}

// Show all subscriptions
echo "<h2>All Subscriptions in Database</h2>";
$result = $conn->query("SELECT id, first_name, last_name, email, status, created_at FROM subscriptions ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['first_name'] . " " . $row['last_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>No subscriptions found in database!</p>";
}

$conn->close();
?>

