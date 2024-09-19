// При загрузке DOM, загружаю заметки
document.addEventListener('DOMContentLoaded', function() {
    loadNotes();
});

// При нажатии на кнопку "Добавить заметку", открываю модальное окно
document.querySelector('.add-note').addEventListener('click', function() {
    const newNoteModal = new bootstrap.Modal(document.getElementById('newNoteModal'));
    newNoteModal.show();
});

// Функция для загрузки заметок с сервера
function loadNotes() {
    // Делаю запрос к файлу get_notes.php
    fetch('/get_notes.php')
        .then(response => response.json()) 
        // Получаю данные заметок в формате JSON
        .then(notes => {
            const notesContainer = document.getElementById('notes-container');
            // Очищаю контейнер для заметок перед добавлением новых
            notesContainer.innerHTML = ''; 
            // Перебираю массив заметок
            notes.forEach(note => {
                // Создаю элемент заметки с помощью функции createNoteElement
                const noteElement = createNoteElement(note); 
                // Добавляю созданный элемент в контейнер
                notesContainer.appendChild(noteElement); 
            });
        })
        // Обработка ошибок при загрузке заметок
        .catch(error => console.error('Error:', error)); 
}

// Функция для создания HTML-элемента заметки
function createNoteElement(note) {
    // Создаю новый div для заметки
    const newNote = document.createElement('div'); 
    // Устанавливаю класс для стилизации
    newNote.className = 'col'; 
    // Формирую HTML-разметку заметки
    newNote.innerHTML = `
        <div class="card note" data-note-id="${note.id}">
            <div class="card-body">
                <h5 class="card-title">${note.title}</h5>
                <p class="card-text note-description">${note.description}</p>
                <div class="btn-group">
                    <button class="btn-edit">Edit</button>
                    <button class="btn-delete">Delete</button>
                </div>
            </div>
        </div>
    `;
    
    // Вешаю обработчик события на кнопку "Изменить"
    newNote.querySelector('.btn-edit').addEventListener('click', function() {
        editNote(newNote); 
    });
    
    // Вешаю обработчик события на кнопку "Удалить"
    newNote.querySelector('.btn-delete').addEventListener('click', function() {
        deleteNote(newNote); 
    });
    
    // Возвращаю созданный элемент заметки
    return newNote; 
}

// Функция для удаления заметки
function deleteNote(note) {
    // Получаю ID заметки из атрибута data-note-id
    const noteId = note.querySelector('.card').dataset.noteId; 
    // Делаю запрос к файлу delete_note.php для удаления заметки
    fetch('/delete_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `note_id=${noteId}`
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        // Удаляю элемент заметки из DOM
        note.remove(); 
    })
    .catch(error => console.error('Error:', error));
}

// Функция для редактирования заметки
function editNote(note) {
    // Получаю элементы заголовка и описания заметки
    const cardBody = note.querySelector('.card-body');
    const title = cardBody.querySelector('.card-title');
    const description = cardBody.querySelector('.card-text');
    const noteId = note.querySelector('.card').dataset.noteId;

    // Создаю поля ввода для заголовка и описания
    const titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.className = 'form-control';
    titleInput.value = title.textContent;

    const descriptionInput = document.createElement('textarea');
    descriptionInput.className = 'form-control';
    descriptionInput.value = description.textContent;

    // Очищаю содержимое card-body и добавляю поля ввода
    cardBody.innerHTML = '';
    cardBody.appendChild(titleInput);
    cardBody.appendChild(descriptionInput);

    // Создаю кнопку "Сохранить"
    const saveBtn = document.createElement('button');
    saveBtn.textContent = 'Сохранить';
    saveBtn.className = 'btn btn-primary me-2';
    // Вешаю обработчик события на кнопку "Сохранить"
    saveBtn.addEventListener('click', function() {
        // Отправляю запрос на сервер для обновления заметки
        fetch('/update_note.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `note_id=${noteId}&title=${encodeURIComponent(titleInput.value)}&description=${encodeURIComponent(descriptionInput.value)}&status=не выполнена` 
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            // После обновления, перезагружаю заметки
            loadNotes(); 
        })
        .catch(error => console.error('Error:', error));
    });

    // Создаю кнопку "Отмена"
    const cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Отмена';
    cancelBtn.className = 'btn btn-secondary';
    // Вешаю обработчик события на кнопку "Отмена"
    cancelBtn.addEventListener('click', function() {
        // При отмене, перезагружаю заметки
        loadNotes(); 
    });

    // Добавляю кнопки "Сохранить" и "Отмена" в card-body
    cardBody.appendChild(saveBtn);
    cardBody.appendChild(cancelBtn);
}
// Обработчик события для кнопки "Создать заметку" в модальном окне
document.getElementById('createNoteBtn').addEventListener('click', function() {
    // Получаю значения из полей ввода заголовка и описания
    const noteTitle = document.getElementById('noteTitle').value;
    const noteDescription = document.getElementById('noteDescription').value;

    // Отправляю запрос на сервер для создания новой заметки
    fetch('/create_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `title=${encodeURIComponent(noteTitle)}&description=${encodeURIComponent(noteDescription)}`
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        // После создания, перезагружаю заметки
        loadNotes(); 
        
        // Очищаю поля формы после создания заметки
        document.getElementById('noteTitle').value = '';
        document.getElementById('noteDescription').value = '';

        // Закрытие модального окна после создания заметки
        const newNoteModal = bootstrap.Modal.getInstance(document.getElementById('newNoteModal'));
        newNoteModal.hide();
    })
    .catch(error => console.error('Error:', error));
});


// Функция для выхода из системы
function logout() {
    // Отправляю запрос на сервер для выхода
    fetch('/logout.php')
    .then(response => response.text())
    .then(result => {
        console.log(result);
        // Перенаправляю на страницу входа
        window.location.href = 'index.html'; 
    })
    .catch(error => console.error('Error:', error));
} 