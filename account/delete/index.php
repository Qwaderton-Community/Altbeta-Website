<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Аккаунт Altbeta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/style.css?v=3">
</head>
<body>
<div class="container">
    <form method="post" action="">
        <input class="btn exit-btn" type="submit" name="logout" value="Выйти">
    </form>
    <div class="header">
        <a href="/" class="logo"><img src="/altbeta_logo.png" alt="Altbeta" class="logo"></a>
        <div class="tagline">Информация об аккаунте</div>
        <div class="info">
            <?php
            session_start();
            define('INCLUDE_CHECK', true);
            include $_SERVER['DOCUMENT_ROOT'] . "/connect.php";

            if (!isset($_SESSION['user'])) {
                header("Location: https://altbeta.qwa.su/login");
                exit();
            }

            $login = $_SESSION['user'];

            $stmt = $link->prepare("SELECT $db_columnUser FROM $db_table WHERE $db_columnUser = ?");
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Вы действительно хотите удалить аккаунт <b>" . htmlspecialchars($login) . "</b>?<br>Это действие нельзя отменить!";
            } else {
                echo "Аккаунт не найден.";
            }
            $stmt->close();
            ?>
        </div>
        <br>
        <form method="post" action="">
            <input class="warn-btn" type="submit" name="delete" value="Удалить аккаунт">
        </form>
        <?php
        if (isset($_POST['delete'])) {
            $stmt = $link->prepare("DELETE FROM $db_table WHERE $db_columnUser = ?");
            $stmt->bind_param('s', $login);
            if ($stmt->execute()) {
                session_destroy();
                header("Location: https://altbeta.qwa.su/login");
                exit();
            } else {
                echo "Ошибка при удалении аккаунта.";
            }
            $stmt->close();
        }
        ?>
        <?php
        if (isset($_POST['logout'])) {
            session_destroy();
            unset($_POST['logout']);
            header('Location: /login');
        }
        ?>
    </div>
</div>
<footer>
    <p>Altbeta не связана с Mojang или Microsoft.</p>
    <p>© 2024 Команда Altbeta</p>
  </footer>
</body>
</html>
