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

    // Подключаемся к датабазе
    // Connecting to datase
    $conn = new mysqli($host, $username, $password, $dbname);

    // Если ошибка с коннектом датабазы
    // If have error with database
    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    // Получаем данные из формы и убираем лишние пробелы и спецсимволы
    // Get date from form 
    $name = trim($_POST['contact-name'] ?? '');
    $email = trim($_POST['contact-email'] ?? '');
    $company = trim($_POST['contact-company'] ?? '');
    $message = trim($_POST['contact-message'] ?? '');

    // Преобразуем спецсимволы в безопасный вид
    // Security from inputs
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $company = htmlspecialchars($company);
    $message = htmlspecialchars($message);

    // Проверяем, что обязательные поля не пустые
    // Cheking if inputs is empty
    if (empty($name) || empty($email) || empty($company)) {
        echo "<p style='color: red;'>Please fill in all required fields.</p>";
        exit;
    }

    // SQL-запрос на добавление данных в таблицу contacts
    // SQL dotaz to adding dates to table contacts
    $sql = "INSERT INTO contacts (name, email, company, message) VALUES (?, ?, ?, ?)";

    // Готовим запрос (предотвращает SQL-инъекции)
    $stmt = $conn->prepare($sql);

    // Привязываем значения к запросу (4 строки string поэтому ssss)
    // Conneting inputs to SQL doraz (4 string => ssss)
    $stmt->bind_param("ssss", $name, $email, $company, $message);

    // Выполняем запрос и проверяем успешность
    // Cheking and do it
    if ($stmt->execute()) {
        echo "<p style='color: white; font-weight: bold; background-color: green; padding: 10px;'>Thank you, $name! Your message has been sent successfully.</p>";
    } else {
        echo "<p style='color: white; font-weight: bold; background-color: red; padding: 10px;'>Error sending message: " . $stmt->error . "</p>";
    }

    // Закрываем запрос и соединение
    // Closing SQL and connecting
    $stmt->close();
    $conn->close();

} else {
    // Если кто-то зашёл напрямую на страницу, а не отправил форму
    // If someone dont send form and went to the web
    echo "<p style='color: red;'>Invalid request.</p>";
}
?>
