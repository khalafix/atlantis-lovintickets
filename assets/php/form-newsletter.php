<?php
/**
 * form-newsletter.php
 * 
 * Handles newsletter subscriptions from website.
 * Sanitizes input, validates email, and sends a 
 * notification email to the site admin.
 */

header('Content-Type: application/json');

$to = "hello@adeluxpadel.com";
$subject_prefix = "[Newsletter Subscription] ";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$email = clean_input($_POST["newsletter"] ?? '');

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "Please enter your email address."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit;
}

$email_subject = $subject_prefix . $email;
$email_body = "
A new user has subscribed to your newsletter.

-------------------------------------------
Email Address: {$email}
-------------------------------------------

This email was sent from your website newsletter form.
";

// Header email
$headers = "From: Website Newsletter <no-reply@" . $_SERVER['SERVER_NAME'] . ">\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode(["status" => "success", "message" => "Thank you for subscribing to our newsletter."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to subscribe. Please try again later."]);
}
?>