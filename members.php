<?php
include 'includes/db.php';
include 'includes/functions.php';

// Handle inline edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['random_id']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    updateMember($pdo, $_POST['random_id'], $_POST['first_name'], $_POST['last_name']);
}

$members = getAllMembers($pdo);
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
            // Show input fields and hide static text
            var cells = row.getElementsByTagName('td');
            for (var i = 1; i < cells.length - 1; i++) {
                var cell = cells[i];
                var text = cell.textContent;
                cell.innerHTML = '<input type="text" value="' + text + '" />';
            }

            // Change Edit button to Save button
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
                  // Reload page to reflect changes
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
                        <a href="delete_member.php?id=<?php echo htmlspecialchars($member['random_id']); ?>" onclick="return confirm('Are you sure you want to delete this member?');" class="button delete-button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php">Back to Home</a>
</body>
</html>
