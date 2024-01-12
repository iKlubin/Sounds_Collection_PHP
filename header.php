<link rel="stylesheet" href="style.css">
<style>
    nav {
        height: 100px;
        width: 100%;
        background-color: #2F27CE;
        display: flex;
        align-items: center;
        justify-content: space-around;
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
    }

    nav ul li {
        margin-right: 20px;
    }

    nav a {
        text-decoration: none;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }
</style>

<nav>
    <ul>
        <?php
        include('functions.php');
        ?>

        <li><a href="dashboard.php">Личный кабинет</a></li>
        <li><a href="catalog.php">Каталог звуков</a></li>
        <li><a href="add_sound.php">Добавить звук</a></li>
        
        <?php
        if (isUserAuthenticated()) {
            echo '<li><a href="logout.php">Выйти</a></li>';
            echo '<li><a>['.$_SESSION['username'].']</a></li>';
        } else {
            echo '<li><a href="login.php">Войти</a></li>';
            echo '<li><a href="register.php">Зарегистрироваться</a></li>';
        }
        ?>
    </ul>
</nav>
