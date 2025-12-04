<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subscription Confirmed - DogPics Weekly</title>
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
        <li class="nav-item"><a href="unsubscribe.php" class="nav-link">Unsubscribe</a></li>
      </ul>
    </div>
  </nav>

  <main class="business-main">
    <section class="subscription-section">
      <div class="subscription-container">
        <div style="text-align: center; padding: 40px 20px;">
          <div style="font-size: 5rem; margin-bottom: 30px;">🎉</div>
          <h2 style="color: #ff6b6b; font-size: 2.5rem; margin-bottom: 20px;">Thank You for Subscribing!</h2>
          <p style="font-size: 1.2rem; color: #666; margin-bottom: 30px; line-height: 1.6;">
            <?php echo htmlspecialchars($message); ?>
          </p>
          <p style="font-size: 1rem; color: #666; margin-bottom: 40px; line-height: 1.6;">
            Get ready to receive adorable dog pictures in your inbox! Your first picture will arrive soon.
          </p>
          
          <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 40px;">
            <a href="../business.php" style="display: inline-block; padding: 15px 30px; background: rgba(255, 107, 107, 0.1); color: #ff6b6b; text-decoration: none; border: 2px solid #ff6b6b; border-radius: 12px; font-weight: 600; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
              Back to Home
            </a>
          </div>
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

