<?php
// Start session to store login status
// Запускаем сессию, чтобы сохранить статус входа
session_start();

// Set admin username and password
// Устанавливаем имя пользователя и пароль администратора
$admin_user = "admin";
$admin_pass = "12345";

// Create a variable for error message
// Создаём переменную для хранения сообщения об ошибке
$error = "";

// Check if the form was submitted
// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    // Получаем имя пользователя и пароль из формы
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    // Check if the input matches the admin credentials
    // Проверяем, совпадают ли введённые данные с учётными данными администратора
    if ($user === $admin_user && $pass === $admin_pass) {
        // Correct login — save to session
        // Вход успешный — сохраняем в сессии, что админ вошёл
        $_SESSION['admin_logged_in'] = true;

        // Redirect to admin page
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
        <!-- Поле для ввода имени пользователя -->
        <input type="text" name="username" required>
    </label><br><br>

    <label>Password:<br>
        <!-- Поле для ввода пароля -->
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Login</button>
    <!-- Кнопка входа -->
</form>

</body>
</html>
