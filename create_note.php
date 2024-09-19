<?php
// Создаю экземпляр класса TaskManager с параметрами подключения к базе данных
$taskManager = new TaskManager('localhost', 'task_manager', 'root', '1234');

// Проверяю, авторизован ли пользователь
if (!$taskManager->isLoggedIn()) {
    // Если пользователь не авторизован, возвращаю ошибку в формате JSON и прекращаю выполнение скрипта
    die(json_encode(['error' => 'User not logged in']));
}

// Проверяю, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаю идентификатор пользователя из сессии
    $user_id = $_SESSION['user_id'];
    // Получаю заголовок и описание задачи из POST-запроса
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Создаю новую задачу с помощью метода класса TaskManager и получаю её идентификатор
    $noteId = $taskManager->createTask($user_id, $title, $description);
    // Возвращаю JSON-ответ с подтверждением успешного создания задачи и её идентификатором
    echo json_encode(['success' => true, 'message' => 'Note created successfully', 'id' => $noteId]);
} else {
    // Если запрос был отправлен не методом POST, возвращаю ошибку в формате JSON
    echo json_encode(['error' => 'Invalid request method']);
}
?>