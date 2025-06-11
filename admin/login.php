<?php
session_start(); //открытие СЕССИИ для хранения данных зашел админ или нет
//ХРАНИТ данные между страницами
// ЭТО как глобальная перменная, глобальный СЛОВАРЬ

// Класс для авторизации 
class Auth {
    public $error = ""; //хранит данные СООБЩЕНИЕ ОБ ОШИБКЕ паблик дает обратится вне класса
    // стринг если что ЭРРОР

    public function login($username, $password) {
    // public void login(String username, String password)

        if ($username == "admin" && $password == "12345") {
            $_SESSION['admin_logged_in'] = true;  //СОХРАНЯЕМ в сессию что АДМИН ЗАШЕЛ
            header("Location: admin.php"); //ПЕРЕНАПРАЯВЛЯЕМ типа в АДМИН пхп
            exit; //как пустой ретёрн в джаве, просто брейк войд метода
        } else {
            $this->error = "Incorrect password or name"; //ошибку добавляем
        }
    }
}

// Создаем объект 
$auth = new Auth();

// Если отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {   //отправлена ли форма методом пост      if request.method == "POST":
    $user = $_POST['username'] ?? '';     // забираем ИМЯ с ПОСТА и создаем переменную ДЛЯ ОТПРАВКИ В метод ЛОГИН
    $pass = $_POST['password'] ?? '';
    $auth->login($user, $pass);  // ВЫЗЫВАЕМ метод логин для обьекта АВТОРИЗАЦИИ класса
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Admin login</h2>

<?php
      //ЕСЛИ ОШИБКА ЕСТЬ то выведет безопасно НЕПРАВИЛЬНЫЙ пароль 
    if ($auth->error != "") {
        echo '<p style="color:red;">' . htmlspecialchars($auth->error) . '</p>';
    }
?>


<form method="post">
    <label>Name :<br>
        <input type="text" name="username" required>
    </label><br><br>

    <label>Password :<br>
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit">Enter</button>
</form>

</body>
</html>
