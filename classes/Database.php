<?php
class Database {
    private $host = "localhost";
    private $dbname = "concert";
    private $username = "root";
    private $password = "";
    private $conn;   //по дефолту НУЛЛ

    public function connect() {    //метод для соединения с датабазой
        if ($this->conn === null) {   //крч НУЛЛ же по дефолту, поэтому в первый раз КОННЕКТИМ
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname); //создаем ОБЬЕКТ для подключения к датабазе
            //с помощью mysqli подключаемя, через аттрибиуты для локалки уже настроенные
            if ($this->conn->connect_error) {   //если наш обьект имеет встроенную методу тру для ОШИБКИ КОННЕКТА
                die("Ошибка подключения: " . $this->conn->connect_error); // то выводим сообщение
            }
            //if self.conn.connect_error:
                //raise Exception("Error: " + self.conn.connect_error)
        }
        return $this->conn;  //теперь из другого кода можно через метод КОНН иметь коннект с датабазой
    }
}
