<?php
session_start();

// Проверка аутентификации пользователя и его статуса администратора
include('functions.php');
include('db_config.php');

if (!isUserAuthenticated() || !isAdmin()) {
    header('Location: catalog.php');
    exit();
}

// Получение id звука из параметра в URL
$sound_id = $_GET['id'];

// Проверка, существует ли звук с указанным id
$sql_check = "SELECT * FROM sounds WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $sound_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    // Если звук не найден, выполните соответствующие действия (например, перенаправление на страницу каталога)
    header('Location: catalog.php');
    exit();
}

$stmt_check->close();

// Удаление звука из базы данных
$sql_delete = "DELETE FROM sounds WHERE id = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $sound_id);
$stmt_delete->execute();
$stmt_delete->close();

// После успешного удаления звука перенаправление на страницу каталога
header('Location: catalog.php');
exit();
?>
