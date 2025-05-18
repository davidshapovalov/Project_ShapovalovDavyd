<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$dbname = "concert";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete_contact'])) {
        $id = (int)$_POST['delete_contact'];
        $conn->query("DELETE FROM contacts WHERE id=$id");
    } elseif (isset($_POST['delete_ticket'])) {
        $id = (int)$_POST['delete_ticket'];
        $conn->query("DELETE FROM tickets WHERE id=$id");
    } elseif (isset($_POST['edit_contact_id'])) {
        $id = (int)$_POST['edit_contact_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $company = $conn->real_escape_string($_POST['company']);
        $message = $conn->real_escape_string($_POST['message']);

        $conn->query("UPDATE contacts SET name='$name', email='$email', company='$company', message='$message' WHERE id=$id");
    } elseif (isset($_POST['edit_ticket_id'])) {
        $id = (int)$_POST['edit_ticket_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $ticket_type = $conn->real_escape_string($_POST['ticket_type']);
        $ticket_count = (int)$_POST['ticket_count'];
        $message = $conn->real_escape_string($_POST['message']);

        $conn->query("UPDATE tickets SET name='$name', email='$email', phone='$phone', ticket_type='$ticket_type', ticket_count=$ticket_count, message='$message' WHERE id=$id");
    }
}

$contacts = $conn->query("SELECT * FROM contacts ORDER BY id DESC");
$tickets = $conn->query("SELECT * FROM tickets ORDER BY id DESC");

$edit_contact_id = $_GET['edit_contact'] ?? null;
$edit_ticket_id = $_GET['edit_ticket'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Requests</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 40px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f0f0f0; }
        h2 { margin-top: 40px; }
        form.inline { display: inline; }
        input[type="text"], textarea { width: 100%; }
    </style>
</head>
<body>

<h1>Admin Panel: Manage Requests</h1>
<p><a href="logout.php">Logout</a></p>

<h2>Contact Forms</h2>
<table>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Company</th><th>Message</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $contacts->fetch_assoc()): ?>
        <?php if ($edit_contact_id == $row['id']): ?>
            <tr>
                <form method="post">
                    <td><?= $row['id'] ?><input type="hidden" name="edit_contact_id" value="<?= $row['id'] ?>"></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><input type="text" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                    <td><input type="text" name="company" value="<?= htmlspecialchars($row['company']) ?>"></td>
                    <td><textarea name="message"><?= htmlspecialchars($row['message']) ?></textarea></td>
                    <td><button type="submit">Save</button></td>
                </form>
            </tr>
        <?php else: ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['company']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td>
                    <form class="inline" method="get">
                        <input type="hidden" name="edit_contact" value="<?= $row['id'] ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <form class="inline" method="post">
                        <input type="hidden" name="delete_contact" value="<?= $row['id'] ?>">
                        <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endif; ?>
    <?php endwhile; ?>
    </tbody>
</table>

<h2>Ticket Orders</h2>
<table>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Quantity</th><th>Message</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $tickets->fetch_assoc()): ?>
        <?php if ($edit_ticket_id == $row['id']): ?>
            <tr>
                <form method="post">
                    <td><?= $row['id'] ?><input type="hidden" name="edit_ticket_id" value="<?= $row['id'] ?>"></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                    <td><input type="text" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                    <td><input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>"></td>
                    <td><input type="text" name="ticket_type" value="<?= htmlspecialchars($row['ticket_type']) ?>"></td>
                    <td><input type="text" name="ticket_count" value="<?= htmlspecialchars($row['ticket_count']) ?>"></td>
                    <td><textarea name="message"><?= htmlspecialchars($row['message']) ?></textarea></td>
                    <td><button type="submit">Save</button></td>
                </form>
            </tr>
        <?php else: ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['ticket_type']) ?></td>
                <td><?= htmlspecialchars($row['ticket_count']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td>
                    <form class="inline" method="get">
                        <input type="hidden" name="edit_ticket" value="<?= $row['id'] ?>">
                        <button type="submit">Edit</button>
                    </form>
                    <form class="inline" method="post">
                        <input type="hidden" name="delete_ticket" value="<?= $row['id'] ?>">
                        <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endif; ?>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>

<?php $conn->close(); ?>
