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

    // Обработка загрузки файла звука
    $uploadDirectory = 'uploads/'; // Папка, куда будут загружены звуки

    if ($_FILES['sound_file']['error'] == UPLOAD_ERR_OK) {
        $filename = basename($_FILES['sound_file']['name']);
        $uploadFilePath = $uploadDirectory . $filename;

        // Перемещение файла в указанную директорию
        move_uploaded_file($_FILES['sound_file']['tmp_name'], $uploadFilePath);

        // Сохранение информации о звуке в базе данных
        $insertQuery = $pdo->prepare("INSERT INTO sounds (title, category, file_path) VALUES (?, ?, ?)");
        $insertQuery->execute([$soundName, $category, $uploadFilePath]);

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
                    <option value="fire">Applause</option>
                    <option value="city">Animals</option>
                </select>

                <label for="sound_description">Description:</label>
                <textarea id="sound_description" name="sound_description" required></textarea>

                <label for="sound_file">Sound File (max 50MB):</label>
                <input type="file" id="sound_file" name="sound_file" accept=".mp3, .wav" required>

                <button type="submit">Upload Sound</button>
            </form>
        </div>
    </div>
</body>
</html>
