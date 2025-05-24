<?php
// Проверяем, была ли отправлена форма методом POST
// Checking if the form sent by POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Данные для подключения к базе
    // Data for connecting to database
    $host = "localhost";
    $dbname = "concert";
    $username = "root";
    $password = "";

    // Подключаемся к базе
    // Connecting to database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Если ошибка с подключением
    // If connection error
    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    // Получаем данные из формы и убираем пробелы
    // Getting data from form
    $name = trim($_POST['ticket-form-name'] ?? '');
    $email = trim($_POST['ticket-form-email'] ?? '');
    $phone = trim($_POST['ticket-form-phone'] ?? '');
    $ticket_type = trim($_POST['ticket-form-type'] ?? '');
    $number = trim($_POST['ticket-form-number'] ?? '');
    $message = trim($_POST['ticket-form-message'] ?? '');

    // Преобразуем спецсимволы в безопасный вид
    // Security from inputs
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $phone = htmlspecialchars($phone);
    $ticket_type = htmlspecialchars($ticket_type);
    $number = htmlspecialchars($number);
    $message = htmlspecialchars($message);

    // Проверяем обязательные поля
    // Checking required inputs
    if (empty($name) || empty($email) || empty($phone) || empty($ticket_type) || empty($number)) {
        echo "<p style='color: red;'>Please fill in all required fields.</p>";
        exit;
    }

    // Проверяем правильность email
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'>Invalid email address.</p>";
        exit;
    }

    // Проверяем правильность телефона (формат 123-456-7890)
    // Check if phone is in format 123-456-7890
    if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone)) {
        echo "<p style='color: red;'>Invalid phone format. Use 123-456-7890.</p>";
        exit;
    }

    // Проверка числа билетов
    // Check if ticket number is valid
    if (!filter_var($number, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        echo "<p style='color: red;'>Invalid number of tickets.</p>";
        exit;
    }

    // SQL-запрос на добавление данных в таблицу tickets
    // SQL query to add data to tickets table
    $sql = "INSERT INTO tickets (name, email, phone, ticket_type, ticket_count, message) VALUES (?, ?, ?, ?, ?, ?)";

    // Готовим запрос (предотвращает взлом через запрос)
    // Prepare dotaz (and security from hacking SQL)
    $stmt = $conn->prepare($sql);

    // Привязываем параметры (4 строки, 1 число, 1 строка => sssis)
    // Binding values (4 strings, 1 integer, 1 string => sssis)
    $stmt->bind_param("ssssis", $name, $email, $phone, $ticket_type, $number, $message);

    // Выполняем запрос и проверяем результат
    // Execute and check
    if ($stmt->execute()) {
        echo "<p style='color: white; font-weight: bold; background-color: green; padding: 10px;'>Thank you, $name! Your ticket request has been received.</p>";
    } else {
        echo "<p style='color: white; font-weight: bold; background-color: red; padding: 10px;'>Error saving ticket: " . $stmt->error . "</p>";
    }

    // Закрываем запрос и соединение
    // Closing SQL and connection
    $stmt->close();
    $conn->close();

} else {
    // Если кто-то зашел напрямую, не отправив форму
    // If user came directly without sending form
    echo "<p style='color: red;'>Invalid request.</p>";
}
?>
