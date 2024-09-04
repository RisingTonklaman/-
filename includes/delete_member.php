<?php
include 'includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM member WHERE random_id = ?");
        $stmt->execute([$id]);
        header("Location: members.php");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting member: " . $e->getMessage();
    }
}
?>
