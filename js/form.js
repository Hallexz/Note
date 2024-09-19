document.querySelector('form').addEventListener('submit', function(event) {
    const username = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;
  
    let isValid = true;
  
    // Проверка имени пользователя
    if (username.trim() === '') {
      alert('Имя пользователя не может быть пустым.');
      isValid = false;
    } else if (username.length < 3) {
      alert('Имя пользователя должно содержать не менее 3 символов.');
      isValid = false;
    }
  
    // Проверка пароля
    if (password.trim() === '') {
      alert('Пароль не может быть пустым.');
      isValid = false;
    } else if (password.length < 6) {
      alert('Пароль должен содержать не менее 6 символов.');
      isValid = false;
    }
  
    // Предотвратить отправку формы, если есть ошибки
    if (!isValid) {
      event.preventDefault();
    }
  });