<?php
session_start();
require("database.php");

if (!isset($_SESSION["isLoggedIn"]) || !isset($_SESSION["userName"])) {
    header("Location: login_form.php");
    exit();
}

$userName = $_SESSION["userName"];

if (isset($_FILES["new_profile_image"]) && $_FILES["new_profile_image"]["error"] === UPLOAD_ERR_OK) {
    $imageTmpPath = $_FILES["new_profile_image"]["tmp_name"];
    $imageName = basename($_FILES["new_profile_image"]["name"]);
    $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageExt, $allowedExts)) {
        $_SESSION["upload_error"] = "Only JPG, PNG, or GIF files are allowed.";
        header("Location: index.php");
        exit();
    }

    // Generate a unique name
    $newFileName = uniqid("profile_", true) . "." . $imageExt;
    $uploadPath = "images/" . $newFileName;

    if (move_uploaded_file($imageTmpPath, $uploadPath)) {
        // Update DB
        $query = "UPDATE registrations SET profile_image = :profile_image WHERE userName = :userName";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":profile_image", $newFileName);
        $stmt->bindValue(":userName", $userName);
        $stmt->execute();

        $_SESSION["upload_success"] = "Profile image updated.";
    } else {
        $_SESSION["upload_error"] = "Error moving uploaded file.";
    }
} else {
    $_SESSION["upload_error"] = "No file uploaded or upload error.";
}

header("Location: index.php");
exit();
