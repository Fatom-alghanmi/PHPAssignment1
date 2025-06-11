<?php
session_start();


require_once('database.php');
require_once('image_util.php');

$title = filter_input(INPUT_POST, 'title');
$author = filter_input(INPUT_POST, 'author');
$genre = filter_input(INPUT_POST, 'genre');
$isbn = filter_input(INPUT_POST, 'isbn');
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
$published_date = filter_input(INPUT_POST, 'published_date');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['image'];

require_once('database.php');
require_once('image_util.php');

$base_dir = 'images/';

// ✅ First: Validate required fields
if (!$title || !$author || !$genre || !$isbn || !$price || !$published_date || !$type_id) {
    $_SESSION["add_error"] = "Invalid book data. Please check all fields and try again.";
    header("Location: error.php");
    exit();
}

// ✅ Then: Check for duplicate ISBN
$queryBooks = 'SELECT * FROM books';
$statement = $db->prepare($queryBooks);
$statement->execute();
$books = $statement->fetchAll();
$statement->closeCursor();

foreach ($books as $book) {
    if ($isbn === $book["isbn"]) {
        $_SESSION["add_error"] = "Duplicate ISBN found. Please use a unique ISBN.";
        header("Location: error.php");
        exit();
    }
}

// ✅ Then: Handle image
$image_name = '';
if ($image && $image['error'] === UPLOAD_ERR_OK) {
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;

    if ($image['error'] !== UPLOAD_ERR_OK) {
        $_SESSION["add_error"] = "Image upload error: " . $image['error'];
        header("Location: error.php");
        exit();
    }
    
    if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
        $_SESSION["add_error"] = "Failed to move uploaded file to $upload_path.";
        header("Location: error.php");
        exit();
    }
    

    process_image($base_dir, $original_filename);

    $dot_pos = strrpos($original_filename, '.');
    $image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
} else {
    // Fallback to placeholder
    $placeholder = 'placeholder.jpg';
    $placeholder_100 = 'placeholder_100.jpg';
    $placeholder_400 = 'placeholder_400.jpg';

    if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
        process_image($base_dir, $placeholder);
    }

    $image_name = $placeholder_100;
}

// ✅ Finally: Insert into DB
$query = 'INSERT INTO books
    (title, author, genre, isbn, price, published_date, typeID, imageName)
    VALUES
    (:title, :author, :genre, :isbn, :price, :published_date, :typeID, :imageName)';

$statement = $db->prepare($query);
$statement->bindValue(':title', $title);
$statement->bindValue(':author', $author);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':isbn', $isbn);
$statement->bindValue(':price', $price);
$statement->bindValue(':published_date', $published_date);
$statement->bindValue(':typeID', $type_id);
$statement->bindValue(':imageName', $image_name);
$statement->execute();
$statement->closeCursor();

$_SESSION["add_success"] = "$title added successfully.";
header("Location: confirmation.php");
exit();
