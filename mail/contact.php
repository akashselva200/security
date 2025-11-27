<?php
if(empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  http_response_code(400); // 400 Bad Request is more appropriate for client-side errors
  echo "Please fill all fields and provide a valid email.";
  exit();
}

// Sanitize input
$name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$m_subject = htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');

// Re-validate email after sanitization
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "Invalid email format.";
    exit();
}

$to = "your-email@yoursecurity.com"; // Change this to the email address where you want to receive messages.
$subject = "New Contact Form Submission: $m_subject";

$body = "You have received a new message from your website contact form.\n\n";
$body .= "Here are the details:\n\n";
$body .= "Name: $name\n\n";
$body .= "Email: $email\n\n";
$body .= "Subject: $m_subject\n\n";
$body .= "Message:\n$message\n";

// To prevent email header injection, it's crucial to not use user input directly in headers.
// Set a static 'From' address and use 'Reply-To' for the user's email.
// This also helps with email deliverability (SPF/DKIM).
$headers = "From: noreply@yoursecurity.com\r\n"; // Change this to use your actual domain.
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

if(mail($to, $subject, $body, $headers)) {
    http_response_code(200);
    echo "Thank you! Your message has been sent.";
} else {
    http_response_code(500);
    echo "Oops! Something went wrong and we couldn't send your message.";
}
?>
