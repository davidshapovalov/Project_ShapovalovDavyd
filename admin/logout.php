<?php
session_start();

// Класс для выхода
class Auth {
    public function logout() {
        session_destroy(); //ЗАКАНЧИВАЕМ СЕССИЮ
        header("Location: login.php"); //ПЕРЕХОДИМ НА ЛОГИН ПХП
        exit;  //РЕТЕРНАЕМ ВОЙД
    }
}

$auth = new Auth();
$auth->logout();
