<?php
// delete.php
include 'config.php';
include 'auth.php';
require_admin(); // Staff typing the URL directly are rejected (403)

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?"); // juste adapt the name of database
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: index.php");
exit;
?>