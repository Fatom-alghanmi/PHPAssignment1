
<?php
    require_once('database.php');
    // get the data from the form
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    // code to delete contact from database
    // validate inputs
    if ($book_id != false)
    {
        // delete the contact from the database
        $query = 'DELETE FROM books WHERE bookID = :book_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':book_id', $book_id);        

        $statement->execute();
        $statement->closeCursor();
    }

    // reload index page
    $url = "index.php";
    header("Location: " . $url);
    die();
?>
