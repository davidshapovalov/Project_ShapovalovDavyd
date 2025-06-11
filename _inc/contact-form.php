<?php
require_once __DIR__ . '/../classes/Contact.php'; //ЭТО как импорт в пайтон, подключает ПХП только один раз если был подключен, больше не подключится
//дир специальная переменная содержащая путь к папке

if ($_SERVER["REQUEST_METHOD"] == "POST") {  //отправлена ли форма методом пост      if request.method == "POST":
    $contact = new Contact($_POST); //cоздаем обьект и передаем ему ПОСТ
    //ПОСТ это массив, типо СЛОВАРЯ, где есть поля имя, номер билета и т.д

    if ($contact->save()) {  //вызов метода сейв у обьекта тикет ЕСЛИ тру по сохранил
        echo "<p style='color: white; font-weight: bold; background-color: green; padding: 10px;'>Thank you, " . $contact->getName() . "! Your message has been sent successfully.</p>";
        //print(f"Thank you, {ticket.get_name()}!")
    } else {  //если фолс то ошибка
        foreach ($contact->getErrors() as $error) {  //вызов метода обьекта тикет на ошибки
            echo "<p style='color: red;'>$error</p>";
        }
    }
} else {  //если вообще не ПОСТ, то типо как ты вообще сюда попал
    echo "<p style='color: red;'>Invalid request.</p>";
}
