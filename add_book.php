<?php
session_start();


$base_dir = 'images/';

$title = filter_input(INPUT_POST, 'title');
$author = filter_input(INPUT_POST, 'author');
$genre = filter_input(INPUT_POST, 'genre');
$isbn = filter_input(INPUT_POST, 'isbn');
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$published_date = filter_input(INPUT_POST, 'published_date');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['file1'];

require_once('database.php');
require_once('image_util.php');

$base_dir = 'images/';

// Check for duplicate ISBN
$queryBooks = 'SELECT * FROM books';
$statement1 = $db->prepare($queryBooks);
$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($books as $book) {
    if ($isbn === $book["isbn"]) {
        $_SESSION["add_error"] = "Invalid data. Duplicate ISBN. Try again.";
        header("Location: error.php");
        die();
    }
}

// Validate fields
if ($title === null || $author === null || $genre === null || $isbn === null ||
    $price === false || $published_date === null || $type_id === null) {
    $_SESSION["add_error"] = "Invalid book data. Check all fields and try again.";
    header("Location: error.php");
    die();
}

// Image processing
$image_name = '';  // default empty

if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    $dot_pos = strrpos($original_filename, '.');
    $name_100 = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
    $image_name = $name_100;
} else {
    // Use placeholder
    $placeholder = 'placeholder.jpg';
    $placeholder_100 = 'placeholder_100.jpg';
    $placeholder_400 = 'placeholder_400.jpg';

    if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
        process_image($base_dir, $placeholder);
    }

    $image_name = $placeholder_100.;
}

// Add book
$query = 'INSERT INTO books
    (title, author, genre, isbn, price, published_date, imageName, typeID)
    VALUES
    (:title, :author, :genre, :isbn, :price, :published_date, :imageName, :typeID)';

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':author', $author);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':isbn', $isbn);
$statement->bindValue(':price', $price);
$statement->bindValue(':published_date', $published_date);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':typeID', $type_id);
$statement->execute();
$statement->closeCursor();

$_SESSION["book_title"] = $title;
header("Location: confirmation.php");
die();
?>
