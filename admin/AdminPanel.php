<?php
class AdminPanel {   // КЛАСС для таблицы КОНТАКТОВ
    private $conn; //соеденение с датабазой из ДАТАБАЗА пхп
    private $table = 'contacts';  // ИМЯ таблицы с базы ДАННЫх чтобы с ней работать
    private $edit_id = null; // ХРАНИТ айди строки которую мы редачим, по дефолту нулл

    public function __construct() {
        $this->checkLogin(); //Вызываем метод, провекра зашел ли админ

        $db = new Database();  //делаем обьект ДАТАБАЗУ, консктуктора там нет
        $this->conn = $db->connect(); //вызывает от этого обьекта метод коннект и он передаёт КОНН в наш КОНТАКТНАЯ ТАБЛИЦА обьект

        $this->handlePost();  // метод ОБРАБОТКА действия УДАЛИТЬ или РЕДАЧИТЬ

        if (isset($_GET['edit'])) {
            $this->edit_id = (int)$_GET['edit'];
        } else {
            $this->edit_id = null;
        }
        //    params = {'edit': '42'}  # Или пустой словарь, если параметра нет
        //    if 'edit' in params:
        //        try:
        //            edit_id = int(params['edit'])
        //        except ValueError:
        //            edit_id = None
        //    else:
        //        edit_id = None
        //    print(edit_id)

    }

    private function checkLogin() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) { 
            //НЕ СУЩЕСТВУЕТ ли переменная админ логин или СЕССИЯ НЕ ТРУ
            header("Location: login.php"); //Перекидываем на логин пхп
            exit;  //Ретёрнаем войд
        }
    }

    private function handlePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Была ли нажата кнопка УДАЛИТЬ или РЕДАЧИТЬ
            if (isset($_POST['delete_id'])) { // ЕСЛИ ПОСТ - удалить НАЖАЛ
                $id = (int)$_POST['delete_id'];  // Берем айди и в ИНТ против взлома переводим
                // КВЕРИ - запрос к датабазе
                $this->conn->query("DELETE FROM {$this->table} WHERE id = $id"); //УДАЛИТЬ там где айди такое 

            } elseif (isset($_POST['edit_id'])) { // ЕСЛИ ПОСТ - редачить НАЖАЛ
                $id = (int)$_POST['edit_id']; // Берем айди и в ИНТ против взлома переводим
                $name = $this->conn->real_escape_string($_POST['name'] ?? '');  // БЕРЕМ столбец с защитой 
                $email = $this->conn->real_escape_string($_POST['email'] ?? '');
                $company = $this->conn->real_escape_string($_POST['company'] ?? '');
                $message = $this->conn->real_escape_string($_POST['message']) ?? '';
                // КВЕРИ - запрос к датабазе
                $this->conn->query("UPDATE {$this->table} SET name='$name', email='$email', company='$company', message='$message' WHERE id=$id");

            } elseif (isset($_POST['create_contact'])) {  // ЕСЛИ ПОСТ - cоздать НАЖАЛ
                $name = $this->conn->real_escape_string($_POST['name'] ?? '');
                $email = $this->conn->real_escape_string($_POST['email'] ?? '');
                $company = $this->conn->real_escape_string($_POST['company'] ?? '');
                $message = $this->conn->real_escape_string($_POST['message'] ?? '');
                $this->conn->query("INSERT INTO {$this->table} (name, email, company, message) VALUES ('$name', '$email', '$company', '$message')");
        }
        }
    }

    private function getAllRecords() {
        $result = $this->conn->query("SELECT * FROM {$this->table} ORDER BY id DESC"); //Берем данные из таблицы
        if (!$result) { // если нет то ошибка
            die("SQL Error: " . $this->conn->error);
        }
        return $result; //возвращаем
    }

    public function render() {
        $records = $this->getAllRecords(); //Берем обьект май_склю РЕЗАЛТ
        ?>
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8" />
            <title>Admin panel</title>
            <style>
                body { font-family: Arial; padding: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                th, td { border: 1px solid #ccc; padding: 8px; }
                th { background: #eee; }
                input { width: 100%; }
                form { margin: 0; }
            </style>
        </head>
        <body>
        <h1>Admin panel</h1>
        <p><a href="logout.php">Exit</a></p>
        <h2>Contacts</h2>
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Company</th><th>Message</th><th>Actions</th>
            </tr>
            <?php while ($row = $records->fetch_assoc()) {   // ФЕТЧ возвращает строку в виде такого СЛОВАРЯ
            // ПЕРЕБИРАЕТ строку по одной
            //          $row = [
            //              'id' => 2,
            //              'name' => 'N',
            //              'email' => 'n@mail.com',
            //              'company' => 'com',
            //              'message' => 'Hello'
            //            ];
                
                // проходимся пока не дропнется айди которое редачится
                if ($this->edit_id === (int)$row['id']) { // ЕСЛИ РЕДАКТИРУЕТСЯ
                // === это проверка что оба ИНТ и оба равны?> 
                    <tr>
                    <form method="post">
                        <!-- ПОКАЗЫВАЕТ АЙДИ ЕГО ВВЕСТИ НЕЛЬЗЯ, НО СКРЫТЫЙ ИМПУТ ЕСТЬ ЧТОБЫ ЧТО_ТО ОТПРАВИТЬ -->
                        <td><?php echo $row['id']; ?><input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>"></td>
                        
                        <!-- БЕРЕМ ЗНАЧЕНИЕ КОТОРОЕ УЖЕ БУДЕТ В ИНПУТЕ ИЗ РОВА И МОЖНО РЕДАЧИТЬ -->
                        <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
                        <td><input type="text" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></td>
                        <td><input type="text" name="company" value="<?php echo htmlspecialchars($row['company']); ?>"></td>
                        <td><input type="text" name="message" value="<?php echo htmlspecialchars($row['message']); ?>"></td>
                        <td><button type="submit">Save</button></td> 
                        <!-- ПОСЛЕ НАЖАТИЯ СЕЙВ ПЕРЕЗАПУСК СТРАНИЦЫ -->
                    </form>
                    </tr>
                <?php } else {   // ЕСЛИ НЕ РЕДАКТИРУЕТСЯ                        ?>  
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['company']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td>
                            <form method="get" style="display:inline;">
                                <input type="hidden" name="edit" value="<?php echo $row['id']; ?>">   
                                <!-- И СРАЗУ ПЕРЕДАЕШЬ АЙДИ ЧТОБЫ БЫЛО ПОНЯТНО КОГДА РЕДАЧИШЬ -->
                                <button>Edit</button>
                                <!-- КАЖДЫЙ РАЗ НАЖИМАЯ КНОПКУ СТРАНИЦА ПЕРЕЗАПУСКАЕТСЯ -->
                                <!-- ЗНАЧИТ КОНСТРУКТОР ВЫПОЛНЯЕТСЯ СНОВА И ПРОВЕРЯЕТ НАЖАТА ЛИ КНОПКА -->
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <!-- ПЕРЕДАЕМ ЧТО НУЖНО УДАЛИТЬ              КОНТКРЕТНОЕ АЙДИ -->
                                <button onclick="return confirm('Delete?');">Delete</button> 
                                <!-- НА ВСЯКИЙ СПРАШИВАЕМ НУЖНО ЛИ УДАЛИТЬ -->
                            </form>

                        <!-- ПОСЛЕ ПОСТ МЕТОДА ОТПРАВКИ СТРАНИЦА ПЕРЕЗАГРУЖАЕТСЯ И СНАЧАЛА КОНСТРУКТОР СМОТРИТ ЧТО УДАЛИТЬ ИЛИ РЕДАЧИТЬ А ПОТОМ РЕНДЕРИТ УЖЕ -->

                        </td>
                    </tr>
            <?php } 
            } ?>
        </table>
        <h3>Create new contact</h3>
        <form method="post">
            <input type="hidden" name="create_contact" value="1">
            <table>
                <tr>
                    <td>Auto</td>
                    <td><input type="text" name="name" placeholder="Name" required></td>
                    <td><input type="email" name="email" placeholder="Email" required></td>
                    <td><input type="text" name="company" placeholder="Company"></td>
                    <td><input type="text" name="message" placeholder="Message"></td>
                    <td><button type="submit">Create</button></td>
                </tr>
            </table>
        </form>
        </body>
        </html>
        <?php
    }
    
    // АВТОМАТИЧЕСКИ вызывается после того как не работаем
    public function __destruct() {
        $this->conn->close();
    }
    // крч бесполезно, тупо для читабельности кода. ПХП и сам это сделать может
}