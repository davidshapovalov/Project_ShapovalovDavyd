<?php
// Start session to save login status
// Запускаем сессию, чтобы сохранить статус входа
session_start();

// Set admin username and password
// Устанавливаем имя пользователя и пароль администратора
$admin_user = "admin";
$admin_pass = "12345";

// Create a value for error message
// Создаём переменную для хранения сообщения об ошибке
$error = "";

// Check if the form was sended
// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    // Получаем имя пользователя и пароль из формы
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Check if the inputs data == admin data
    // Проверка на данные вода == данным админа
    if ($user === $admin_user && $pass === $admin_pass) {
        // Correct login — save to session
        // Вход успешный — сохраняем в сессии, что админ вошёл
        $_SESSION['admin_logged_in'] = true;

        // To admin page
        // Перенаправляем на страницу администратора
        header("Location: admin.php");
        exit;
    } else {
        // Wrong login — show error
        // Неверный логин или пароль — показать ошибку
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>

<h2>Admin Login</h2>

<!-- Show error message if it exists -->
<!-- Покажи сообщение об ошибке, если оно есть -->
<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- Login form -->
<!-- Форма входа -->
<form method="post">
    <label>Username:<br>
        <input type="text" name="username" required>
    </label><br><br>

    <label>Password:<br>
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>
