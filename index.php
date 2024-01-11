<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <?php
    include('functions.php');

    if (isUserAuthenticated()) {
        include('catalog.php');
    } else {
        redirectToLogin();
    }
    ?>
</body>
</html>
