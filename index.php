<?php
    session_start();
    require("database.php");
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();
    $statement1->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="txt/css" href="css/main.css" />
    <title>PHP Assignment 1 - Home</title>
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h1>Book List</h1>
        <table>
    <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>ISBN</th>
        <th>Price</th>
        <th>Published Date</th>
    </tr>

    <?php foreach ($books as $book): ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['genre']; ?></td>
            <td><?php echo $book['isbn']; ?></td>
            <td><?php echo $book['price']; ?></td>
            <td><?php echo $book['published_Date']; ?></td>

        </tr>
    <?php endforeach; ?>
</table>


    </main>

    <?php include("footer.php"); ?>
</body>

</html>