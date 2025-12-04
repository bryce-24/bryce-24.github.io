<?php
// Enable error display for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug logging
    error_log("Signup form submitted - POST data received");
    error_log("POST data: " . print_r($_POST, true));
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $error = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long!';
    } else {
        // Check database connection
        if ($conn->connect_error) {
            $error = 'Database connection failed. Please try again later.';
            error_log("Database connection error: " . $conn->connect_error);
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            if (!$stmt) {
                $error = 'Database error: ' . $conn->error;
                error_log("Prepare failed (SELECT): " . $conn->error);
            } else {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $error = 'Email already registered!';
                    $stmt->close();
                } else {
                    $stmt->close();
                    
                    // Hash password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert new user
                    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
                    
                    if (!$stmt) {
                        $error = 'Database error: ' . $conn->error;
                        error_log("Prepare failed: " . $conn->error);
                    } else {
                        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
                        
                        if ($stmt->execute()) {
                            $insertId = $stmt->insert_id;
                            error_log("User registered successfully - ID: $insertId, Email: $email");
                            $success = 'Registration successful! You can now <a href="signin.php" style="color: #6df1ff;">sign in</a>.';
                        } else {
                            $errorMsg = $stmt->error ? $stmt->error : $conn->error;
                            $error = 'Registration failed! Error: ' . htmlspecialchars($errorMsg);
                            error_log("Insert failed: " . $errorMsg);
                        }
                        $stmt->close();
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - Bryce Normile</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <div class="background-shapes"></div>

  <!-- Navigation -->
  <nav class="navbar">
    <div class="nav-container">
      <a href="index.html" class="nav-logo">Bryce Normile</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
        <li class="nav-item"><a href="portfolio.html" class="nav-link">Portfolio</a></li>
        <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
        <li class="nav-item"><a href="signin.php" class="nav-link">Sign In</a></li>
        <li class="nav-item"><a href="signup.php" class="nav-link active">Sign Up</a></li>
      </ul>
    </div>
  </nav>

  <main class="section">
    <header>
      <h1>Sign Up</h1>
    </header>

    <article>
      <section>
        <h2>Join My Portfolio</h2>
        <p>
          Create an account to access exclusive content and stay updated with my latest projects and updates.
        </p>
      </section>

      <section>
        <h2>Why Sign Up?</h2>
        <p>
          By creating an account, you'll join a community of developers and tech enthusiasts who share my passion for web development. You'll get early access to new projects, behind-the-scenes content, and the chance to connect with other members. Plus, you'll never miss an update about my latest coding adventures!
        </p>
      </section>

      <section>
        <h2>Getting Started</h2>
        <p>
          Signing up is quick and easy - just fill out the form below with your basic information. Once you're registered, you'll have full access to all the features and content on the site. I promise not to spam you with emails, and you can unsubscribe from updates anytime if you change your mind.
        </p>
      </section>
    </article>

    <!-- Sign Up Form -->
    <div class="form-container">
      <h3>Create Your Account</h3>
      
      <?php if ($error): ?>
        <div style="background: rgba(255, 107, 107, 0.2); border: 1px solid #ff6b6b; color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div style="background: rgba(109, 241, 255, 0.2); border: 1px solid #6df1ff; color: #6df1ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>
      
      <form action="signup.php" method="post">
        <div class="form-group">
          <label for="firstName">First Name <span style="color: #ff6b6b;">*</span></label>
          <input type="text" id="firstName" name="firstName" required placeholder="First name" value="<?php echo htmlspecialchars($_POST['firstName'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
          <label for="lastName">Last Name <span style="color: #ff6b6b;">*</span></label>
          <input type="text" id="lastName" name="lastName" required placeholder="Last name" value="<?php echo htmlspecialchars($_POST['lastName'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
          <label for="email">Email <span style="color: #ff6b6b;">*</span></label>
          <input type="email" id="email" name="email" required placeholder="your@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
          <label for="password">Password <span style="color: #ff6b6b;">*</span></label>
          <input type="password" id="password" name="password" required placeholder="Create a password (min 6 characters)">
        </div>
        
        <div class="form-group">
          <label>
            <input type="checkbox" name="terms" value="1" required> I agree to the <a href="terms.html" style="color: #6df1ff;">Terms</a> and <a href="privacy.html" style="color: #6df1ff;">Privacy Policy</a> <span style="color: #ff6b6b;">*</span>
          </label>
        </div>
        
        <div class="form-group">
          <label>
            <input type="checkbox" name="newsletter" value="1"> Subscribe to updates
          </label>
        </div>
        
        <button type="submit" class="form-submit">Create Account</button>
        
        <p style="text-align: center; margin-top: 20px;">
          Already have an account? <a href="signin.php" style="color: #6df1ff;">Sign in here</a>
        </p>
      </form>
    </div>
  </main>

  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="portfolio.html">Portfolio</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </div>
      <div class="footer-section">
        <h3>Legal</h3>
        <ul class="footer-links">
          <li><a href="terms.html">Terms and Conditions</a></li>
          <li><a href="privacy.html">Privacy Policy</a></li>
          <li><a href="cookie.html">Cookie Policy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bryce Normile. All rights reserved. Live, Laugh, Love</p>
    </div>
  </footer>
</body>
</html>

