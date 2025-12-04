<?php
// Debug what URL we're actually on
echo "<h1>Form Debug Info</h1>";
echo "<p><strong>Current URL:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>PHP_SELF:</strong> " . $_SERVER['PHP_SELF'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>POST Data Received:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo "<h2>Test Form</h2>";
    echo "<form method='post' action=''>";
    echo "<p>Name: <input type='text' name='test' value='test'></p>";
    echo "<p><button type='submit'>Submit</button></p>";
    echo "</form>";
}
?>

