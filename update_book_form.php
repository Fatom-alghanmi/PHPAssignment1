<?php

session_start();
require_once('database.php');


$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

if (!$book_id) {
    echo "Invalid book ID.";
    exit();
}

// Get current book data
$query = 'SELECT * FROM books WHERE bookID = :book_id';
$statement = $db->prepare($query);
$statement->bindValue(':book_id', $book_id);
$statement->execute();
$book = $statement->fetch();
$statement->closeCursor();

// Get book types
$queryTypes = 'SELECT * FROM types';
$statement2 = $db->prepare($queryTypes);
$statement2->execute();
$types = $statement2->fetchAll();
$statement2->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Book</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include('header.php'); ?>
<main>
    <h2>Update Book</h2>

    <form action="update_book.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="book_id" value="<?= $book['bookID']; ?>">

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>"><br>

        <label>Author:</label>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']); ?>"><br>

        <label>Genre:</label>
        <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']); ?>"><br>

        <label>ISBN:</label>
        <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']); ?>"><br>

        <label>Price:</label>
        <input type="text" name="price" value="<?= htmlspecialchars($book['price']); ?>"><br>

        <?php
    $publishedDate = '';
    if (!empty($book['published_date'])) {
        $dateObj = new DateTime($book['published_date']);
        $publishedDate = $dateObj->format('Y-m-d');
    }
?>
<label>Published Date:</label>
<input type="date" name="published_date" value="<?= htmlspecialchars($publishedDate); ?>"><br>

        <label>Book Type:</label>
        <select name="type_id">
            <?php foreach ($types as $type): ?>
                <option value="<?= $type['typeID']; ?>" <?= $type['typeID'] == $book['typeID'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($type['bookType']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <?php if (!empty($book['imageName'])): ?>
            <label>Current Image:</label>
            <img src="images/<?= htmlspecialchars($book['imageName']); ?>" height="100"><br>
        <?php endif; ?>

        <label>New Image (optional):</label>
        <input type="file" name="image"><br><br>

        <input type="submit" value="Update Book" class="button">
    </form>

    <p><a href="index.php">Return to Book List</a></p>
</main>
<?php include('footer.php'); ?>
</body>
</html>
<?php