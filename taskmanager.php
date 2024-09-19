<?php
class TaskManager {
    // Свойство для хранения подключения к базе данных
    private $conn;

    // Конструктор для инициализации подключения к базе данных
    public function __construct($host, $db, $user, $pass) {
        try {
            // Устанавливаю соединение с базой данных через PDO
            $this->conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            // Устанавливаю режим обработки ошибок для PDO на выброс исключений
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Если соединение не удалось, выводим сообщение об ошибке и прекращаем выполнение
            die(json_encode(['error' => 'Database error: ' . $e->getMessage()]));
        }
    }

    // Метод для проверки, вошел ли пользователь в систему
    public function isLoggedIn() {
        // Стартую сессию
        session_start();
        // Проверяю наличие идентификатора пользователя в сессии
        return isset($_SESSION['user_id']);
    }

    // Метод для создания новой задачи
    public function createTask($user_id, $title, $description) {
        // Подготавливаю SQL-запрос на вставку новой задачи
        $stmt = $this->conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
        // Выполняю запрос с переданными параметрами
        $stmt->execute([$user_id, $title, $description]);
        // Возвращаю идентификатор последней вставленной записи
        return $this->conn->lastInsertId();
    }

    // Метод для удаления задачи
    public function deleteTask($user_id, $task_id) {
        // Подготавливаю SQL-запрос на удаление задачи
        $stmt = $this->conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        // Выполняю запрос с переданными параметрами
        $stmt->execute([$task_id, $user_id]);
        // Возвращаю количество удалённых строк
        return $stmt->rowCount();
    }

    // Метод для обновления задачи
    public function updateTask($user_id, $task_id, $title, $description, $status) {
        // Подготавливаем SQL-запрос на обновление задачи
        $stmt = $this->conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?");
        // Выполняю запрос с переданными параметрами
        $stmt->execute([$title, $description, $status, $task_id, $user_id]);
        // Возвращаю количество обновлённых строк
        return $stmt->rowCount();
    }

    // Метод для получения всех задач пользователя
    public function getTasks($user_id) {
        // Подготавливаю SQL-запрос на выборку всех задач пользователя
        $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE user_id = ?");
        // Выполняю запрос с переданным параметром
        $stmt->execute([$user_id]);
        // Возвращаю все задачи в виде ассоциативного массива
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}