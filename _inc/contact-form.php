<?php
// Проверяем, была ли отправлена форма методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Данные для подключения к базе
    $host = "localhost";         // Сервер базы данных
    $dbname = "concert";         // Название базы
    $username = "root";          // Имя пользователя
    $password = "";              // Пароль (обычно пустой на локалке)

    // Подключаемся к базе данных
    $conn = new mysqli($host, $username, $password, $dbname);

    // Если есть ошибка подключения, показываем сообщение и выходим
    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    // Получаем данные из формы и убираем лишние пробелы и спецсимволы
    $name = trim($_POST['contact-name'] ?? '');
    $email = trim($_POST['contact-email'] ?? '');
    $company = trim($_POST['contact-company'] ?? '');
    $message = trim($_POST['contact-message'] ?? '');

    // Преобразуем спецсимволы в безопасный вид (например, < в &lt;)
    $name = htmlspecialchars($name);
    $email = htmlspecialchars($email);
    $company = htmlspecialchars($company);
    $message = htmlspecialchars($message);

    // Проверяем, что обязательные поля не пустые
    if (empty($name) || empty($email) || empty($company)) {
        echo "<p style='color: red;'>Please fill in all required fields.</p>";
        exit;
    }

    // SQL-запрос на добавление данных в таблицу contacts
    $sql = "INSERT INTO contacts (name, email, company, message) VALUES (?, ?, ?, ?)";

    // Готовим запрос (предотвращает SQL-инъекции)
    $stmt = $conn->prepare($sql);

    // Привязываем значения к запросу (4 строки типа string, поэтому "ssss")
    $stmt->bind_param("ssss", $name, $email, $company, $message);

    // Выполняем запрос и проверяем успешность
    if ($stmt->execute()) {
    echo "<p style='color: white; font-weight: bold; background-color: green; padding: 10px;'>Thank you, $name! Your message has been sent successfully.</p>";
    } else {
        echo "<p style='color: white; font-weight: bold; background-color: red; padding: 10px;'>Error sending message: " . $stmt->error . "</p>";
    }



    // Закрываем запрос и соединение
    $stmt->close();
    $conn->close();

} else {
    // Если кто-то зашёл напрямую на страницу, а не отправил форму
    echo "<p style='color: red;'>Invalid request.</p>";
}
?>
