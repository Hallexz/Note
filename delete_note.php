<?php
// Создаю экземпляр класса TaskManager с параметрами подключения к базе данных
$taskManager = new TaskManager('localhost', 'task_manager', 'root', '1234');

// Проверяю, авторизован ли пользователь
if (!$taskManager->isLoggedIn()) {
    // Если пользователь не авторизован, прекращаю выполнение скрипта с сообщением об ошибке
    die("User not logged in");
}

// Проверяю, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаю идентификатор пользователя из сессии
    $user_id = $_SESSION['user_id'];
    // Получаю идентификатор заметки из POST-запроса
    $note_id = $_POST['note_id'];

    // Использую метод deleteTask для удаления заметки, принадлежащей пользователю
    $result = $taskManager->deleteTask($user_id, $note_id);
    
    // Проверяю, была ли заметка успешно удалена
    if ($result > 0) {
        // Если заметка удалена, вывожу сообщение об успешном удалении
        echo "Note deleted successfully";
    } else {
        // Если заметка не найдена или удаление не удалось, вывожу сообщение об ошибке
        echo "Failed to delete note or note not found";
    }
}