<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    deleteMember($pdo, $id);
    header("Location: members.php"); // Redirect to avoid resubmission
    exit();
}

// Handle inline edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['random_id']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    updateMember($pdo, $_POST['random_id'], $_POST['first_name'], $_POST['last_name']);
}

// Handle member addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    insertMember($pdo, $_POST['first_name'], $_POST['last_name']);
}

// Fetch all members
$members = getAllMembers($pdo);

function deleteMember($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM member WHERE random_id = ?");
    $stmt->execute([$id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function enableEdit(row) {
            var cells = row.getElementsByTagName('td');
            for (var i = 1; i < cells.length - 1; i++) {
                var cell = cells[i];
                var text = cell.textContent;
                cell.innerHTML = '<input type="text" value="' + text + '" />';
            }

            var actionsCell = cells[cells.length - 1];
            actionsCell.innerHTML = '<button onclick="saveChanges(this)">Save</button> <button onclick="cancelEdit(this)">Cancel</button>';
        }

        function saveChanges(button) {
            var row = button.closest('tr');
            var cells = row.getElementsByTagName('td');

            var random_id = row.getAttribute('data-id');
            var first_name = cells[1].getElementsByTagName('input')[0].value;
            var last_name = cells[2].getElementsByTagName('input')[0].value;

            var formData = new FormData();
            formData.append('random_id', random_id);
            formData.append('first_name', first_name);
            formData.append('last_name', last_name);

            fetch('members.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
              .then(data => {
                  window.location.reload();
              });
        }

        function cancelEdit(button) {
            var row = button.closest('tr');
            var cells = row.getElementsByTagName('td');
            for (var i = 1; i < cells.length - 1; i++) {
                var cell = cells[i];
                var input = cell.getElementsByTagName('input')[0];
                cell.innerHTML = input.value;
            }
            var actionsCell = cells[cells.length - 1];
            actionsCell.innerHTML = '<a href="#" onclick="enableEdit(this.closest(\'tr\'))" class="button edit-button">Edit</a>';
        }
    </script>
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
<body class="center">
    <h1>Manage Members</h1>
    <form action="members.php" method="post">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <button type="submit">Add Member</button>
    </form>

    <h2>Members List</h2>
    <table class="center">
        <thead>
            <tr>
                <th class="th-id">ID</th>
                <th class="th-first-name">First Name</th>
                <th class="th-last-name">Last Name</th>
                <th class="th-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
                <tr data-id="<?php echo htmlspecialchars($member['random_id']); ?>">
                    <td><?php echo htmlspecialchars($member['random_id']); ?></td>
                    <td><?php echo htmlspecialchars($member['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($member['last_name']); ?></td>
                    <td class="actions">
                        <a href="#" onclick="enableEdit(this.closest('tr'))" class="button edit-button">Edit</a>
                        <a href="members.php?action=delete&id=<?php echo htmlspecialchars($member['random_id']); ?>" 
                           class="delete-button"
                           onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php">Back to Home</a>
</body>
</html>
