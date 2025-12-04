<?php
// Check if confirmation page is accessible
echo "<h1>Testing Confirmation Page</h1>";

echo "<h2>Test 1: Direct access to confirm.php</h2>";
echo "<p><a href='business/confirm.php'>Click here to test confirmation page</a></p>";

echo "<h2>Test 2: Check if file exists</h2>";
if (file_exists('business/confirm.php')) {
    echo "<p style='color: green;'>✓ business/confirm.php exists</p>";
} else {
    echo "<p style='color: red;'>✗ business/confirm.php does NOT exist</p>";
}

echo "<h2>Test 3: Check current directory</h2>";
echo "<p>Current directory: " . getcwd() . "</p>";
echo "<p>Script path: " . __FILE__ . "</p>";

echo "<h2>Test 4: Try to require confirm.php</h2>";
try {
    require_once 'business/confirm.php';
    echo "<p style='color: green;'>✓ Successfully loaded confirm.php</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

?>

