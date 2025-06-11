<?php
require_once __DIR__ . '/Database.php';   //ЭТО как импорт в пайтон, подключает ПХП только один раз если был подключен, больше не подключится
//дир специальная переменная содержащая путь к папке

class Ticket {
    private $conn;  //соеденение с датабазой из ДАТАБАЗА пхп
    private $name;
    private $email;
    private $phone;
    private $ticket_type;
    private $ticket_count;
    private $message;
    private $errors = []; //cписок ошибок

    public function __construct($formData) {  //конструктор, ФОРМ ДАТА это наш ПОСТ который взяли из ТикетФорм ПХП
        $db = new Database();   //делаем обьект ДАТАБАЗУ, консктуктора там нет
        $this->conn = $db->connect();  //вызывает от этого обьекта метод коннект и он передаёт КОНН в наш ТИКЕТ обьект

        // Чистим и записываем данные из формы
        $this->name = $this->sanitize($formData['ticket-form-name'] ?? '');  // ?? это если пользователь ничего не ввел то будет просто пусто ''
        $this->email = $this->sanitize($formData['ticket-form-email'] ?? '');  //террарный оператор
        $this->phone = $this->sanitize($formData['ticket-form-phone'] ?? '');
        $this->ticket_type = $this->sanitize($formData['ticket-form-type'] ?? '');
        $this->ticket_count = $this->sanitize($formData['ticket-form-number'] ?? '');
        $this->message = $this->sanitize($formData['ticket-form-message'] ?? '');
    }

    private function sanitize($value) {  //метод для очистки данных
        return htmlspecialchars(trim($value));  //ЗАЩИЩАЕМ от опасный скриптов в ХТМЛ и ДЖС и убираем пробелы лишние
    }

    public function validate() {
        if (empty($this->name) || empty($this->email) || empty($this->phone) || empty($this->ticket_type) || empty($this->ticket_count)) {
            $this->errors[] = "Please fill in all required fields.";   //хоть что-то пустое, вызов этого
        }
        //if not name or not email or not phone or not ticket_type or not ticket_count:
            //errors.append("Please fill in all required fields.")

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {   //встроенная функция в ПХП фильтер ВАР если имейл неправильный то фолс
            $this->errors[] = "Invalid email address.";
        }

        if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $this->phone)) {   //регулярное выражение, проверяет телефон
            $this->errors[] = "Invalid phone format. Use 123-456-7890.";
        }

        if (!filter_var($this->ticket_count, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
            $this->errors[] = "Invalid number of tickets.";
        }
        //if not (ticket_count.isdigit() and int(ticket_count) >= 1):
            //errors.append("Invalid number of tickets.")

        return empty($this->errors);   //если нет ошибок то выводит ТРУ
    }

    public function save() {   //вызывается сейв в ТИКЕТ ФОРМ, для проверки все ли ок
        if (!$this->validate()) { //ну если твои данные не подходят критериям то не сейф
            return false;
        }

        $sql = "INSERT INTO tickets (name, email, phone, ticket_type, ticket_count, message) VALUES (?, ?, ?, ?, ?, ?)";
        // СКЛЮ запрос с ВОПРОСИКАМИ чтобы не подставлять сразу опасные значения
        $stmt = $this->conn->prepare($sql); //из датабазы берем метод ПОДГОТОВКИ встроенный и наш запрос вставляем

        if (!$stmt) {  //если какая-то ошибка и что-то пошло не так
            $this->errors[] = "Error preparing request: " . $this->conn->error;
            return false;
        }
        
        // s - String, i - Integer
        $stmt->bind_param("ssssis", $this->name, $this->email, $this->phone, $this->ticket_type, $this->ticket_count, $this->message);
        // Дает значения и типы данных. ВСТАВЛЯЕТ ДАННЫЕ В ЗНАКИ ВОПРОСА

        $result = $stmt->execute();   // В РЕЗУЛЬТАТ сохраняем ВЫПОЛНЯЕМ ЗАПРОС если ВОПРОСИКИ не пустые

        if (!$result) {   //если что-то опять пошло не так
            $this->errors[] = "Saving error: " . $stmt->error;
        }

        $stmt->close();   //закрываем запрос после работы
        return $result;   // и возвращаем наш результат работы
    }

    public function getErrors() { //дает список ошибок для вывода
        return $this->errors;
    }

    public function getName() { //дает имя для вывода
        return $this->name;
    }
}
