<?php
session_start();

include('db_config.php');

$sound_id = $_GET['id'];

$sql = "SELECT * FROM sounds WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sound_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sound = $result->fetch_assoc();
} else {
    header('Location: catalog.php');
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listen Sound</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="listen-sound-container">
    <h2>Listen Sound</h2>

    <audio controls>
        <source src="<?php
            echo $sound['file_path'];
        ?>" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <p><?php echo $sound['description']; ?></p>

    <a href="catalog.php">Back to Catalog</a>
</div>

</body>
</html>
