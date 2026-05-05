<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fichier FROM certificats WHERE id=$id"));
    if ($row && $row['fichier'] && file_exists("fichiers/" . $row['fichier'])) {
        @unlink("fichiers/" . $row['fichier']);
    }
    mysqli_query($conn, "DELETE FROM certificats WHERE id=$id");
}

header("Location: certificats.php");
exit;
?>
