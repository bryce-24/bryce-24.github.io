<?php
// Debug/test page for subscription system
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";
if ($conn->connect_error) {
    echo "<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color: green;'>✓ Database connected successfully!</p>";
}

echo "<h2>Table Check</h2>";
$tables = ['users', 'subscriptions'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        
        // Check table structure
        $result = $conn->query("DESCRIBE $table");
        echo "<h3>Structure of '$table':</h3><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Field'] . " (" . $row['Type'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' does NOT exist</p>";
    }
}

echo "<h2>Test Insert</h2>";
$testEmail = 'test@example.com';
$testToken = bin2hex(random_bytes(32));

// Check if test email exists
$stmt = $conn->prepare("SELECT id FROM subscriptions WHERE email = ?");
$stmt->bind_param("s", $testEmail);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    echo "<p>Test email already exists. Deleting it first...</p>";
    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE email = ?");
    $stmt->bind_param("s", $testEmail);
    $stmt->execute();
    $stmt->close();
}

// Try to insert
$stmt = $conn->prepare("INSERT INTO subscriptions (first_name, last_name, email, dog_preference, delivery_day, newsletter, status, unsubscribe_token) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");
if (!$stmt) {
    echo "<p style='color: red;'>✗ Prepare failed: " . $conn->error . "</p>";
} else {
    $firstName = "Test";
    $lastName = "User";
    $dogPreference = "any";
    $deliveryDay = "monday";
    $newsletter = 0;
    
    $stmt->bind_param("sssssis", $firstName, $lastName, $testEmail, $dogPreference, $deliveryDay, $newsletter, $testToken);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✓ Test insert successful!</p>";
        echo "<p>Inserted ID: " . $stmt->insert_id . "</p>";
        $stmt->close();
        
        // Clean up test data
        $deleteStmt = $conn->prepare("DELETE FROM subscriptions WHERE email = ?");
        $deleteStmt->bind_param("s", $testEmail);
        $deleteStmt->execute();
        $deleteStmt->close();
        echo "<p>Test data cleaned up.</p>";
    } else {
        echo "<p style='color: red;'>✗ Insert failed: " . $stmt->error . "</p>";
        $stmt->close();
    }
}

echo "<h2>Current Subscriptions</h2>";
$result = $conn->query("SELECT id, first_name, last_name, email, status, created_at FROM subscriptions ORDER BY created_at DESC LIMIT 10");
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Created</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No subscriptions found.</p>";
}

$conn->close();
?>

