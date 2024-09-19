<?php
// Создаю экземпляр класса TaskManager с параметрами подключения к базе данных
$taskManager = new TaskManager('localhost', 'task_manager', 'root', '1234');

// Проверяю, авторизован ли пользователь
if (!$taskManager->isLoggedIn()) {
    // Если пользователь не авторизован, возвращаю ошибку в формате JSON и прекращаю выполнение скрипта
    die(json_encode(['error' => 'User not logged in']));
}

// Получаю идентификатор пользователя из сессии
$user_id = $_SESSION['user_id'];

// Получаю список задач пользователя с помощью метода getTasks
$notes = $taskManager->getTasks($user_id);

// Возвращаю список задач в формате JSON
echo json_encode($notes);