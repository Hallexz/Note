-- Создание базы данных `task_manager`
CREATE DATABASE IF NOT EXISTS task_manager;
USE task_manager;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица задач
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('выполнена', 'не выполнена') DEFAULT 'не выполнена',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Индексы для оптимизации запросов
CREATE INDEX idx_user_id ON tasks(user_id);

-- Опционально: добавление тестового пользователя
INSERT INTO users (username, password) VALUES ('test_user', '$2y$10$wJx9x3G.qM7m4E6f1hZ0D.3XH1cJXaB2z3F0K8F9Fz5Hq9h8qC3W2'); -- Пароль: test1234

-- Опционально: добавление тестовой задачи для пользователя
INSERT INTO tasks (user_id, title, description, status) VALUES 
(1, 'Тестовая задача', 'Это описание тестовой задачи', 'не выполнена');