<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteClass($pdo, $id);
    header("Location: classes.php"); // Redirect to avoid resubmission
    exit();
}

// Handle adding a new class
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    insertClass($pdo, $_POST['name']);
}

// Fetch all classes
$classes = getAllClasses($pdo);

function deleteClass($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM classes WHERE random_id = ?");
    $stmt->execute([$id]);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .delete-button {
            background-color: #f44336; /* Red */
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .delete-button:hover {
            background-color: #d32f2f; /* Darker red */
        }
    </style>
</head>
<body>
    <h1>Manage Classes</h1>
    <form action="classes.php" method="post">
        <input type="text" name="name" placeholder="Class Name" required>
        <button type="submit">Add Class</button>
    </form>

    <h2>Classes List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Class Name</th>
                <th>Actions</th> <!-- Added Actions column -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($classes as $class): ?>
                <tr>
                    <td><?php echo htmlspecialchars($class['random_id']); ?></td>
                    <td><?php echo htmlspecialchars($class['name']); ?></td>
                    <td>
                        <a href="classes.php?action=delete&id=<?php echo htmlspecialchars($class['random_id']); ?>" 
                           class="delete-button"
                           onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php">Back to Home</a>
</body>
</html>
