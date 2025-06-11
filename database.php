<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dsn = 'mysql:host=localhost;dbname=booklist_manager';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION["database_error"] = $e->getMessage();
    header("Location: database_error.php");
    exit();
}
?>
