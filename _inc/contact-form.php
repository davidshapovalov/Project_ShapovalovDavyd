<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $dbname = "concert";
    $username = "root";
    $password = "";

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $name = htmlspecialchars(trim($_POST['contact-name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['contact-email'] ?? ''));
    $company = htmlspecialchars(trim($_POST['contact-company'] ?? ''));
    $message = htmlspecialchars(trim($_POST['contact-message'] ?? ''));

    if (empty($name) || empty($email) || empty($company)) {
        echo "<p style='color: red;'>Пожалуйста, заполните все обязательные поля.</p>";
        exit;
    }

    $sql = "INSERT INTO contacts (name, email, company, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $company, $message);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Спасибо, $name! Ваше сообщение успешно отправлено.</p>";
    } else {
        echo "<p style='color: red;'>Ошибка: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p style='color: red;'>Неверный запрос</p>";
}
?>
