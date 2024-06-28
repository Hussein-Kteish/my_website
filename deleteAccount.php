<?php
session_start();
session_destroy();

//This page responsible for deleting the account when user request this.
include 'connect.php';

setcookie('username', '', time() - 3600);
// Assuming you want to delete the record of the logged-in user from the "signup" table
if (isset($_SESSION['checkLogin'])) {
    $loggedUser = $_SESSION['checkLogin'];

    if (!$conn) {
        die("Error: " . mysqli_connect_error());
    }

    $sql = "DELETE FROM signup WHERE username='$loggedUser'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error deleting record: " . mysqli_error($conn);
    } else {
        echo "Account deleted successfully";
    }

    // Redirect after logout
    header("refresh:3;url=signup.php");
    exit();
} else {
    // Handle the case where no user is logged in
    header("location: signup.php");
    exit();
}
?>