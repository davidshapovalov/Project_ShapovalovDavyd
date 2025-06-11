<?php
require_once __DIR__ . '/Database.php'; //ЭТО как импорт в пайтон, подключает ПХП только один раз если был подключен, больше не подключится
//дир специальная переменная содержащая путь к папке

class Contact {
    private $conn; //соеденение с датабазой из ДАТАБАЗА пхп
    private $name;
    private $email;
    private $company;
    private $message;
    private $errors = []; //cписок ошибок

    public function __construct($formData) {  //конструктор, ФОРМ ДАТА это наш ПОСТ который взяли из ТикетФорм ПХП
        $db = new Database(); //делаем обьект ДАТАБАЗУ, консктуктора там нет
        $this->conn = $db->connect();  //вызывает от этого обьекта метод коннект и он передаёт КОНН в наш ТИКЕТ обьект

        // Чистим и записываем данные из формы
        $this->name = $this->sanitize($formData['contact-name'] ?? ''); // ?? это если пользователь ничего не ввел то будет просто пусто ''
        $this->email = $this->sanitize($formData['contact-email'] ?? ''); //террарный оператор
        $this->company = $this->sanitize($formData['contact-company'] ?? '');
        $this->message = $this->sanitize($formData['contact-message'] ?? '');
    }

    private function sanitize($value) {  //метод для очистки данных
        return htmlspecialchars(trim($value));  //ЗАЩИЩАЕМ от опасный скриптов в ХТМЛ и ДЖС и убираем пробелы лишние
    }

    public function validate() {
        if (empty($this->name) || empty($this->email) || empty($this->company)) {
            $this->errors[] = "Please fill in all required fields."; //хоть что-то пустое, вызов этого
        }
        //if not name or not email or not company:
            //errors.append("Please fill in all required fields.")

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {  //встроенная функция в ПХП фильтер ВАР если имейл неправильный то фолс
            $this->errors[] = "Invalid email address.";
        }

        return empty($this->errors); //если нет ошибок то выводит ТРУ
    }

    public function save() {  //вызывается сейв в ТИКЕТ ФОРМ, для проверки все ли ок
        if (!$this->validate()) {  //ну если твои данные не подходят критериям то не сейф
            return false;
        }

        $sql = "INSERT INTO contacts (name, email, company, message) VALUES (?, ?, ?, ?)";
        // СКЛЮ запрос с ВОПРОСИКАМИ чтобы не подставлять сразу опасные значения
        $stmt = $this->conn->prepare($sql); //из датабазы берем метод ПОДГОТОВКИ встроенный и наш запрос вставляем

        if (!$stmt) { //если какая-то ошибка и что-то пошло не так
            $this->errors[] = "Error preparing request: " . $this->conn->error;
            return false;
        }
        
        // s - String
        $stmt->bind_param("ssss", $this->name, $this->email, $this->company, $this->message);
        // Дает значения и типы данных. ВСТАВЛЯЕТ ДАННЫЕ В ЗНАКИ ВОПРОСА

        $result = $stmt->execute();  // В РЕЗУЛЬТАТ сохраняем ВЫПОЛНЯЕМ ЗАПРОС если ВОПРОСИКИ не пустые

        if (!$result) {  //если что-то опять пошло не так
            $this->errors[] = "Saving error: " . $stmt->error;
        }

        $stmt->close();  //закрываем запрос после работы
        return $result;  // и возвращаем наш результат работы
    }

    public function getErrors() {  //дает список ошибок для вывода
        return $this->errors;
    }

    public function getName() {  //дает имя для вывода
        return $this->name;
    }
}
