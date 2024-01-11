<?php

// Проверка аутентификации пользователя
include('functions.php');

// Подключение к базе данных
include('db_config.php');

// Проверка, если пользователь не аутентифицирован, перенаправление на страницу входа
if (!isUserAuthenticated()) {
    header('Location: login.php');
    exit();
}

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $soundName = $_POST['title'];
    $category = $_POST['category'];
    $sound_description = $_POST['description'];
    $user_id = $_SESSION['user_id'];

    // Обработка загрузки файла
    $file_name = $_FILES['sound_file']['name'];
    $file_size = $_FILES['sound_file']['size'];
    $file_tmp = $_FILES['sound_file']['tmp_name'];

    $max_size = 50 * 1024 * 1024; // 50MB в байтах
    if ($file_size > $max_size) {
        $_SESSION['add_sound_error'] = 'File size exceeds the limit (50MB).';
        header('Location: add_sound.php');
        exit();
    }

    // Создание уникального имени файла
    $unique_name = uniqid().'_'.$file_name;

    // Полный путь к файлу
    $upload_path = 'sounds/'.lcfirst($category).'/'.$unique_name;

    if ($_FILES['sound_file']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES['sound_file']['name']);
        $uploadFilePath = $uploadDirectory.$filename;

        // Перемещение файла в папку назначения
        move_uploaded_file($file_tmp, $upload_path);

        // Сохранение информации о звуке в базе данных
        $insertQuery = $pdo->prepare("INSERT INTO sounds (category, title, file_path, user_id, description) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->execute([$category, $soundName, $upload_path, $user_id, $sound_description]);

        // Редирект на страницу каталога звуков
        header('Location: catalog.php');
        exit();
    } else {
        $error_message = "Ошибка при загрузке файла.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sound</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include('header.php'); ?>

    <div class="add-sound-container">
        <div class="add-sound-box">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <h2>Add Sound</h2>

                <?php

                if (isset($_SESSION['add_sound_error'])) {
                    echo '<div class="error-message">' . $_SESSION['add_sound_error'] . '</div>';
                    unset($_SESSION['add_sound_error']); // Очистка ошибки после отображения
                }
                ?>

                <label for="title">Sound Name:</label>
                <input type="text" id="title" name="title" required>

                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="fire">Fire</option>
                    <option value="city">City</option>
                    <option value="applause">Applause</option>
                    <option value="animals">Animals</option>
                </select>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="sound_file">Sound File (max 50MB):</label>
                <input type="file" id="sound_file" name="sound_file" accept=".mp3, .wav" required>

                <button type="submit">Upload Sound</button>
            </form>
        </div>
    </div>
</body>
</html>
