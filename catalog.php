<?php
include('functions.php');

include('db_config.php');

if (!isUserAuthenticated()) {
    header('Location: login.php');
    exit();
}

$query = $pdo->prepare("SELECT * FROM sounds ORDER BY category, title");
$query->execute();
$sounds = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sound Catalog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="catalog-container">
    <div class="sound-list">
    <h2 class="text1">Каталог Звуков</h2>
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
                echo '<a href="listen_sound.php?id=' . $sound['id'] . '">Listen</a>';
                echo '<div class="play-icon">&#9654;</div>';
                echo '<div class="sound-details">';
                echo '<div class="sound-title">' . $sound['title'] . '</div>';
                echo '<div class="sound-info">';
                echo '<div class="category-icon">&#9733;</div>';
                echo '<div class="sound-title">' . $sound['category'] . '</div>';
                echo '<div class="download-icon">&#8681;</div>';

                $user_id = $sound['user_id'];
                $user_info = getUserInfo($user_id);

                if ($user_info) {
                    echo '<div class="uploaded-by">Uploaded by: ' . $user_info['username'] . '</div>';
                } else {
                    echo '<div class="uploaded-by">User not found</div>';
                }

                echo '<div class="created-at">Created on: ' . $sound['created_at'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No available sounds in the catalog.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
