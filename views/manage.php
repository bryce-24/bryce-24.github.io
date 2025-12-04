<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Subscription - DogPics Weekly</title>
  <link rel="stylesheet" href="../styles.css" />
  <link rel="stylesheet" href="../business-styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="business-page">
  <div class="business-background"></div>

  <!-- Navigation -->
  <nav class="navbar business-nav">
    <div class="nav-container">
      <a href="index.html" class="nav-logo">🐕 DogPics Weekly</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="../index.html" class="nav-link">Back to Portfolio</a></li>
        <li class="nav-item"><a href="../business.php" class="nav-link">Subscribe</a></li>
        <li class="nav-item"><a href="manage.php" class="nav-link active">Manage</a></li>
        <li class="nav-item"><a href="unsubscribe.php" class="nav-link">Unsubscribe</a></li>
      </ul>
    </div>
  </nav>

  <main class="business-main">
    <section class="subscription-section">
      <div class="subscription-container">
        <h2>Manage Your Subscription</h2>
        <p class="subscription-description">
          Enter your email address to view and update your subscription preferences.
        </p>
        
        <?php 
        $error = $_SESSION['subscription_error'] ?? '';
        $message = $_SESSION['subscription_message'] ?? '';
        unset($_SESSION['subscription_error']);
        unset($_SESSION['subscription_message']);
        ?>
        
        <?php if ($error): ?>
          <div style="background: rgba(255, 107, 107, 0.2); border: 2px solid #ff6b6b; color: #ff6b6b; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>
        
        <?php if ($message): ?>
          <div style="background: rgba(109, 241, 255, 0.2); border: 2px solid #6df1ff; color: #6df1ff; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        
        <div class="subscription-form-container">
          <?php if ($subscription): ?>
            <form class="subscription-form" action="manage.php" method="post">
              <input type="hidden" name="email" value="<?php echo htmlspecialchars($subscription['email']); ?>">
              
              <div class="form-group">
                <label>Email Address</label>
                <input type="email" value="<?php echo htmlspecialchars($subscription['email']); ?>" disabled style="background: #f5f5f5;">
              </div>
              
              <div class="form-group">
                <label>Name</label>
                <input type="text" value="<?php echo htmlspecialchars($subscription['first_name'] . ' ' . $subscription['last_name']); ?>" disabled style="background: #f5f5f5;">
              </div>
              
              <div class="form-group">
                <label>Subscription Status</label>
                <input type="text" value="<?php echo ucfirst($subscription['status']); ?>" disabled style="background: #f5f5f5;">
              </div>
              
              <div class="form-group">
                <label for="dogPreference">Dog Preference</label>
                <select id="dogPreference" name="dogPreference">
                  <option value="any" <?php echo ($subscription['dog_preference'] === 'any') ? 'selected' : ''; ?>>Any adorable dog</option>
                  <option value="puppies" <?php echo ($subscription['dog_preference'] === 'puppies') ? 'selected' : ''; ?>>Puppies</option>
                  <option value="golden-retrievers" <?php echo ($subscription['dog_preference'] === 'golden-retrievers') ? 'selected' : ''; ?>>Golden Retrievers</option>
                  <option value="labradors" <?php echo ($subscription['dog_preference'] === 'labradors') ? 'selected' : ''; ?>>Labradors</option>
                  <option value="huskies" <?php echo ($subscription['dog_preference'] === 'huskies') ? 'selected' : ''; ?>>Huskies</option>
                  <option value="bulldogs" <?php echo ($subscription['dog_preference'] === 'bulldogs') ? 'selected' : ''; ?>>Bulldogs</option>
                  <option value="poodles" <?php echo ($subscription['dog_preference'] === 'poodles') ? 'selected' : ''; ?>>Poodles</option>
                  <option value="mixed-breeds" <?php echo ($subscription['dog_preference'] === 'mixed-breeds') ? 'selected' : ''; ?>>Mixed Breeds</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="deliveryDay">Preferred Delivery Day</label>
                <select id="deliveryDay" name="deliveryDay">
                  <option value="monday" <?php echo ($subscription['delivery_day'] === 'monday') ? 'selected' : ''; ?>>Monday</option>
                  <option value="tuesday" <?php echo ($subscription['delivery_day'] === 'tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                  <option value="wednesday" <?php echo ($subscription['delivery_day'] === 'wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                  <option value="thursday" <?php echo ($subscription['delivery_day'] === 'thursday') ? 'selected' : ''; ?>>Thursday</option>
                  <option value="friday" <?php echo ($subscription['delivery_day'] === 'friday') ? 'selected' : ''; ?>>Friday</option>
                </select>
              </div>
              
              <div class="form-group checkbox-group">
                <label class="checkbox-label">
                  <input type="checkbox" id="newsletter" name="newsletter" <?php echo $subscription['newsletter'] ? 'checked' : ''; ?>>
                  <span class="checkmark"></span>
                  I'd also like to receive occasional dog-related news and tips
                </label>
              </div>
              
              <button type="submit" class="subscribe-btn">
                <span class="btn-text">Update Preferences</span>
                <span class="btn-icon">💾</span>
              </button>
            </form>
          <?php else: ?>
            <form class="subscription-form" action="manage.php" method="get">
              <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" required placeholder="your@email.com" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">
              </div>
              
              <button type="submit" class="subscribe-btn">
                <span class="btn-text">Find My Subscription</span>
                <span class="btn-icon">🔍</span>
              </button>
            </form>
            
            <?php if (isset($_GET['email']) && empty($subscription)): ?>
              <div style="background: rgba(255, 107, 107, 0.2); border: 2px solid #ff6b6b; color: #ff6b6b; padding: 15px; border-radius: 12px; margin-top: 20px;">
                No subscription found for this email address.
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="business-footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>🐕 DogPics Weekly</h3>
        <p>Bringing joy to your inbox, one dog picture at a time.</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
          <li><a href="../index.html">Back to Portfolio</a></li>
          <li><a href="../business.php">Subscribe</a></li>
          <li><a href="unsubscribe.php">Unsubscribe</a></li>
          <li><a href="../terms.html">Terms</a></li>
          <li><a href="../privacy.html">Privacy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bryce Normile. All rights reserved. Live, Laugh, Love.</p>
    </div>
  </footer>
</body>
</html>

