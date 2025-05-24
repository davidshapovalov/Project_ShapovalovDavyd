<?php
session_start();
session_destroy(); // Удаляет сессию все данные с неё удаляются
header("Location: login.php"); // Перенаправляет назад в логин
exit;
