<?php
// Создаю экземпляр класса TaskManager для управления задачами
$taskManager = new TaskManager('localhost', 'task_manager', 'root', '1234');

// Проверяю, авторизован ли пользователь
if (!$taskManager->isLoggedIn()) {
    // Если пользователь не авторизован, завершаю выполнение скрипта
    die("User not logged in"); 
}

// Проверяю, был ли отправлен POST-запрос
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаю ID пользователя из сессии
    $user_id = $_SESSION['user_id']; 
    // Получаю ID заметки, заголовок, описание и статус из POST-данных
    $note_id = $_POST['note_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Вызываю метод updateTask для обновления заметки
    $result = $taskManager->updateTask($user_id, $note_id, $title, $description, $status); 
    // Проверяю результат обновления
    if ($result > 0) {
        // Если заметка успешно обновлена, вывожу сообщение об успехе
        echo "Note updated successfully"; 
    } else {
        // Иначе вывожу сообщение об ошибке
        echo "Failed to update note or note not found"; 
    }
}