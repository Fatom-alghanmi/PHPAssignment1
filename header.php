
<header>
    <h1>Book Manager System</h1>

    <?php
session_start();
require_once('database.php');

if (!isset($_SESSION['userName'])) {
    // user not logged in, maybe redirect or show login link
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['userName'];

$query = 'SELECT profile_image FROM registrations WHERE userName = :userName';
$stmt = $db->prepare($query);
$stmt->bindValue(':userName', $userName);
$stmt->execute();
$user = $stmt->fetch();

$profile_image = $user && !empty($user['profile_image']) 
    ? 'images/' . htmlspecialchars($user['profile_image']) 
    : 'images/';
?>


</header>
