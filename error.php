<?php
session_start();

$error_message = $_SESSION['add_error'] ?? $_SESSION['update_error'] ?? "Unknown error occurred.";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booklist Manager - Error</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<header>
    <h1>Book Manager System</h1>
</header>
<main>
    <h2>Error</h2>
    <p><?= htmlspecialchars($error_message); ?></p>

    <p><a href="add_book_form.php">Add Book</a></p>
    <p><a href="index.php">View Book List</a></p>
</main>
<footer>
    <p>© 2025 Book Manager System. All rights reserved.</p>
</footer>
</body>
</html>
