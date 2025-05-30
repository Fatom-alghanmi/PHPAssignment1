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

            <form action="add_book.php" method="post" id="add_book_form"
                enctype="multipart/form-data">

                <div id="data">

                    <label>Author:</label>
                    <input type="text" name="author" /><br />

                    <label>Genre:</label>
                    <input type="text" name="genre" /><br />

                    <label>Isbn:</label>
                    <input type="text" name="isbn" /><br />

                    <label>Price:</label>
                    <input type="text" name="price" /><br />

                    <label>Published_Date:</label>
                    <input type="date" name="published_date" /><br />
                    <label>Title:</label>
                    <input type="text" name="title" /><br />

                    <label>Upload Image:</label>
                    <input type="file" name="file1" /><br />

                </div>

                <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Save Book" /><br />

            

                </div>

            </form>

            <p><a href="index.php">View Book List</a></p>
            
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>