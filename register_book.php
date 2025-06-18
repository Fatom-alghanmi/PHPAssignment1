<?php
session_start();
require_once('message.php');   // your email sending functions
require_once('database.php');  // your PDO database connection

// --- 1. Get and sanitize form inputs ---
$user_name = trim(filter_input(INPUT_POST, 'user_name'));
$password = filter_input(INPUT_POST, 'password');
$email_address = trim(filter_input(INPUT_POST, 'email_address'));

// --- 2. Validate required fields ---
if (empty($user_name) || empty($password) || empty($email_address)) {
    $_SESSION["add_error"] = "All fields are required.";
    header("Location: error.php");
    exit;
}

// --- 3. Check for duplicate username ---
$queryRegistrations = 'SELECT * FROM registrations WHERE userName = :userName';
$statement1 = $db->prepare($queryRegistrations);
$statement1->bindValue(':userName', $user_name);
$statement1->execute();
$existingUser = $statement1->fetch();
$statement1->closeCursor();

if ($existingUser) {
    $_SESSION["add_error"] = "Duplicate Username. Try again.";
    header("Location: error.php");
    exit;
}

// --- 4. Hash the password ---
$hash = password_hash($password, PASSWORD_DEFAULT);

// --- 5. Handle profile image upload ---
$profile_image = $_FILES['profile_image'] ?? null;
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
$upload_dir = 'images/';  // Make sure this folder exists and is writable
$filename = 'default.png'; // Default profile image if upload fails or not provided

if ($profile_image && $profile_image['error'] === UPLOAD_ERR_OK) {
    if (in_array($profile_image['type'], $allowed_types)) {
        // Securely get file extension
        $ext = pathinfo($profile_image['name'], PATHINFO_EXTENSION);
        // Generate a unique filename to avoid collisions
        $new_name = uniqid('profile_', true) . '.' . $ext;
        $destination = $upload_dir . $new_name;

        // Move uploaded file to the images folder
        if (move_uploaded_file($profile_image['tmp_name'], $destination)) {
            $filename = $new_name;
        }
        // else keep default.png
    }
    // else keep default.png if file type not allowed
}

// --- 6. Insert new user into database ---
$query = 'INSERT INTO registrations
    (userName, password, emailAddress, profile_image)
    VALUES
    (:userName, :password, :emailAddress, :profileImage)';

$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->bindValue(':password', $hash);
$statement->bindValue(':emailAddress', $email_address);
$statement->bindValue(':profileImage', $filename);
$statement->execute();
$statement->closeCursor();

// --- 7. Set session variables after successful registration ---
$_SESSION["isLoggedIn"] = 1; // logged in
$_SESSION["userName"] = $user_name;

// --- 8. Send registration confirmation email ---
$to_address = $email_address;
$to_name = $user_name;
$from_address = 'YOUR_EMAIL@gmail.com';  // Replace with your email
$from_name = 'Book Manager 2025';
$subject = 'Book Manager 2025 - Registration Complete';
$body = '<p>Thanks for registering with our site.</p>' .
    '<p>Sincerely,</p>' .
    '<p>Book Manager 2025</p>';
$is_body_html = true;

try {
    send_email($to_address, $to_name, $from_address, $from_name, $subject, $body, $is_body_html);
} catch (Exception $ex) {
    $_SESSION["add_error"] = $ex->getMessage();
    header("Location: error.php");
    exit;
}

// --- 9. Redirect to confirmation page ---
header("Location: register_confirmation.php");
exit;
?>
