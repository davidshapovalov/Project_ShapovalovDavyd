<?php
class AdminPanel2 { // КЛАСС для таблицы БИЛЕТОВ
    private $conn; //соеденение с датабазой из ДАТАБАЗА пхп
    private $table = 'tickets'; // ИМЯ таблицы с базы ДАННЫх чтобы с ней работать
    private $edit_id_ticket = null; // ХРАНИТ айди строки которую мы редачим, по дефолту нулл

    public function __construct() {
        $this->checkLogin();  //Вызываем метод, провекра зашел ли админ

        $db = new Database();  //делаем обьект ДАТАБАЗУ, консктуктора там нет
        $this->conn = $db->connect(); //вызывает от этого обьекта метод коннект и он передаёт КОНН в наш КОНТАКТНАЯ ТАБЛИЦА обьект

        $this->handlePost(); // метод ОБРАБОТКА действия УДАЛИТЬ или РЕДАЧИТЬ

        if (isset($_GET['edit2'])) {
            $this->edit_id_ticket = (int)$_GET['edit2'];
        } else {
            $this->edit_id_ticket = null;
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
            exit; //Ретёрнаем войд
        }
    }

    private function handlePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {   //Была ли нажата кнопка УДАЛИТЬ или РЕДАЧИТЬ
            if (isset($_POST['delete_id_tickets'])) {  // ЕСЛИ ПОСТ - удалить НАЖАЛ
                $id = (int)$_POST['delete_id_tickets'];  // Берем айди и в ИНТ против взлома переводим
                // КВЕРИ - запрос к датабаз
                $this->conn->query("DELETE FROM {$this->table} WHERE id = $id");  //УДАЛИТЬ там где айди такое 

            } elseif (isset($_POST['edit_id_tickets'])) {  // ЕСЛИ ПОСТ - редачить НАЖАЛ
                $id = (int)$_POST['edit_id_tickets']; // Берем айди и в ИНТ против взлома переводим
                // БЕРЕМ СТОЛБЦЫ С ЗАЩИТОЙ
                $name = $this->conn->real_escape_string($_POST['name'] ?? '');
                $email = $this->conn->real_escape_string($_POST['email'] ?? '');
                $phone = $this->conn->real_escape_string($_POST['phone'] ?? '');
                $ticket_count = (int)($_POST['ticket_count'] ?? 0);
                $ticket_type = $this->conn->real_escape_string($_POST['ticket_type'] ?? '');
                // КВЕРИ - запрос к датабазе
                // Обновляем запись
                $this->conn->query("UPDATE {$this->table} SET name='$name', email='$email', phone='$phone', ticket_count=$ticket_count, ticket_type='$ticket_type' 
                    WHERE id=$id");

            } elseif (isset($_POST['create_ticket'])) {  // ЕСЛИ ПОСТ - создать НАЖАЛ
                $name = $this->conn->real_escape_string($_POST['name'] ?? '');  // ЕСЛИ ПОСТ - cоздать НАЖАЛ
                $email = $this->conn->real_escape_string($_POST['email'] ?? '');
                $phone = $this->conn->real_escape_string($_POST['phone'] ?? '');
                $ticket_count = (int)($_POST['ticket_count'] ?? 0);
                $ticket_type = $this->conn->real_escape_string($_POST['ticket_type'] ?? '');
                $this->conn->query("INSERT INTO {$this->table} (name, email, phone, ticket_count, ticket_type) VALUES ('$name', '$email', '$phone', $ticket_count, '$ticket_type')");
            }
        }
    }

    private function getAllRecords() {
        $result = $this->conn->query("SELECT * FROM {$this->table} ORDER BY id DESC");    //Берем данные из таблицы
        if (!$result) {   // ЕСЛИ данных нет то ошибка
            die("SQL Error: " . $this->conn->error); //возвращаем
        } 
        return $result; //возвращаем
    }

    public function render() {
        $records = $this->getAllRecords();
        ?>
        <h2>Tickets</h2>
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Telephone number</th><th>Tickets count</th><th>Tickets type</th><th>Actions</th>
            </tr> 
            <?php while ($row = $records->fetch_assoc()) {  // ФЕТЧ возвращает строку в виде такого СЛОВАРЯ
                    // ПЕРЕБИРАЕТ строку по одной
                    //          $row = [
                    //              'id' => 2,
                    //              'name' => 'N',
                    //              'email' => 'n@mail.com',
                    //              'company' => 'com',
                    //              'message' => 'Hello'
                    //            ];
                    // проходимся пока не дропнется айди которое редачится
                    if ($this->edit_id_ticket === (int)$row['id']) {  // ЕСЛИ РЕДАКТИРУЕТСЯ
                    // === это проверка что оба ИНТ и оба равны?>
                        <tr>
                            <form method="post">
                            <!-- ПОКАЗЫВАЕТ АЙДИ ЕГО ВВЕСТИ НЕЛЬЗЯ, НО СКРЫТЫЙ ИМПУТ ЕСТЬ ЧТОБЫ ЧТО_ТО ОТПРАВИТЬ -->
                                <td><?php echo $row['id']; ?><input type="hidden" name="edit_id_tickets" value="<?php echo $row['id']; ?>"></td>

                                <!-- БЕРЕМ ЗНАЧЕНИЕ КОТОРОЕ УЖЕ БУДЕТ В ИНПУТЕ ИЗ РОВА И МОЖНО РЕДАЧИТЬ -->
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
                                <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></td>
                                <td><input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>"></td>
                                <td><input type="number" name="ticket_count" min="0" value="<?php echo (int)$row['ticket_count']; ?>"></td>
                                <td><input type="text" name="ticket_type" value="<?php echo htmlspecialchars($row['ticket_type']); ?>"></td>
                                <td><button type="submit">Save</button></td>
                                <!-- ПОСЛЕ НАЖАТИЯ СЕЙВ ПЕРЕЗАПУСК СТРАНИЦЫ -->
                            </form>
                        </tr>
                    <?php } else {   // ЕСЛИ НЕ РЕДАКТИРУЕТСЯ
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo (int)$row['ticket_count']; ?></td>
                            <td><?php echo htmlspecialchars($row['ticket_type']); ?></td>
                            <td>
                                <form method="get" style="display:inline;">
                                    <!-- И СРАЗУ ПЕРЕДАЕШЬ АЙДИ ЧТОБЫ БЫЛО ПОНЯТНО КОГДА РЕДАЧИШЬ -->
                                    <input type="hidden" name="edit2" value="<?php echo $row['id']; ?>">
                                    <!-- КАЖДЫЙ РАЗ НАЖИМАЯ КНОПКУ СТРАНИЦА ПЕРЕЗАПУСКАЕТСЯ -->
                                    <!-- ЗНАЧИТ КОНСТРУКТОР ВЫПОЛНЯЕТСЯ СНОВА И ПРОВЕРЯЕТ НАЖАТА ЛИ КНОПКА -->
                                    <button>Edit</button>
                                </form>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="delete_id_tickets" value="<?php echo $row['id']; ?>">
                                    <!-- ПЕРЕДАЕМ ЧТО НУЖНО УДАЛИТЬ              КОНТКРЕТНОЕ АЙДИ -->
                                    <button onclick="return confirm('Delete?');">Delete</button>
                                     <!-- НА ВСЯКИЙ СПРАШИВАЕМ НУЖНО ЛИ УДАЛИТЬ -->
                                </form>
                            </td>
                        </tr>

                       <!-- ПОСЛЕ ПОСТ МЕТОДА ОТПРАВКИ СТРАНИЦА ПЕРЕЗАГРУЖАЕТСЯ И СНАЧАЛА КОНСТРУКТОР СМОТРИТ ЧТО УДАЛИТЬ ИЛИ РЕДАЧИТЬ А ПОТОМ РЕНДЕРИТ УЖЕ -->

            <?php }
                }
            ?>
        </table>
        <!-- Форма для создания нового билета -->
        <h3>Create new ticket</h3>
        <form method="post">
            <input type="hidden" name="create_ticket" value="1">
            <table>
                <tr>
                    <td><input type="text" name="name" placeholder="Name" required></td>
                    <td><input type="email" name="email" placeholder="Email" required></td>
                    <td><input type="text" name="phone" placeholder="Phone"></td>
                    <td><input type="number" name="ticket_count" placeholder="Tickets count" min="0" value="1"></td>
                    <td><input type="text" name="ticket_type" placeholder="Tickets type"></td>
                    <td><button type="submit">Create</button></td>
                </tr>
            </table>
        </form>

        <?php
    }
    
    // АВТОМАТИЧЕСКИ вызывается после того как не работаем
    public function __destruct() {
        $this->conn->close();
    }
    // крч бесполезно, тупо для читабельности кода. ПХП и сам это сделать может
}