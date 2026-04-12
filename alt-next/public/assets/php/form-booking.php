<?php
/**
 * form-booking.php
 * 
 * Standard ThemeForest Booking Form Handler
 * ------------------------------------------
 * Handles form submissions from booking form.
 * Sanitizes inputs, validates required fields,
 * and sends email notification to site admin.
 */

header('Content-Type: application/json');

$to = "hello@adeluxpadel.com";
$subject_prefix = "[Booking Request] ";

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
$date    = clean_input($_POST["date"] ?? '');
$time    = clean_input($_POST["time"] ?? '');
$court   = clean_input($_POST["court"] ?? '');
$message = clean_input($_POST["message"] ?? '');

// Validation of field
if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($court)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    exit;
}

// Validation of email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email address."]);
    exit;
}

// Validasi of date format
$date_check = DateTime::createFromFormat('Y-m-d', $date);
if (!$date_check && !preg_match('/\d{2}\/\d{2}\/\d{4}/', $date)) {
    echo json_encode(["status" => "error", "message" => "Invalid date format."]);
    exit;
}

$email_subject = $subject_prefix . $name;
$email_body = "
You have received a new booking request from your website:

-------------------------------------------------
Name       : {$name}
Email      : {$email}
Phone      : {$phone}
Date       : {$date}
Time Slot  : {$time}
Court Type : {$court}

Additional Notes:
{$message}
-------------------------------------------------

This booking request was sent from your website booking form.
";

// Header email
$headers = "From: {$name} <{$email}>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $email_subject, $email_body, $headers)) {
    echo json_encode(["status" => "success", "message" => "Your booking request has been sent successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to send booking request. Please try again later."]);
}
?>