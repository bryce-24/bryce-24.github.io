<?php
// Simple test to see if form submission works
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h1>POST Received!</h1>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    echo "<h2>Testing Database Insert</h2>";
    require_once 'config.php';
    
    if ($conn->connect_error) {
        echo "<p style='color: red;'>Database connection failed: " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>Database connected</p>";
        
        // Try insert
        $email = $_POST['email'] ?? 'test@test.com';
        $firstName = $_POST['firstName'] ?? 'Test';
        $lastName = $_POST['lastName'] ?? 'User';
        $dogPreference = $_POST['dogPreference'] ?? 'any';
        $deliveryDay = $_POST['deliveryDay'] ?? 'monday';
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        $token = bin2hex(random_bytes(32));
        
        $stmt = $conn->prepare("INSERT INTO subscriptions (first_name, last_name, email, dog_preference, delivery_day, newsletter, status, unsubscribe_token) VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)");
        
        if (!$stmt) {
            echo "<p style='color: red;'>Prepare failed: " . $conn->error . "</p>";
        } else {
            $stmt->bind_param("sssssis", $firstName, $lastName, $email, $dogPreference, $deliveryDay, $newsletter, $token);
            
            if ($stmt->execute()) {
                echo "<p style='color: green; font-size: 20px;'>✓ INSERT SUCCESSFUL! ID: " . $stmt->insert_id . "</p>";
                
                // Verify
                $check = $conn->query("SELECT * FROM subscriptions WHERE id = " . $stmt->insert_id);
                if ($check->num_rows > 0) {
                    echo "<p style='color: green;'>✓ Verified in database!</p>";
                }
            } else {
                echo "<p style='color: red;'>Insert failed: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        
        $conn->close();
    }
    
    echo "<hr><a href='test-form.php'>Try Again</a>";
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test Form</title>
    </head>
    <body>
        <h1>Test Form Submission</h1>
        <form method="post" action="test-form.php">
            <p>
                <label>First Name: <input type="text" name="firstName" value="Test" required></label>
            </p>
            <p>
                <label>Last Name: <input type="text" name="lastName" value="User" required></label>
            </p>
            <p>
                <label>Email: <input type="email" name="email" value="test@example.com" required></label>
            </p>
            <p>
                <label>Dog Preference: 
                    <select name="dogPreference">
                        <option value="any">Any</option>
                        <option value="puppies">Puppies</option>
                    </select>
                </label>
            </p>
            <p>
                <label>Delivery Day: 
                    <select name="deliveryDay">
                        <option value="monday">Monday</option>
                    </select>
                </label>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="newsletter" value="1"> Newsletter
                </label>
            </p>
            <p>
                <label>
                    <input type="checkbox" name="terms" value="1" required> Terms
                </label>
            </p>
            <p>
                <button type="submit">Submit Test Form</button>
            </p>
        </form>
    </body>
    </html>
    <?php
}
?>

