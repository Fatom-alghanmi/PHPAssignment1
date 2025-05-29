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
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
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
        <th>Image</th>
        <th>&nbsp;</th> <!-- for edit button -->
        <th>&nbsp;</th> <!-- for delete button -->

    </tr>

    <?php foreach ($books as $book): ?>
        <tr>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['genre']; ?></td>
            <td><?php echo $book['isbn']; ?></td>
            <td><?php echo $book['price']; ?></td>
            <td><?php echo $book['published_Date']; ?></td>
            
            <td><img src="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>"
                            alt="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" style="width:100px; height:auto;" /></td>
            <td>
                            <form action="update_book_form.php" method="post"> 
                                <input type="hidden" name="book_id"
                                    value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Update" />
                            </form>
                        </td> <!-- for edit button -->
                        <td>
                            <form action="delete_book.php" method="post"> 
                                <input type="hidden" name="book_id"
                                    value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Delete" />
                            </form>
                        </td> <!-- for delete button -->
     </tr>
    <?php endforeach; ?>
        </table>
    </main>
    <p><a href="add_book_form.php" class="add-book-main">Add Book</a></p>

    <?php include("footer.php"); ?>
</body>

</html>