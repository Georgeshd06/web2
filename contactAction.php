<?php
// Check if form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data with better input sanitization
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['subject']) ? filter_var($_POST['subject'], FILTER_SANITIZE_STRING) : '';
    $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';
    
    // Extra validation
    $errors = [];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if all required fields are filled
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errors[] = "All fields are required";
    }
    
    // Process if no errors
    if (empty($errors)) {
        // Recipient email address
        $to = 'georgeshaddad560@gmail.com';
        
        // Email subject with better formatting
        $emailSubject = "Contact Form: " . $subject;
        
        // Create email content in HTML format for better appearance
        $emailContent = "
        <html>
        <head>
            <title>Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                h2 { color: #2c3e50; border-bottom: 1px solid #eee; padding-bottom: 10px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; }
                .message { background-color: #f9f9f9; padding: 15px; border-left: 4px solid #ddd; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>New Contact Form Submission</h2>
                <div class='field'>
                    <span class='label'>Name:</span> {$name}
                </div>
                <div class='field'>
                    <span class='label'>Email:</span> {$email}
                </div>
                <div class='field'>
                    <span class='label'>Subject:</span> {$subject}
                </div>
                <div class='field'>
                    <span class='label'>Message:</span>
                    <div class='message'>" . nl2br($message) . "</div>
                </div>
                <p>This message was sent from your website contact form on " . date("Y-m-d H:i:s") . "</p>
            </div>
        </body>
        </html>
        ";
        
        // Email headers for HTML email
        $headers = "From: {$name} <{$email}>\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // Also prepare a plain text version as fallback
        $plainText = "
        New Contact Form Submission
        --------------------------
        Name: {$name}
        Email: {$email}
        Subject: {$subject}
        Message: 
        {$message}
        
        Sent on: " . date("Y-m-d H:i:s");
        
        // Send email with error handling
        if (mail($to, $emailSubject, $emailContent, $headers)) {
            // Success response - you can customize this for different formats
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully. We will contact you soon!']);
        } else {
            // Error response
            header('Content-Type: application/json');
            http_response_code(500); // Internal server error
            echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later or contact us directly.']);
        }
    } else {
        // Return validation errors
        header('Content-Type: application/json');
        http_response_code(400); // Bad request
        echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
    }
} else {
    // Not a POST request
    header('Content-Type: application/json');
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
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
