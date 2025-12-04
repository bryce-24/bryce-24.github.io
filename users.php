<?php
require_once 'config.php';
require_once 'auth.php';

// Get all users
$users = [];
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();

// Get messages from session
$addError = $_SESSION['add_user_error'] ?? '';
$addSuccess = $_SESSION['add_user_success'] ?? '';
$deleteError = $_SESSION['delete_error'] ?? '';
$deleteSuccess = $_SESSION['delete_success'] ?? '';

// Clear messages
unset($_SESSION['add_user_error']);
unset($_SESSION['add_user_success']);
unset($_SESSION['delete_error']);
unset($_SESSION['delete_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Management - Bryce Normile</title>
  <link rel="stylesheet" href="styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    .users-table {
      width: 100%;
      border-collapse: collapse;
      margin: 30px 0;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      overflow: hidden;
    }
    
    .users-table th,
    .users-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .users-table th {
      background: rgba(109, 241, 255, 0.2);
      color: #6df1ff;
      font-weight: bold;
      font-family: 'Orbitron', sans-serif;
    }
    
    .users-table tr:hover {
      background: rgba(255, 255, 255, 0.05);
    }
    
    .users-table tr:last-child td {
      border-bottom: none;
    }
    
    .delete-btn {
      background: #ff6b6b;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    
    .delete-btn:hover {
      background: #ff5252;
      transform: translateY(-2px);
    }
    
    .user-info {
      background: rgba(109, 241, 255, 0.1);
      border: 1px solid rgba(109, 241, 255, 0.3);
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    
    .user-info p {
      margin: 5px 0;
    }
    
    .add-user-section {
      margin-bottom: 40px;
    }
  </style>
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
        <li class="nav-item"><a href="users.php" class="nav-link active">Users</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
      </ul>
    </div>
  </nav>

  <main class="section">
    <header>
      <h1>User Management</h1>
    </header>

    <!-- User Info -->
    <div class="user-info">
      <p><strong>Logged in as:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
    </div>

    <!-- Messages -->
    <?php if ($addError): ?>
      <div style="background: rgba(255, 107, 107, 0.2); border: 1px solid #ff6b6b; color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($addError); ?>
      </div>
    <?php endif; ?>
    
    <?php if ($addSuccess): ?>
      <div style="background: rgba(109, 241, 255, 0.2); border: 1px solid #6df1ff; color: #6df1ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($addSuccess); ?>
      </div>
    <?php endif; ?>
    
    <?php if ($deleteError): ?>
      <div style="background: rgba(255, 107, 107, 0.2); border: 1px solid #ff6b6b; color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($deleteError); ?>
      </div>
    <?php endif; ?>
    
    <?php if ($deleteSuccess): ?>
      <div style="background: rgba(109, 241, 255, 0.2); border: 1px solid #6df1ff; color: #6df1ff; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($deleteSuccess); ?>
      </div>
    <?php endif; ?>

    <!-- Add User Form -->
    <div class="add-user-section">
      <div class="form-container">
        <h3>Add New User</h3>
        <form action="add_user.php" method="post">
          <div class="form-group">
            <label for="firstName">First Name <span style="color: #ff6b6b;">*</span></label>
            <input type="text" id="firstName" name="firstName" required placeholder="First name">
          </div>
          
          <div class="form-group">
            <label for="lastName">Last Name <span style="color: #ff6b6b;">*</span></label>
            <input type="text" id="lastName" name="lastName" required placeholder="Last name">
          </div>
          
          <div class="form-group">
            <label for="email">Email <span style="color: #ff6b6b;">*</span></label>
            <input type="email" id="email" name="email" required placeholder="your@email.com">
          </div>
          
          <div class="form-group">
            <label for="password">Password <span style="color: #ff6b6b;">*</span></label>
            <input type="password" id="password" name="password" required placeholder="Password (min 6 characters)">
          </div>
          
          <button type="submit" class="form-submit">Add User</button>
        </form>
      </div>
    </div>

    <!-- Users Table -->
    <div class="form-container">
      <h3>Registered Users</h3>
      <?php if (count($users) > 0): ?>
        <table class="users-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                <td>
                  <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <form action="delete_user.php" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                      <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                      <button type="submit" class="delete-btn">Delete</button>
                    </form>
                  <?php else: ?>
                    <span style="color: #888;">Current User</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align: center; color: #888; padding: 20px;">No users found.</p>
      <?php endif; ?>
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

