<?php
require_once __DIR__ . '/../classes/Ticket.php'; //ЭТО как импорт в пайтон, подключает ПХП только один раз если был подключен, больше не подключится
//дир специальная переменная содержащая путь к папке

if ($_SERVER["REQUEST_METHOD"] == "POST") { //отправлена ли форма методом пост      if request.method == "POST":
    $ticket = new Ticket($_POST); //cоздаем обьект и передаем ему ПОСТ
    //ПОСТ это массив, типо СЛОВАРЯ, где есть поля имя, номер билета и т.д

    if ($ticket->save()) {    //вызов метода сейв у обьекта тикет ЕСЛИ тру по сохранил
        echo "<p style='color: white; font-weight: bold; background-color: green; padding: 10px;'>Thank you, " . $ticket->getName() . "! Your ticket request has been received.</p>";
        //print(f"Thank you, {ticket.get_name()}!")
    } else { //если фолс то ошибка
        foreach ($ticket->getErrors() as $error) { //вызов метода обьекта тикет на ошибки
            echo "<p style='color: red;'>$error</p>";
        }
    }
} else {   //если вообще не ПОСТ, то типо как ты вообще сюда попал
    echo "<p style='color: red;'>Invalid request.</p>";
}
