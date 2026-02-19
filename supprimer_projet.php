<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit; }
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Optional: delete image file too
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM projets WHERE id=$id"));
    if ($row && $row['image'] && file_exists("fichiers/" . $row['image'])) {
        @unlink("fichiers/" . $row['image']);
    }
    mysqli_query($conn, "DELETE FROM projets WHERE id=$id");
}

header("Location: projets.php");
exit;
?>
