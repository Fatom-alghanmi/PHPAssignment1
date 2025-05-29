<?php
    require_once('database.php');
    // get the data from the form
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    // select the contact from the database
    $query = 'SELECT * FROM books WHERE bookID = :book_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':book_id', $book_id);        

        $statement->execute();
        $book = $statement->fetch();
        $statement->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>BookList Manager - Update Book</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Update Book</h2>

            <form action="update_book.php" method="post" id="update_book_form"
                enctype="multipart/form-data">

                <div id="data">

                    <input type="hidden" name="book_id"
                        value="<?php echo $book['bookID']; ?>" />

                    <label>Title:</label>
                    <input type="text" name="title"
                        value="<?php echo $book['title']; ?>" /><br />

                    <label>Author:</label>
                    <input type="text" name="author"
                        value="<?php echo $book['author']; ?>" /><br />

                    <label>Genre:</label>
                    <input type="text" name="genre"
                        value="<?php echo $book['genre']; ?>" /><br />

                    <label>isbn:</label>
                    <input type="text" name="isbn"
                        value="<?php echo $book['isbn']; ?>" /><br />

                    <label>Price:</label>
                    <input type="text" name="price"
                        value="<?php echo $book['price']; ?>" /><br />

                    <label>Published Date:</label>
                    <input type="date" name="published_date"
                        value="<?php echo $book['published_date']; ?>" /><br />

                    <label>Upload Image:</label>
                    <input type="file" name="file1" /><br />
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