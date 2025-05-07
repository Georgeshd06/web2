<?php
// Get form data
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
//Checks if the name field was submitted via POST.
//If it exists: htmlspecialchars() is used to convert special characters (like <, >, &) to HTML-safe entities, helping prevent XSS (Cross-Site Scripting) attacks.
//If it doesn’t exist: an empty string '' is assigned
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
$subject = isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '';
$message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

if ($name && $email && $subject && $message) {
  // Recipient email address
  $to = 'georgeshaddad560@gmail.com'; // Change this to your email

  // Email subject
  $emailSubject = "Contact Form Submission: $subject";

  // Email body
  $emailBody = "You have received a new message from your website contact form.\n\n";
  $emailBody .= "Name: $name\n";
  $emailBody .= "Email: $email\n";
  $emailBody .= "Subject: $subject\n";
  $emailBody .= "Message:\n$message\n";

  // Email headers
  $headers = "From: $name <$email>\r\n";
  $headers .= "Reply-To: $email\r\n";

  // Send email
  if (mail($to, $emailSubject, $emailBody, $headers)) {
      echo "Message sent successfully!";
  } else {
      echo "Failed to send message. Please try again later.";
  }
} else {
  echo "Please fill in all the fields.";
}

// You could add additional processing here if needed
// For example, saving to database, sending emails, etc.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Contact form submission confirmation">
  <title>Thank You | Professional Portfolio</title>
  <link rel="icon" type="image/png" href="contact.png">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .thank-you-container {
      background: white;
      border-radius: 8px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 40px;
      margin: 40px auto;
      max-width: 800px;
    }

    .thank-you-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .thank-you-header h2 {
      font-size: 32px;
      color: #2c3e50;
      margin-bottom: 16px;
    }

    .thank-you-header p {
      font-size: 18px;
      color: #666;
    }

    .submission-details {
      background: #f8f9fa;
      border-radius: 6px;
      padding: 25px;
      margin-top: 20px;
    }

    .detail-row {
      margin-bottom: 15px;
      display: flex;
    }

    .detail-row:last-child {
      margin-bottom: 0;
    }

    .detail-label {
      font-weight: 600;
      color: #2c3e50;
      width: 100px;
      min-width: 100px;
    }

    .detail-value {
      color: #555;
      flex: 1;
    }

    .message-content {
      white-space: pre-wrap;
    }

    .back-button {
      display: inline-block;
      background-color: #3498db;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      margin-top: 25px;
      transition: all 0.3s ease;
    }

    .back-button:hover {
      background-color: #2980b9;
      transform: translateY(-2px);
    }

    .action-row {
      text-align: center;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Contact Submission</h1>
    <nav>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="cv.html">My CV</a></li>
        <li><a href="schedule.html">Schedule</a></li>
        <li><a href="contact.html">Contact</a></li>
        <li><a href="quiz.html">Quiz</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="container">
      <div class="thank-you-container">
        <div class="thank-you-header">
          <h2>Thank you, your message has been submitted!</h2>
          <p>We appreciate you contacting us and will respond as soon as possible.</p>
        </div>

        <div class="submission-details">
          <h3>Your submission details:</h3>
          
          <div class="detail-row">
            <div class="detail-label">Name:</div>
            <div class="detail-value"><?php echo $name; ?></div>
          </div>
          
          <div class="detail-row">
            <div class="detail-label">Email:</div>
            <div class="detail-value"><?php echo $email; ?></div>
          </div>
          
          <div class="detail-row">
            <div class="detail-label">Subject:</div>
            <div class="detail-value"><?php echo $subject ? $subject : 'No subject provided'; ?></div>
          </div>
          
          <div class="detail-row">
            <div class="detail-label">Message:</div>
            <div class="detail-value message-content"><?php echo $message; ?></div>
          </div>
        </div>

        <div class="action-row">
          <a href="contact.html" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Contact Page
          </a>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-nav">
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="cv.html">My CV</a></li>
            <li><a href="schedule.html">Schedule</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="quiz.html">Quiz</a></li>
            <li><a href="https://www.privacypolicies.com/blog/privacy-policy-template/" target="_blank">Privacy Policy</a></li>
            <li><a href="https://www.termsfeed.com/blog/sample-terms-of-service-template/" target="_blank">Terms of Service</a></li>
          </ul>
        </div>
        
        <div class="social-links">
          <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/georges.hd?igsh=Y3dsM3JjOGpuaW5w&utm_source=qr" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
        
        <div class="copyright">
          <p>&copy; 2025 Professional Website by Georges Haddad <span>|</span> Ryan Bou Mansour</p>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
