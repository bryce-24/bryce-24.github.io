<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unsubscribe - DogPics Weekly</title>
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
        <li class="nav-item"><a href="unsubscribe.php" class="nav-link active">Unsubscribe</a></li>
      </ul>
    </div>
  </nav>

  <main class="business-main">
    <section class="subscription-section">
      <div class="subscription-container">
        <h2>Unsubscribe</h2>
        <p class="subscription-description">
          We're sorry to see you go! You can unsubscribe using the link from your email or by entering your email address below.
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
            <div style="text-align: center; padding: 20px;">
              <p style="font-size: 1.1rem; color: #333; margin-bottom: 20px;">
                Are you sure you want to unsubscribe <strong><?php echo htmlspecialchars($subscription['email']); ?></strong>?
              </p>
              <a href="unsubscribe.php?token=<?php echo htmlspecialchars($subscription['unsubscribe_token']); ?>&action=confirm" class="subscribe-btn" style="display: inline-block; background: linear-gradient(135deg, #ff6b6b, #ff8e8e); text-decoration: none; padding: 20px 30px; border-radius: 15px; color: white; font-weight: bold;">
                <span class="btn-text">Yes, Unsubscribe Me</span>
                <span class="btn-icon">✉️</span>
              </a>
            </div>
          <?php else: ?>
            <form class="subscription-form" action="unsubscribe.php" method="post">
              <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" required placeholder="your@email.com">
              </div>
              
              <button type="submit" class="subscribe-btn" style="background: linear-gradient(135deg, #ff6b6b, #ff8e8e);">
                <span class="btn-text">Unsubscribe</span>
                <span class="btn-icon">✉️</span>
              </button>
            </form>
          <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding: 20px; background: rgba(255, 107, 107, 0.05); border-radius: 12px;">
          <p style="color: #666; line-height: 1.6;">
            Changed your mind? <a href="../business.php" style="color: #ff6b6b; font-weight: 600;">Subscribe again</a> instead.
          </p>
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

