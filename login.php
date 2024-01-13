<?php
session_start();

include('functions.php');

include('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $query = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([$username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        header('Location: catalog.php');
        exit();
    } else {
        $error_message = "Неверное имя пользователя или пароль";
    }

    // Проверка reCAPTCHA
    $recaptcha_secret_key = '6LdB6k4pAAAAAL5qYvDM3ZRgviBr2ZIsEsU6npB3';
    $recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_verify_data = [
        'secret' => $recaptcha_secret_key,
        'response' => $recaptcha_response,
    ];

    $recaptcha_verify_options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query($recaptcha_verify_data),
        ],
    ];

    $recaptcha_verify_context = stream_context_create($recaptcha_verify_options);
    $recaptcha_verify_result = file_get_contents($recaptcha_verify_url, false, $recaptcha_verify_context);
    $recaptcha_verify_data = json_decode($recaptcha_verify_result, true);

    if ($recaptcha_verify_data['success']) {
        $query = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $query->execute([$username]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];

            header('Location: catalog.php');
            exit();
        } else {
            $error_message = "Неверное имя пользователя или пароль";
        }
    } else {
        $error_message = "Пожалуйста, подтвердите, что вы не робот.";
    }
}

$query = $pdo->prepare("SELECT * FROM sounds ORDER BY category, title");
$query->execute();
$sounds = $query->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="catalog-container">
        <div class="sound-list">
            <h2 class="text1">Каталог Звуков</h2>
            <h3 class="text1">Для полного доступа к катологу требуется войти в аккаунт</h3>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "sounds_collection";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            function getUserInfo($user_id) {
                global $conn;
                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);

                $stmt->bind_param("i", $user_id);

                $stmt->execute();

                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    $user_info = $result->fetch_assoc();

                    $stmt->close();

                    return $user_info;
                } else {

                    $stmt->close();

                    return false;
                }
            }

            if ($sounds) {
                foreach ($sounds as $sound) {
                    echo '<div class="sound-item">';
                    echo '<div class="sound-details">';
                    echo '<div class="sound-title">' . $sound['title'] . '</div>';
                    echo '<div class="sound-info">';
                    echo '<div class="category-icon">&#9733;</div>';
                    echo '<div class="sound-title">' . $sound['category'] . '</div>';
                    echo '<div class="download-icon">&#8681;</div>';

                    $user_id = $sound['user_id'];
                    $user_info = getUserInfo($user_id);

                    if ($user_info) {
                        echo '<div class="uploaded-by">Загружен: ' . $user_info['username'] . '</div>';
                    } else {
                        echo '<div class="uploaded-by">Неизвестный</div>';
                    }

                    echo '<div class="created-at">Дата: ' . $sound['created_at'] . '</div>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Звуков в каталоге нет.</p>';
            }
            ?>
        </div>
    </div>

<div class="login-container">
    <div class="login-box">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>Вход</h2>

            <label for="username">Логин:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>

            <script src="https://www.google.com/recaptcha/api.js"
                async defer>
            </script>
            <div class="g-recaptcha" data-sitekey="6LdB6k4pAAAAAFopXt70JMU9VwEAz59gAqUl-cvP" style="margin: auto 150px 10px 150px"></div>

            <button type="submit">Войти</button>
            <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
        </form>
    </div>
</div>

<script>
function checkRecaptcha() {
    var response = grecaptcha.getResponse();
    if (response.length === 0) {
        alert("Пожалуйста, подтвердите, что вы не робот.");
        return false;
    }
    return true;
}
</script>

</body>
</html>
