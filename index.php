<?php
    session_start();

    if (!isset($_SESSION["isLoggedIn"])) {
        header("Location: login_form.php");
        die();
    }

    require("database.php");

    // JOIN books with types to get the book type name
    $queryBooks = '
        SELECT c.*, t.bookType
        FROM books c
        LEFT JOIN types t ON c.typeID = t.typeID
    ';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();
    $statement1->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Manager - Home</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Book List</h2>

            <table>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>ISBN</th>
                    <th>Price</th>
                    <th>Published Date</th>
                    <th>Book Type</th>
                    <th>Image</th>
                    <th>&nbsp;</th> <!-- for update -->
                    <th>&nbsp;</th> <!-- for delete -->
                    <th>&nbsp;</th> <!-- for view details -->
                </tr>

                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['genre']); ?></td>
                        <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                        <td><?php echo htmlspecialchars($book['price']); ?></td>
                        <td><?php echo htmlspecialchars($book['published_Date']); ?></td>
                        <td><?php echo htmlspecialchars($book['bookType']); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" 
                                 alt="<?php echo htmlspecialchars($book['title']); ?>" />
                        </td>
                        <td>
                            <form action="update_book_form.php" method="post">
                                <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Update" />
                            </form>
                        </td>
                        <td>
                            <form action="delete_book.php" method="post">
                                <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Delete" />
                            </form>
                        </td>
                        <td>
                            <form action="book_details.php" method="post">
                                <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="View Details" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p><a href="add_book_form.php">Add Book</a></p>
            <p><a href="logout.php">Logout</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>
