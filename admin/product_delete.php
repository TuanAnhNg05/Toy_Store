<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $res = $conn->query("SELECT image FROM products WHERE id=$id");
    if ($row = $res->fetch_assoc()) {
        $path = "../assets/images/" . $row['image'];
        if (file_exists($path) && $row['image'] != 'no-image.png') {
            unlink($path);
        }
    }

    $conn->query("DELETE FROM products WHERE id=$id");
}

header("Location: index.php?page=products");
exit();
?>