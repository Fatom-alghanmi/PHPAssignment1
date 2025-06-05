<?php
require_once('database.php');

// Get book ID from POST
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

// Get the book's current data
$query = 'SELECT * FROM books WHERE bookID = :book_id';
$statement = $db->prepare($query);
$statement->bindValue(':book_id', $book_id);
$statement->execute();
$book = $statement->fetch();
$statement->closeCursor();

// Get all book types from the shared "types" table
$queryTypes = 'SELECT * FROM types';
$statement2 = $db->prepare($queryTypes);
$statement2->execute();
$types = $statement2->fetchAll();
$statement2->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booklist Manager - Update Book</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Update Book</h2>

        <form action="update_book.php" method="post" id="update_book_form" enctype="multipart/form-data">
            <div id="data">
                <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />

                <label>Title:</label>
                <input type="text" name="title" value="<?php echo $book['title']; ?>" /><br />

                <label>Author:</label>
                <input type="text" name="author" value="<?php echo $book['author']; ?>" /><br />

                <label>Genre:</label>
                <input type="text" name="genre" value="<?php echo $book['genre']; ?>" /><br />

                <label>ISBN:</label>
                <input type="text" name="isbn" value="<?php echo $book['isbn']; ?>" /><br />

                <label>Price:</label>
                <input type="text" name="price" value="<?php echo $book['price']; ?>" /><br />

                <label>Published Date:</label>
                <input type="date" name="published_date" value="<?php echo $book['published_date']; ?>" /><br />

                <label>Book Type:</label>
                <select name="type_id">
                    <?php foreach ($types as $type): ?>
                        <option value="<?php echo $type['typeID']; ?>" <?php if ($type['typeID'] == $book['typeID']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($type['bookType']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br />

                <?php if (!empty($book['imageName'])): ?>
                <label>Current Image:</label>
                <img src="images/<?php echo htmlspecialchars($book['imageName']); ?>" height="100"><br />
            <?php endif; ?>

            <label>Update Image:</label>
            <input type="file" name="image"><br />
            </div>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Update Book" /><br />
            </div>
        </form>

        <p><a href="index.php">View Book List</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
