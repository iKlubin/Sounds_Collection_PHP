<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <style></style>
</head>
<body>
    <?php include('header.php'); ?>
    <h2>Личный кабинет</h2>

    <?php
    include('db_config.php');

    $user_id = $_SESSION['user_id'];
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$user_id]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    echo "<p>Добро пожаловать, {$user['username']}!</p>";

    $pdo = null;
    ?>
</body>
</html>
