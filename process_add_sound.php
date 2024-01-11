<?php
session_start();

// Проверка, если пользователь не аутентифицирован, перенаправление на страницу входа
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Подключение к базе данных
include('db_config.php');

// Получение данных из формы
$sound_name = $_POST['title'];
$category = $_POST['category'];
$sound_description = $_POST['description'];
$user_id = $_SESSION['user_id'];

// Обработка загрузки файла
$upload_dir = 'sounds/' . $category . '/'; // Папка для загрузки файлов в зависимости от категории
$file_name = $_FILES['sound_file']['name'];
$file_size = $_FILES['sound_file']['size'];
$file_tmp = $_FILES['sound_file']['tmp_name'];

// Проверка на максимальный размер файла (50MB)
$max_size = 50 * 1024 * 1024; // 50MB в байтах
if ($file_size > $max_size) {
    $_SESSION['add_sound_error'] = 'File size exceeds the limit (50MB).';
    header('Location: add_sound.php');
    exit();
}

// Создание уникального имени файла
$unique_name = uniqid() . '_' . $file_name;

// Полный путь к файлу
$upload_path = $upload_dir . $unique_name;

// Перемещение файла в папку назначения
move_uploaded_file($file_tmp, $upload_path);

// Добавление записи в базу данных
$sql = "INSERT INTO sounds (user_id, title, category, description, file_path) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $user_id, $sound_name, $category, $sound_description, $upload_path);
$stmt->execute();
$stmt->close();

// Перенаправление на страницу каталога
header('Location: catalog.php');
exit();
?>
