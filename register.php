<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: catalog.php');
    exit();
}

include('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $query->execute([$username]);

    if ($query->rowCount() > 0) {
        $error_message = "Пользователь с таким именем уже существует.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_query = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, ?)");
        $insert_query->execute([$username, $hashed_password, 0]);

        header('Location: login.php');
        exit();
    }
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="register-container">
        <div class="register-box">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h2>Register</h2>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <script src="https://www.google.com/recaptcha/api.js"
                async defer>
            </script>
            <div class="g-recaptcha" data-sitekey="6LdB6k4pAAAAAFopXt70JMU9VwEAz59gAqUl-cvP" style="margin: auto 150px 10px 150px"></div>

                <button type="submit">Register</button>
                <p>Есть аккаунт? <a href="login.php">Войти</a></p>
            </form>
        </div>
    </div>
</body>
</html>
