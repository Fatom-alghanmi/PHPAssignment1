<?php
session_start();

require_once 'image_util.php';
$image_dir = 'images';
$image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

// Handle image upload
$image_name = '';
if (isset($_FILES['file1'])) {
    $filename = $_FILES['file1']['name'];
    if (!empty($filename)) {
        $source = $_FILES['file1']['tmp_name'];
        $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;
        move_uploaded_file($source, $target);
        process_image($image_dir_path, $filename);
        $image_name = $filename;
    }
}

// Get data from form
$author = filter_input(INPUT_POST, 'author');
$genre = filter_input(INPUT_POST, 'genre');
$isbn = filter_input(INPUT_POST, 'isbn');
$price = filter_input(INPUT_POST, 'price');
$published_date = filter_input(INPUT_POST, 'published_date');
$title = filter_input(INPUT_POST, 'title');
$image_name = $_FILES['file1']['name'];

// Validate empty fields
if ($author == null || $genre == null || $isbn == null || $price == null || $published_date == null || $title == null) {
    $_SESSION["add_error"] = "Invalid book data. Please fill out all fields.";
    header("Location: error.php");
    exit();
}

require_once('database.php');
// Check duplicates
$queryBooks = 'SELECT * FROM books';
$statement1 = $db->prepare($queryBooks);
$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($books as $book) {
    if ($title == $book["title"] || $isbn == $book["isbn"]) {
        $_SESSION["add_error"] = "Duplicate Book Title or ISBN. Try again.";
        header("Location: error.php");
        exit();
    }
}

// No duplicates and all fields valid, proceed to insert
$query = 'INSERT INTO books
    (author, genre, isbn, price, published_Date, title, imageName)
    VALUES
    (:author, :genre, :isbn, :price, :published_Date, :title, :imageName)';

$statement = $db->prepare($query);
$statement->bindValue(':author', $author);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':isbn', $isbn);
$statement->bindValue(':price', $price);
$statement->bindValue(':published_Date', $published_date);
$statement->bindValue(':title', $title);
$statement->bindValue(':imageName', $image_name);

$statement->execute();
$statement->closeCursor();

$_SESSION["bookTitle"] = $title;

// redirect to confirmation page
header("Location: confirmation.php");
exit();
?>
