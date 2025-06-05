<?php
    session_start();
    require_once 'image_util.php';

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    if (isset($_FILES['file1'])) {
        $filename = $_FILES['file1']['name'];
        if (!empty($filename)) {
            $source = $_FILES['file1']['tmp_name'];
            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;
            move_uploaded_file($source, $target);
            process_image($image_dir_path, $filename);
        }
    }

    $title = filter_input(INPUT_POST, 'title');
    $author = filter_input(INPUT_POST, 'author');
    $genre = filter_input(INPUT_POST, 'genre');
    $isbn = filter_input(INPUT_POST, 'isbn');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $published_date = filter_input(INPUT_POST, 'published_date');
    $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);

    $file_name = $_FILES['file1']['name'];

    $i = strrpos($file_name, '.');
    $image_name = substr($file_name, 0, $i);
    $ext = substr($file_name, $i);
    $image_name_100 = $image_name . '_100' . $ext;

    if ($title == null || $author == null || $genre == null || $isbn == null ||
    $price === false || $published_date == null || $type_id === false) {
    $_SESSION["add_error"] = "Invalid book data. Check all fields and try again.";
    header("Location: error.php");
    die();
}

    require_once('database.php');
    $query = 'INSERT INTO books (title, author, genre, isbn, price, published_date, imageName, typeID)
              VALUES (:title, :author, :genre, :isbn, :price, :published_date, :imageName, :typeID)';

    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':author', $author);
    $statement->bindValue(':genre', $genre);
    $statement->bindValue(':isbn', $isbn);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':published_date', $published_date);
    $statement->bindValue(':imageName', $image_name_100);
    $statement->bindValue(':typeID', $type_id);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["book_title"] = $title;
    header("Location: confirmation.php");
    die();
?>
