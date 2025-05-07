<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log function to track execution
function logMessage($message) {
    // Uncomment to write to a log file
    // file_put_contents('email_log.txt', date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
    echo "<div style='padding:5px;margin:5px;border:1px solid #ccc;'>" . $message . "</div>";
}

logMessage("Script started");

// Get form data
$name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? filter_var($_POST['subject'], FILTER_SANITIZE_STRING) : '';
$message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';

logMessage("Form data received - Name: $name, Email: $email, Subject: $subject");

// Basic validation
if ($name && $email && $subject && $message) {
    logMessage("All required fields present");
    
    // Check mail function availability
    if (!function_exists('mail')) {
        logMessage("ERROR: PHP mail() function does not exist or is disabled");
        echo "Server configuration error: mail function not available";
        exit;
    }
    
    // Mail configuration info (for debugging)
    $mailConfig = "Mail Configuration - sendmail_path: " . ini_get('sendmail_path') . 
                  ", SMTP: " . ini_get('SMTP') . 
                  ", smtp_port: " . ini_get('smtp_port');
    logMessage($mailConfig);
    
    // Recipient email address
    $to = 'georgeshaddad560@gmail.com';
    
    // Email subject
    $emailSubject = "Contact Form Submission: $subject";
    
    // Email body - both HTML and plain text versions
    
    // HTML version
    $emailBodyHtml = "
    <html>
    <head>
        <title>Contact Form Submission</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; }
            h2 { color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; }
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
    
    // Plain text version as fallback
    $emailBodyText = "You have received a new message from your website contact form.\n\n";
    $emailBodyText .= "Name: $name\n";
    $emailBodyText .= "Email: $email\n";
    $emailBodyText .= "Subject: $subject\n";
    $emailBodyText .= "Message:\n$message\n";
    $emailBodyText .= "\nSent on: " . date("Y-m-d H:i:s");
    
    // Try both HTML and plain text methods
    
    // Method 1: HTML Email with proper headers
    $headersHtml = "From: $name <$email>\r\n";
    $headersHtml .= "Reply-To: $email\r\n";
    $headersHtml .= "MIME-Version: 1.0\r\n";
    $headersHtml .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headersHtml .= "X-Mailer: PHP/" . phpversion();
    
    logMessage("Attempting to send HTML email");
    $mailResultHtml = mail($to, $emailSubject, $emailBodyHtml, $headersHtml);
    
    if ($mailResultHtml) {
        logMessage("HTML email apparently sent successfully");
    } else {
        $error = error_get_last();
        logMessage("Failed to send HTML email. Error: " . ($error ? $error['message'] : 'Unknown error'));
    }
    
    // Method 2: Plain text email (as backup)
    $headersText = "From: $name <$email>\r\n";
    $headersText .= "Reply-To: $email\r\n";
    $headersText .= "X-Mailer: PHP/" . phpversion();
    
    logMessage("Attempting to send plain text email as backup");
    $mailResultText = mail($to, $emailSubject, $emailBodyText, $headersText);
    
    if ($mailResultText) {
        logMessage("Plain text email apparently sent successfully");
    } else {
        $error = error_get_last();
        logMessage("Failed to send plain text email. Error: " . ($error ? $error['message'] : 'Unknown error'));
    }
    
    // Response to user
    if ($mailResultHtml || $mailResultText) {
        logMessage("At least one email method succeeded");
        
        // For AJAX responses
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
        } else {
            echo "Message sent successfully!";
        }
    } else {
        logMessage("Both email methods failed");
        
        // Try a final simple attempt with minimal parameters
        $simpleResult = mail($to, "URGENT: Contact Form Attempt", "Someone tried to contact you but emails are failing.\n\nName: $name\nEmail: $email\n\nPlease check your server mail configuration.");
        
        if ($simpleResult) {
            logMessage("Simple notification email sent");
        } else {
            logMessage("All email attempts failed. Server likely cannot send mail.");
        }
        
        // Check server environment for clues
        logMessage("Server Environment - PHP: " . phpversion() . ", Software: " . $_SERVER['SERVER_SOFTWARE']);
        
        // For AJAX responses
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to send message. Your server may not be configured to send email.',
                'debug_info' => 'Check that your hosting provider allows PHP mail()'
            ]);
        } else {
            echo "Failed to send message. Please try again later or contact the administrator.";
            echo "<p>Troubleshooting: Your server may not be configured to send email. Contact your hosting provider.</p>";
        }
    }
} else {
    logMessage("Missing required fields");
    
    // List which fields are missing
    $missingFields = [];
    if (!$name) $missingFields[] = 'name';
    if (!$email) $missingFields[] = 'email';
    if (!$subject) $missingFields[] = 'subject';
    if (!$message) $missingFields[] = 'message';
    
    logMessage("Missing fields: " . implode(', ', $missingFields));
    
    // For AJAX responses
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false, 
            'message' => 'Please fill in all the fields.',
            'missing_fields' => $missingFields
        ]);
    } else {
        echo "Please fill in all the fields.";
    }
}

// Alternative email solutions (commented out - uncomment for production use)
/*
// 1. Using PHP's built-in mail() function often fails on many hosting providers.
// 2. Consider using a proper email library like PHPMailer:
//    - Install via Composer: composer require phpmailer/phpmailer
//    - Docs: https://github.com/PHPMailer/PHPMailer
// 3. Or use an email service API:
//    - SendGrid: https://sendgrid.com/
//    - Mailgun: https://www.mailgun.com/
//    - AWS SES: https://aws.amazon.com/ses/
*/

// If you want to implement PHPMailer, here's a starting point:
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;           // Enable verbose debug output
    $mail->isSMTP();                                 // Send using SMTP
    $mail->Host       = 'smtp.example.com';          // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                        // Enable SMTP authentication
    $mail->Username   = 'user@example.com';          // SMTP username
    $mail->Password   = 'password';                  // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port       = 587;                         // TCP port to connect to

    // Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');
    $mail->addReplyTo('info@example.com', 'Information');

    // Content
    $mail->isHTML(true);                             // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body';
    $mail->AltBody = 'This is the plain text message body for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
*/
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
