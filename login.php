<?php
// Подключение к базе данных
session_start(); // Стартуем сессию

// Подключение к базе данных
$host = 'localhost';
$db = 'task_manager';
$user = 'root';
$pass = '1234';
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Получаем пользователя по имени пользователя
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверяем, существует ли пользователь и совпадает ли пароль
    if ($user && password_verify($password, $user['password'])) {
        // Сохраняем данные о пользователе в сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Перенаправление на защищённую страницу
        header("Location: ../home.html");
        exit();
    } else {
        echo "Неверное имя пользователя или пароль.";
    }
}