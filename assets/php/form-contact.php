<?php
/**
 * form-contact.php
 * 
 * Handles form submissions from contact form.
 * Sanitizes inputs, validates required fields,
 * and sends email notification to site admin.
 */

header('Content-Type: application/json');

$to = "hello@adeluxpadel.com";
$subject_prefix = "[Website Contact] ";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$name    = clean_input($_POST["name"] ?? '');
$email   = clean_input($_POST["email"] ?? '');
$phone   = clean_input($_POST["phone"] ?? '');
$subject = clean_input($_POST["subject"] ?? '');
$message = clean_input($_POST["message"] ?? '');

// Validation field
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields (Name, Email, Message)."]);
    exit;
}

// Validation email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    exit;
}

// Siapkan isi email
$email_subject = $subject_prefix . ($subject ?: "No Subject");
$email_body = "
You have received a new message from your website contact form:

-------------------------------------------------
Name: {$name}
Email: {$email}
Phone: {$phone}
Subject: {$subject}

Message:
{$message}
-------------------------------------------------

This email was sent from your website contact form.
";

// Header email
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode(["status" => "success", "message" => "Your message has been sent successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to send the message. Please try again later."]);
}
?>