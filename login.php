<?php
session_start();

include('db_config.php');

if (!$pdo) {
    die("Ошибка подключения к базе данных. Пожалуйста, попробуйте позже.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([$username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: catalog.php');
        exit();
    } else {

        $error_message = "Неверное имя пользователя или пароль";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include('db_config.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([$username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: catalog.php');
        exit();
    } else {
        $error_message = "Неверное имя пользователя или пароль";
    }
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h2>Login</h2>

                <?php
                    if (isset($_SESSION['login_error'])) {
                        echo '<div class="error-message">' . $_SESSION['login_error'] . '</div>';
                        unset($_SESSION['login_error']);
                    }
                ?>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Log in</button>
                <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
            </form>
        </div>
    </div>

    <?php
    if (isset($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>

</body>
</html>

