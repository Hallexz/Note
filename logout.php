<?php

function logout() {
  // 1. Уничтожить сессию
  session_start();
  session_destroy();

  // 2. Удаление куки, связанные с сессией
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
  }

  // 3. Перенаправление пользователя на страницу входа (или другую нужную страницу)
  header('Location: login.php'); // Замените login.php на нужную страниц
}

