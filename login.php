<?php
session_start();
require_once('database.php');

$user_name = trim(filter_input(INPUT_POST, 'user_name'));
$password = trim(filter_input(INPUT_POST, 'password'));

if (empty($user_name) || empty($password)) {
    $_SESSION['login_error'] = 'Username and password are required.';
    header("Location: login_form.php");
    exit;
}

// Step 1: Get user row
$query = 'SELECT password, failed_attempts, last_failed_login FROM registrations WHERE userName = :userName';
$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if ($user) {
    $failed_attempts = (int) $user['failed_attempts'];
    $last_failed_login = $user['last_failed_login'] ? strtotime($user['last_failed_login']) : 0;
    $current_time = time();

    // Step 2: Check lockout
    if ($failed_attempts >= 3 && ($current_time - $last_failed_login < 300)) {
        $remaining = 300 - ($current_time - $last_failed_login);
        $_SESSION['login_error'] = 'Too many failed attempts. Try again in ' . ceil($remaining / 60) . ' minute(s).';
        $_SESSION['lockout_remaining'] = $remaining;
        header("Location: login_form.php");
        exit;
    }

    // Step 3: Password check
    if (password_verify($password, $user['password'])) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userName'] = $user_name;

        $resetQuery = 'UPDATE registrations SET failed_attempts = 0, last_failed_login = NULL WHERE userName = :userName';
        $resetStmt = $db->prepare($resetQuery);
        $resetStmt->bindValue(':userName', $user_name);
        $resetStmt->execute();
        $resetStmt->closeCursor();

        header("Location: login_confirmation.php");
        exit;
    } else {
        $failed_attempts++;

        $updateQuery = 'UPDATE registrations 
                        SET failed_attempts = :attempts, last_failed_login = NOW()
                        WHERE userName = :userName';
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindValue(':attempts', $failed_attempts, PDO::PARAM_INT);
        $updateStmt->bindValue(':userName', $user_name);
        $updateStmt->execute();
        $updateStmt->closeCursor();

        if ($failed_attempts >= 3) {
            $_SESSION['login_error'] = 'Too many failed attempts. Try again in 5 minute(s).';
            $_SESSION['lockout_remaining'] = 300;
        } else {
            $_SESSION['login_error'] = "Invalid username or password. Attempt $failed_attempts of 3.";
        }

        $_SESSION['isLoggedIn'] = false;
        header("Location: login_form.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = 'Invalid username or password.';
    header("Location: login_form.php");
    exit;
}