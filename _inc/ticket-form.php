<?php
// Данные для подключения к базе данных
$host = "localhost";
$dbname = "concert";
$username = "root";
$password = "";

// Создаем подключение к базе данных
$conn = new mysqli($host, $username, $password, $dbname);

// Проверяем, есть ли ошибка подключения
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

// Получаем данные из формы и убираем лишние пробелы
$name = trim($_POST['ticket-form-name'] ?? '');
$email = trim($_POST['ticket-form-email'] ?? '');
$phone = trim($_POST['ticket-form-phone'] ?? '');
$ticket_type = $_POST['ticket-form-type'] ?? '';
$number = $_POST['ticket-form-number'] ?? '';
$message = trim($_POST['ticket-form-message'] ?? '');

// Создаем массив для ошибок
$errors = [];

// Проверяем, что имя не пустое
if (empty($name)) {
    $errors[] = "Please enter your name.";
}

// Проверяем, что email заполнен и валиден
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

// Проверяем, что телефон заполнен и соответствует формату 123-456-7890
if (empty($phone) || !preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone)) {
    $errors[] = "Please enter a valid phone number (format: 123-456-7890).";
}

// Проверяем, что выбран тип билета
if (empty($ticket_type)) {
    $errors[] = "Please select a ticket type.";
}

// Проверяем, что количество билетов указано и это число от 1 и выше
if (empty($number) || !filter_var($number, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
    $errors[] = "Please enter a valid number of tickets.";
}

// Если есть ошибки — выводим их
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
} else {
    // Готовим SQL-запрос для вставки данных в таблицу tickets
    $stmt = $conn->prepare("INSERT INTO tickets (name, email, phone, ticket_type, ticket_count, message) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Привязываем параметры к запросу (ssssis — 4 строки, 1 целое число и еще 1 строка)
    $stmt->bind_param("ssssis", $name, $email, $phone, $ticket_type, $number, $message);
    
    // Выполняем запрос и проверяем результат
    if ($stmt->execute()) {
        // Безопасно выводим имя пользователя (защита от XSS)
        $safe_name = htmlspecialchars($name);
        echo "<p>Thank you, $safe_name! Your ticket request has been received.</p>";
    } else {
        echo "<p style='color:red;'>Error saving data.</p>";
    }

    // Закрываем подготовленный запрос
    $stmt->close();
}

// Закрываем соединение с базой данных
$conn->close();
?>
