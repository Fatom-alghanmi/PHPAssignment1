<?php
session_start();

$success_message = $_SESSION['upload_success'] ?? null;
unset($_SESSION['upload_success']); // Remove it so it doesn't show again


if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}

require("database.php");

// Get profile image
$userName = $_SESSION["userName"];
$query = 'SELECT profile_image FROM registrations WHERE userName = :userName';
$stmt = $db->prepare($query);
$stmt->bindValue(':userName', $userName);
$stmt->execute();
$user = $stmt->fetch();
$stmt->closeCursor();

$profile_image = isset($user["profile_image"]) && !empty($user["profile_image"])
    ? 'images/' . htmlspecialchars($user["profile_image"])
    : 'images/default.png';

// JOIN books with types
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
    
<?php if ($success_message): ?>
  <div id="success-msg" class="success-message"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>

<!-- NAV BAR -->
<!-- NAV BAR -->
<nav>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="nav-profile">
    <img src="<?= $profile_image ?>" alt="Profile Image" class="profile-image" />
  </div>
</nav>


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
            <th>image</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>

        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']); ?></td>
                <td><?= htmlspecialchars($book['author']); ?></td>
                <td><?= htmlspecialchars($book['genre']); ?></td>
                <td><?= htmlspecialchars($book['isbn']); ?></td>
                <td><?= htmlspecialchars($book['price']); ?></td>
                <td>
                    <?php 
                        $date = $book['published_Date'];
                        echo $date ? (new DateTime($date))->format('Y-m-d') : '';
                    ?>
                </td>
                <td><?= htmlspecialchars($book['bookType']); ?></td>
                <td>
                    <img src="<?= 'images/' . htmlspecialchars($book['imageName']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" />
                </td>
                <td>
                    <form action="update_book_form.php" method="post">
                        <input type="hidden" name="book_id" value="<?= $book['bookID']; ?>" />
                        <input type="submit" value="Update" class="button" />
                    </form>
                </td>
                <td>
                    <form action="delete_book.php" method="post">
                        <input type="hidden" name="book_id" value="<?= $book['bookID']; ?>" />
                        <input type="submit" value="Delete" class="button" />
                    </form>
                </td>
                <td>
                    <form action="book_details.php" method="post">
                        <input type="hidden" name="book_id" value="<?= $book['bookID']; ?>" />
                        <input type="submit" value="View Details" class="button" />
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="add_book_form.php" class="back-link">Add Book</a></p>
    <p><a href="logout.php" class="back-link">Logout</a></p>

</main>

<script>
  // Hide success message after 3 seconds
  window.addEventListener('DOMContentLoaded', () => {
    const successMsg = document.getElementById('success-msg');
    if (successMsg) {
      setTimeout(() => {
        successMsg.style.display = 'none';
      }, 3000);
    }
  });
</script>

</main>

<?php include("footer.php"); ?>
</body>
</html>
