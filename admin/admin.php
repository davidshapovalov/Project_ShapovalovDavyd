<?php
session_start(); // Запуск сессии // Start session

// Проверка, вошёл ли админ в систему // Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php"); // Если нет — перенаправить на логин // Redirect if not logged in
    exit;
}

// Подключение к базе данных // Connect to database
$conn = new mysqli("localhost", "root", "", "concert"); // Сервер, пользователь, пароль, база // Server, user, password, database
if ($conn->connect_error) {
    die("Connect error: " . $conn->connect_error); // Connection error
}

// Обработка формы // Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Удаление контакта // Delete contact
    if (isset($_POST['delete_contact'])) {
        $id = (int)$_POST['delete_contact'];
        $conn->query("DELETE FROM contacts WHERE id=$id");
    }
    // Удаление билета // Delete ticket
    elseif (isset($_POST['delete_ticket'])) {
        $id = (int)$_POST['delete_ticket'];
        $conn->query("DELETE FROM tickets WHERE id=$id");
    }
    // Редактирование контакта // Edit contact
    elseif (isset($_POST['edit_contact_id'])) {
        $id = (int)$_POST['edit_contact_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $company = $conn->real_escape_string($_POST['company']);
        $message = $conn->real_escape_string($_POST['message']);
        $conn->query("UPDATE contacts SET name='$name', email='$email', company='$company', message='$message' WHERE id=$id");
    }
    // Редактирование билета // Edit ticket
    elseif (isset($_POST['edit_ticket_id'])) {
        $id = (int)$_POST['edit_ticket_id'];
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $type = $conn->real_escape_string($_POST['ticket_type']);
        $count = (int)$_POST['ticket_count'];
        $msg = $conn->real_escape_string($_POST['message']);
        $conn->query("UPDATE tickets SET name='$name', email='$email', phone='$phone', ticket_type='$type', ticket_count=$count, message='$msg' WHERE id=$id");
    }
}

// Получение всех записей // Get all records
$contacts = $conn->query("SELECT * FROM contacts ORDER BY id DESC");
$tickets = $conn->query("SELECT * FROM tickets ORDER BY id DESC");

// Режим редактирования // Edit mode
$edit_contact_id = $_GET['edit_contact'] ?? null;
$edit_ticket_id = $_GET['edit_ticket'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
        input, textarea { width: 100%; }
    </style>
</head>
<body>

<h1>Admin Panel</h1>
<p><a href="logout.php">Logout</a></p>

<!-- Таблица Контактов // Contact Table -->
<h2>Contact Form</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Company</th><th>Message</th><th>Actions</th></tr>
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
            <form method="get" style="display:inline;"><input type="hidden" name="edit_contact" value="<?= $row['id'] ?>"><button>Edit</button></form>
            <form method="post" style="display:inline;"><input type="hidden" name="delete_contact" value="<?= $row['id'] ?>"><button onclick="return confirm('Delete?')">Delete</button></form>
        </td>
    </tr>
    <?php endif; ?>
<?php endwhile; ?>
</table>

<!-- Таблица Билетов // Tickets Table -->
<h2>Ticket Orders</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Type</th><th>Count</th><th>Message</th><th>Actions</th></tr>
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
            <form method="get" style="display:inline;"><input type="hidden" name="edit_ticket" value="<?= $row['id'] ?>"><button>Edit</button></form>
            <form method="post" style="display:inline;"><input type="hidden" name="delete_ticket" value="<?= $row['id'] ?>"><button onclick="return confirm('Delete?')">Delete</button></form>
        </td>
    </tr>
    <?php endif; ?>
<?php endwhile; ?>
</table>

</body>
</html>

<?php $conn->close(); // Закрытие соединения с БД // Close DB connection ?>
