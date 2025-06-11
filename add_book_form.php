<?php
require_once('database.php');
$queryTypes = 'SELECT * FROM types';
$statement = $db->prepare($queryTypes);
$statement->execute();
$types = $statement->fetchAll();
$statement->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Manager - Add Book</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Add Book</h2>

        <form action="add_book.php" method="post" enctype="multipart/form-data" id="add_book_form">
            <div id="data">
                <label>Title:</label>
                <input type="text" name="title" required><br />

                <label>Author:</label>
                <input type="text" name="author" required><br />

                <label>Genre:</label>
                <input type="text" name="genre" required><br />

                <label>ISBN:</label>
                <input type="text" name="isbn" required><br />

                <label>Price:</label>
                <input type="number" name="price" step="0.01" required><br />

                <label>Published Date:</label>
                <input type="date" name="published_date" required><br />

                <label>Book Type:</label>
                <select name="type_id" required>
                    <option value="">-- Select Type --</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['typeID']; ?>">
                            <?= htmlspecialchars($type['bookType']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br />

                <label>Upload Cover Image:</label>
                <input type="file" name="image"><br />
            </div>

            <div id="buttons">
                <label>&nbsp;</label>
                <input type="submit" value="Add Book" class="button"><br />
            </div>
        </form>

        <p><a href="index.php">View Book List</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
