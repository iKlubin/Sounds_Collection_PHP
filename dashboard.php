<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container">
    <h2>Личный кабинет</h2>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }    

    include('functions.php');
    include('db_config.php');

    if (!isUserAuthenticated()) {
        header('Location: login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$user_id]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['is_admin'];

    // Отображение статуса пользователя
    echo '<h2>Добро пожаловать, ' . $_SESSION['username'] . '!</h2>';
    echo '<p>Статус: ' . ($is_admin ? 'Administrator' : 'User') . '</p>';

        echo '<p>Загруженные звуки:</p>';
        $sql_sounds = "SELECT * FROM sounds WHERE user_id = ?";
        $stmt_sounds = $conn->prepare($sql_sounds);
        $stmt_sounds->bind_param("i", $user_id);
        $stmt_sounds->execute();
        $result_sounds = $stmt_sounds->get_result();

        if ($result_sounds->num_rows > 0) {
            echo '<ul>';
            while ($sound = $result_sounds->fetch_assoc()) {
                echo '<li>' . $sound['title'] . ' - ' . $sound['category'] . ' - <a href="listen_sound.php?id=' . $sound['id'] . '">Слушать</a> | <a href="delete_sound.php?id=' . $sound['id'] . '">Удалить</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Загруженных звуков нет.</p>';
        }

        $stmt_sounds->close();
    
    // Закройте соединение с базой данных
    $conn->close();

    $pdo = null;
    ?>
    </div>
</body>
</html>
