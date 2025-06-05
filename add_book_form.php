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
        <form action="add_book.php" method="post" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title"><br>

            <label>Author:</label>
            <input type="text" name="author"><br>

            <label>Genre:</label>
            <input type="text" name="genre"><br>

            <label>ISBN:</label>
            <input type="text" name="isbn"><br>

            <label>Price:</label>
            <input type="text" name="price"><br>

            <label>Published Date:</label>
            <input type="date" name="published_date"><br>

            <label>Book Type:</label>
                    <select name="type_id">
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['typeID']; ?>">
                                <?php echo $type['bookType']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br />

            <label>Upload Cover Image:</label>
            <input type="file" name="file1"><br>

            <input type="submit" value="Add Book">
        </form>
        <p><a href="index.php">View Book List</a></p>
    </main>
    <?php include("footer.php"); ?>
</body>
</html>
