<?php
session_start();

$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

// get data from the form
$title = filter_input(INPUT_POST, 'title');
$author = filter_input(INPUT_POST, 'author');
$genre = filter_input(INPUT_POST, 'genre');
$isbn = filter_input(INPUT_POST, 'isbn');
$price = filter_input(INPUT_POST, 'price');
$published_date = filter_input(INPUT_POST, 'published_date');
$image_name = $_FILES['file1']['name'];
if (empty($image_name)) {
    $image_name = filter_input(INPUT_POST, 'existing_image');
}

require_once('database.php');

// Check for duplicates
$queryBooks = 'SELECT * FROM books';
$statement1 = $db->prepare($queryBooks);
$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($books as $book) {
    if (
        ($title === $book["title"] || $isbn === $book["isbn"]) &&
        ($book_id != $book["bookID"])
    ) {
        $_SESSION["add_error"] = "Invalid data, Duplicate Book Title or ISBN. Try again.";
        header("Location: error.php");
        die();
    }
}

// Check for missing fields
if ($title == null || $author == null || $genre == null || $isbn == null || $price == null || $published_date == null) {
    $_SESSION["add_error"] = "Invalid book data, Check all fields and try again.";
    header("Location: error.php");
    die();
}

// Save new image if uploaded
if ($_FILES['file1']['error'] == UPLOAD_ERR_OK) {
    $source = $_FILES['file1']['tmp_name'];
    $target = 'images/' . $image_name;
    move_uploaded_file($source, $target);
    require_once('image_util.php');
    process_image('images', $image_name);
}

// Update book
$query = 'UPDATE books
    SET title = :title,
        author = :author,
        genre = :genre,
        isbn = :isbn,
        price = :price,
        published_Date = :published_date,
        imageName = :imageName
    WHERE bookID = :bookID';

$statement = $db->prepare($query);
$statement->bindValue(':bookID', $book_id);
$statement->bindValue(':title', $title);
$statement->bindValue(':author', $author);
$statement->bindValue(':genre', $genre);
$statement->bindValue(':isbn', $isbn);
$statement->bindValue(':price', $price);
$statement->bindValue(':published_date', $published_date);
$statement->bindValue(':imageName', $image_name);
$statement->execute();
$statement->closeCursor();

$_SESSION["bookTitle"] = $title;

header("Location: update_confirmation.php");
die();
?>
