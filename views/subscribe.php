<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weekly Dog Pictures - Subscribe</title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="business-styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body class="business-page">
  <div class="business-background"></div>

  <!-- Navigation -->
  <nav class="navbar business-nav">
    <div class="nav-container">
      <a href="index.html" class="nav-logo">🐕 DogPics Weekly</a>
      <ul class="nav-menu">
        <li class="nav-item"><a href="index.html" class="nav-link">Back to Portfolio</a></li>
        <li class="nav-item"><a href="business.php" class="nav-link active">Subscribe</a></li>
        <li class="nav-item"><a href="business/unsubscribe.php" class="nav-link">Unsubscribe</a></li>
      </ul>
    </div>
  </nav>

  <main class="business-main">
    <!-- Hero Section -->
    <section class="business-hero">
      <div class="hero-content">
        <h1>🐕 Weekly Dog Pictures</h1>
        <p class="hero-subtitle">Get adorable dog pictures delivered to your inbox every week!</p>
        <p class="hero-description">
          Join thousands of happy subscribers who start their week with a smile. 
          Each Monday, you'll be sent a carefully curated, high-quality dog picture 
          that's guaranteed to brighten your day.
        </p>
        <div class="hero-features">
          <div class="feature">✨ Free Forever</div>
          <div class="feature">📧 Weekly Delivery</div>
          <div class="feature">🐾 Adorable Dogs</div>
        </div>
      </div>
    </section>

    <!-- Subscription Form -->
    <section class="subscription-section">
      <div class="subscription-container">
        <h2>Subscribe Now</h2>
        <p class="subscription-description">
          Fill out the form below and you'll receive your first dog picture within 24 hours!
        </p>
        
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
          <form class="subscription-form" action="business.php" method="post" id="subscriptionForm">
            <div class="form-group">
              <label for="firstName">First Name <span class="required">*</span></label>
              <input type="text" id="firstName" name="firstName" required placeholder="Enter your first name" value="<?php echo htmlspecialchars($_POST['firstName'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
              <label for="lastName">Last Name <span class="required">*</span></label>
              <input type="text" id="lastName" name="lastName" required placeholder="Enter your last name" value="<?php echo htmlspecialchars($_POST['lastName'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
              <label for="email">Email Address <span class="required">*</span></label>
              <input type="email" id="email" name="email" required placeholder="your@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
              <label for="dogPreference">Dog Preference</label>
              <select id="dogPreference" name="dogPreference">
                <option value="any" <?php echo (($_POST['dogPreference'] ?? 'any') === 'any') ? 'selected' : ''; ?>>Any adorable dog</option>
                <option value="puppies" <?php echo (($_POST['dogPreference'] ?? '') === 'puppies') ? 'selected' : ''; ?>>Puppies</option>
                <option value="golden-retrievers" <?php echo (($_POST['dogPreference'] ?? '') === 'golden-retrievers') ? 'selected' : ''; ?>>Golden Retrievers</option>
                <option value="labradors" <?php echo (($_POST['dogPreference'] ?? '') === 'labradors') ? 'selected' : ''; ?>>Labradors</option>
                <option value="huskies" <?php echo (($_POST['dogPreference'] ?? '') === 'huskies') ? 'selected' : ''; ?>>Huskies</option>
                <option value="bulldogs" <?php echo (($_POST['dogPreference'] ?? '') === 'bulldogs') ? 'selected' : ''; ?>>Bulldogs</option>
                <option value="poodles" <?php echo (($_POST['dogPreference'] ?? '') === 'poodles') ? 'selected' : ''; ?>>Poodles</option>
                <option value="mixed-breeds" <?php echo (($_POST['dogPreference'] ?? '') === 'mixed-breeds') ? 'selected' : ''; ?>>Mixed Breeds</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="deliveryDay">Preferred Delivery Day</label>
              <select id="deliveryDay" name="deliveryDay">
                <option value="monday" <?php echo (($_POST['deliveryDay'] ?? 'monday') === 'monday') ? 'selected' : ''; ?>>Monday</option>
                <option value="tuesday" <?php echo (($_POST['deliveryDay'] ?? '') === 'tuesday') ? 'selected' : ''; ?>>Tuesday</option>
                <option value="wednesday" <?php echo (($_POST['deliveryDay'] ?? '') === 'wednesday') ? 'selected' : ''; ?>>Wednesday</option>
                <option value="thursday" <?php echo (($_POST['deliveryDay'] ?? '') === 'thursday') ? 'selected' : ''; ?>>Thursday</option>
                <option value="friday" <?php echo (($_POST['deliveryDay'] ?? '') === 'friday') ? 'selected' : ''; ?>>Friday</option>
              </select>
            </div>
            
            <div class="form-group checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" id="newsletter" name="newsletter" <?php echo isset($_POST['newsletter']) ? 'checked' : 'checked'; ?>>
                <span class="checkmark"></span>
                I'd also like to receive occasional dog-related news and tips
              </label>
            </div>
            
            <div class="form-group checkbox-group">
              <label class="checkbox-label">
                <input type="checkbox" id="terms" name="terms" required>
                <span class="checkmark"></span>
                I agree to the <a href="terms.html" target="_blank">Terms of Service</a> and <a href="privacy.html" target="_blank">Privacy Policy</a>
              </label>
            </div>
            
            <button type="submit" class="subscribe-btn">
              <span class="btn-text">Subscribe to Dog Pictures</span>
              <span class="btn-icon">🐕</span>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section">
      <h2>What Do The Subscribers Think?</h2>
      <div class="testimonials-grid">
        <div class="testimonial-card">
          <div class="testimonial-content">
            <p>"This is the best email I get all week! My Monday mornings are so much better now."</p>
          </div>
          <div class="testimonial-author">
            <strong>Sarah M.</strong>
            <span>Subscriber since 2024</span>
          </div>
        </div>
        
        <div class="testimonial-card">
          <div class="testimonial-content">
            <p>"I forward these to my whole family. We have a group chat just for the weekly dog pictures!"</p>
          </div>
          <div class="testimonial-author">
            <strong>Mike R.</strong>
            <span>Subscriber since 2024</span>
          </div>
        </div>
        
        <div class="testimonial-card">
          <div class="testimonial-content">
            <p>"Perfect way to start the work week. These pictures never fail to make me smile."</p>
          </div>
          <div class="testimonial-author">
            <strong>Emily K.</strong>
            <span>Subscriber since 2024</span>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <h2>Frequently Asked Questions</h2>
      <div class="faq-container">
        <div class="faq-item">
          <h3>How much does this cost?</h3>
          <p>It's completely free! We believe everyone deserves weekly dog pictures without any cost.</p>
        </div>
        
        <div class="faq-item">
          <h3>How often will I receive pictures?</h3>
          <p>You'll receive one adorable dog picture every week on your chosen delivery day.</p>
        </div>
        
        <div class="faq-item">
          <h3>Can I unsubscribe anytime?</h3>
          <p>Absolutely! Every email includes an unsubscribe link, and you can stop receiving pictures at any time.</p>
        </div>
        
        <div class="faq-item">
          <h3>Where do the dog pictures come from?</h3>
          <p>I source my pictures from various reputable sources, ensuring they're high-quality and properly licensed.</p>
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
          <li><a href="index.html">Back to Portfolio</a></li>
          <li><a href="business/unsubscribe.php">Unsubscribe</a></li>
          <li><a href="terms.html">Terms</a></li>
          <li><a href="privacy.html">Privacy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Bryce Normile. All rights reserved. Live, Laugh, Love.</p>
    </div>
  </footer>
  
  <script>
  // Debug: Log form submission
  document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
    console.log('Form is submitting...');
    console.log('Form action:', this.action);
    console.log('Form method:', this.method);
    console.log('Current URL:', window.location.href);
    
    // Check if terms checkbox is checked
    var termsCheckbox = document.getElementById('terms');
    if (!termsCheckbox.checked) {
      console.log('Terms checkbox not checked!');
      alert('Please agree to the Terms of Service');
      e.preventDefault();
      return false;
    }
    
    console.log('Form validation passed, submitting to:', this.action || window.location.href);
  });
  </script>
</body>
</html>

