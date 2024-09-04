<?php
require 'functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdo = connectDB();
    $stmt = $pdo->prepare("DELETE FROM member WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: members.php");
    exit;
} else {
    echo "Invalid member ID!";
}
?>
