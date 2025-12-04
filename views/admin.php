<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - DogPics Weekly</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="business-styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    .admin-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    
    .stat-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      border: 2px solid rgba(255, 107, 107, 0.2);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card h3 {
      color: #ff6b6b;
      font-size: 2rem;
      margin: 0;
      font-family: 'Orbitron', sans-serif;
    }
    
    .stat-card p {
      color: #666;
      margin: 10px 0 0 0;
      font-weight: 600;
    }
    
    .admin-table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      overflow: hidden;
      margin-top: 20px;
    }
    
    .admin-table th,
    .admin-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid rgba(255, 107, 107, 0.1);
    }
    
    .admin-table th {
      background: rgba(255, 107, 107, 0.2);
      color: #ff6b6b;
      font-weight: bold;
      font-family: 'Orbitron', sans-serif;
    }
    
    .admin-table tr:hover {
      background: rgba(255, 107, 107, 0.05);
    }
    
    .status-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    
    .status-active {
      background: rgba(76, 175, 80, 0.2);
      color: #4caf50;
    }
    
    .status-pending {
      background: rgba(255, 193, 7, 0.2);
      color: #ffc107;
    }
    
    .status-unsubscribed {
      background: rgba(158, 158, 158, 0.2);
      color: #9e9e9e;
    }
    
    .admin-btn {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin: 2px;
    }
    
    .btn-edit {
      background: #4ecdc4;
      color: white;
    }
    
    .btn-edit:hover {
      background: #45b7d1;
      transform: translateY(-2px);
    }
    
    .btn-delete {
      background: #ff6b6b;
      color: white;
    }
    
    .btn-delete:hover {
      background: #ff5252;
      transform: translateY(-2px);
    }
    
    .btn-test {
      background: #96ceb4;
      color: white;
    }
    
    .btn-test:hover {
      background: #85c1a3;
      transform: translateY(-2px);
    }
    
    .test-email-form {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 30px;
      border: 2px solid rgba(255, 107, 107, 0.2);
    }
    
    .test-email-form h3 {
      color: #ff6b6b;
      margin-bottom: 15px;
      font-family: 'Orbitron', sans-serif;
    }
    
    .test-email-form form {
      display: flex;
      gap: 10px;
      align-items: end;
    }
    
    .test-email-form input {
      flex: 1;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
    }
    
    .test-email-form button {
      padding: 12px 24px;
      background: linear-gradient(135deg, #96ceb4, #85c1a3);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
    }
  </style>
</head>
<body class="business-page">
  <div class="business-background"></div>

  <!-- Navigation -->
  <nav class="navbar business-nav">
    <div class="nav-container">
      <a href="index.html" class="nav-logo">🐕 DogPics Weekly - Admin</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="index.html" class="nav-link">Back to Portfolio</a></li>
        <li class="nav-item"><a href="../business.php" class="nav-link">Subscribe</a></li>
        <li class="nav-item"><a href="admin.php" class="nav-link active">Admin</a></li>
        <li class="nav-item"><a href="users.php" class="nav-link">Users</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link">Logout</a></li>
      </ul>
    </div>
  </nav>

  <main class="business-main">
    <section class="subscription-section">
      <div class="subscription-container">
        <h2>Admin Dashboard</h2>
        <p class="subscription-description">
          Manage all subscriptions and send test emails.
        </p>
        
        <?php if ($error): ?>
          <div style="background: rgba(255, 107, 107, 0.2); border: 2px solid #ff6b6b; color: #ff6b6b; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>
        
        <?php if ($message): ?>
          <div style="background: rgba(109, 241, 255, 0.2); border: 2px solid #6df1ff; color: #6df1ff; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($message); ?>
          </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="admin-stats">
          <div class="stat-card">
            <h3><?php echo $stats['total']; ?></h3>
            <p>Total Subscriptions</p>
          </div>
          <div class="stat-card">
            <h3><?php echo $stats['active']; ?></h3>
            <p>Active Subscriptions</p>
          </div>
          <div class="stat-card">
            <h3><?php echo $stats['pending']; ?></h3>
            <p>Pending Subscriptions</p>
          </div>
        </div>
        
        <!-- Test Email Form -->
        <div class="test-email-form">
          <h3>Send Test Email</h3>
          <form action="admin/send.php" method="post">
            <input type="email" name="email" placeholder="Enter email address" required>
            <button type="submit">Send Test Email</button>
          </form>
        </div>
        
        <!-- Subscriptions Table -->
        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Dog Preference</th>
                <th>Delivery Day</th>
                <th>Newsletter</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($subscriptions)): ?>
                <tr>
                  <td colspan="9" style="text-align: center; padding: 40px; color: #666;">
                    No subscriptions found.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($subscriptions as $sub): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($sub['id']); ?></td>
                    <td><?php echo htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($sub['email']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst(str_replace('-', ' ', $sub['dog_preference']))); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($sub['delivery_day'])); ?></td>
                    <td><?php echo $sub['newsletter'] ? '✓' : '✗'; ?></td>
                    <td>
                      <span class="status-badge status-<?php echo $sub['status']; ?>">
                        <?php echo ucfirst($sub['status']); ?>
                      </span>
                    </td>
                    <td><?php echo date('Y-m-d H:i', strtotime($sub['created_at'])); ?></td>
                    <td>
                      <form action="admin/update.php" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $sub['id']; ?>">
                        <select name="status" onchange="this.form.submit()" style="padding: 5px; border-radius: 5px; border: 1px solid #ddd;">
                          <option value="pending" <?php echo $sub['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                          <option value="active" <?php echo $sub['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                          <option value="unsubscribed" <?php echo $sub['status'] === 'unsubscribed' ? 'selected' : ''; ?>>Unsubscribed</option>
                        </select>
                      </form>
                      <form action="admin/delete.php" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subscription?');">
                        <input type="hidden" name="id" value="<?php echo $sub['id']; ?>">
                        <button type="submit" class="admin-btn btn-delete">Delete</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <footer class="business-footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>🐕 DogPics Weekly</h3>
        <p>Admin Dashboard</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
          <li><a href="index.html">Back to Portfolio</a></li>
          <li><a href="../business.php">Subscribe</a></li>
          <li><a href="users.php">User Management</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bryce Normile. All rights reserved. Live, Laugh, Love.</p>
    </div>
  </footer>
</body>
</html>

