<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle adding a new booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['member_name']) && isset($_POST['class_name'])) {
        list($member_first_name, $member_last_name) = explode(' ', $_POST['member_name'], 2);
        $class_name = $_POST['class_name'];

        if (insertBooking($pdo, $member_first_name, $member_last_name, $class_name)) {
            echo "Booking successfully added.";
        } else {
            echo "Failed to add booking.";
        }
    } else {
        echo "Member name or class name missing.";
    }
}

// Handle delete request
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $random_id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM booking WHERE random_id = ?");
    if ($stmt->execute([$random_id])) {
        header("Location: bookings.php"); // Redirect to avoid resubmission
        exit();
    } else {
        echo "Failed to delete booking.";
    }
}

// Fetch all bookings, members, and classes
$bookings = getAllBookings($pdo);
$members = getAllMembers($pdo);
$classes = getAllClasses($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
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
    <h1>Manage Bookings</h1>
    <form action="bookings.php" method="post">
        <select name="member_name" required>
            <option value="">Select Member</option>
            <?php foreach ($members as $member): ?>
                <option value="<?php echo htmlspecialchars($member['first_name']) . ' ' . htmlspecialchars($member['last_name']); ?>">
                    <?php echo htmlspecialchars($member['first_name']) . ' ' . htmlspecialchars($member['last_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="class_name" required>
            <option value="">Select Class</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo htmlspecialchars($class['name']); ?>">
                    <?php echo htmlspecialchars($class['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Add Booking</button>
    </form>

    <h2>Bookings List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Member Name</th>
                <th>Class Name</th>
                <th>Actions</th> <!-- New column for actions -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['random_id']); ?></td>
                    <td><?php echo htmlspecialchars($booking['member_first_name']) . ' ' . htmlspecialchars($booking['member_last_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['class_name']); ?></td>
                    <td>
                        <a href="bookings.php?action=delete&id=<?php echo htmlspecialchars($booking['random_id']); ?>" 
                           class="delete-button"
                           onclick="return confirm('Are you sure you want to delete this booking?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php">Back to Home</a>
</body>
</html>
