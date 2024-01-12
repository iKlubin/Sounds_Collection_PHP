<?php
session_start();

include('db_config.php');

$sound_id = $_GET['id'];

$sql_sound = "SELECT * FROM sounds WHERE id = ?";
$stmt_sound = $conn->prepare($sql_sound);
$stmt_sound->bind_param("i", $sound_id);
$stmt_sound->execute();
$result_sound = $stmt_sound->get_result();

if ($result_sound->num_rows > 0) {
    $sound = $result_sound->fetch_assoc();

    // Получение информации о пользователе
    $user_id = $sound['user_id'];
    $sql_user = "SELECT username FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $users = $result_user->fetch_assoc();
        // Теперь у вас есть информация о пользователе
    }

    $stmt_user->close();
} else {
    header('Location: catalog.php');
    exit();
}

$stmt_sound->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listen Sound</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .sound-details {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sound-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        audio {
            width: 100%;
            margin-bottom: 20px;
        }

        .info {
            color: #555;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>


<?php include('header.php'); ?>

<div class="container">
    <div class="sound-details">
        <h2>Подробная информация</h2>
        <div class="sound-title"><?php echo $sound['title']; ?></div>
        <audio controls>
        <source src="<?php
            echo $sound['file_path'];
        ?>" type="audio/mpeg">
            Браузер не поддерживает аудио.
        </audio>
        <div class="info">
            <p>Категория: <?php echo $sound['category']; ?></p>
            <p>Описание: <?php echo $sound['description']; ?></p>
            <?php
            if (isset($users['username'])) {
                echo '<p>Загрузил: ' . $users['username'] . '</p>';
            }
            ?>
            <p>Дата: <?php echo $sound['created_at']; ?></p>
        </div>
        <a href="catalog.php" class="back-link">Назад к каталогу</a>
    </div>
</div>

</body>
</html>
