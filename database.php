<?php
    session_start();
    $dsn = 'mysql:host=localhost;dbname=booklist_manager'; // <-- updated database name
    $username = 'root'; // use your DB username
    $password = '';     // use your DB password

    try {
        $db = new PDO($dsn, $username, $password);
    }
    catch (PDOException $e)
    {
        $_SESSION["database_error"] = $e->getMessage();
        $url = "database_error.php";
        header("Location: " . $url);
        exit();
    }
?>
