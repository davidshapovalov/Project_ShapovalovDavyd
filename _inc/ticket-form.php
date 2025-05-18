<?php
$host = "localhost";
$dbname = "concert";
$username = "root";
$password = "";

// Подключение к БД
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение и проверка данных
$name = trim($_POST['ticket-form-name'] ?? '');
$email = trim($_POST['ticket-form-email'] ?? '');
$phone = trim($_POST['ticket-form-phone'] ?? '');
$ticket_type = $_POST['ticket-form-type'] ?? '';  
$number = $_POST['ticket-form-number'] ?? '';
$message = trim($_POST['ticket-form-message'] ?? '');

$errors = [];

if (empty($name)) {
    $errors[] = "Please enter your name.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
if (empty($phone) || !preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone)) {
    $errors[] = "Please enter a valid phone number (format: 123-456-7890).";
}
if (empty($ticket_type)) {
    $errors[] = "Please select a ticket type.";
}
if (empty($number) || !filter_var($number, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
    $errors[] = "Please enter a valid number of tickets.";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
} else {
    // Подготовка запроса
    $stmt = $conn->prepare("INSERT INTO tickets (name, email, phone, ticket_type, ticket_count, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $name, $email, $phone, $ticket_type, $number, $message);
    
    if ($stmt->execute()) {
        $safe_name = htmlspecialchars($name);
        echo "<p>Thank you, $safe_name! Your ticket request has been received.</p>";
    } else {
        echo "<p style='color:red;'>Ошибка при сохранении данных.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

