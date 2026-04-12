<?php
header('Content-Type: text/plain; charset=UTF-8');

$receiving_email_address = 'fathimathulfarsaana@gmail.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed.');
}

function clean_input($value) {
  return trim(str_replace(["\r", "\n"], ' ', $value));
}

$name = isset($_POST['name']) ? clean_input($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? clean_input($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($name === '' || $email === '' || $subject === '' || $message === '') {
  http_response_code(400);
  exit('Please fill in all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  exit('Please enter a valid email address.');
}

$safe_subject = 'Portfolio Contact: ' . $subject;
$email_body = "You received a new message from your portfolio contact form.\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Subject: " . $subject . "\n\n";
$email_body .= "Message:\n" . $message . "\n";

$headers = [];
$headers[] = 'From: Portfolio Contact <no-reply@localhost>';
$headers[] = 'Reply-To: ' . $email;
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$sent = mail(
  $receiving_email_address,
  $safe_subject,
  $email_body,
  implode("\r\n", $headers)
);

if (!$sent) {
  http_response_code(500);
  exit('Message could not be sent. Please try again later.');
}

exit('OK');
?>
