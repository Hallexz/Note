<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'task_manager';
$user = 'root';
$pass = '1234';
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Хэширование пароля
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Проверка существующего пользователя по имени
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        echo "Пользователь с таким username уже существует.";
    } else {
        // Вставка нового пользователя
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);
        
        // Перенаправление на страницу home.html после успешной регистрации
        header("Location: /homenotes");
        exit();
    }
}


