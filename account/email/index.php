<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена почты - Altbeta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.css?v=3">
</head>
<body>
<div class="container">
    <div class="header">
        <a href="/" class="logo"><img src="/altbeta_logo.png" alt="Altbeta" class="logo"></a>
        <?php
        session_start();
        define('INCLUDE_CHECK', true);
        include $_SERVER['DOCUMENT_ROOT'] . "/connect.php";

        if (!isset($_SESSION['user'])) {
            header("Location: https://altbeta.qwa.su/login");
            exit();
        }

        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);

            if (empty($email)) {
                echo 'Email не может быть пустым.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo 'Email введен некорректно.';
            } else {
                $username = $_SESSION['username'];
                $stmt = $link->prepare("UPDATE $db_table SET $db_columnEmail = '?' WHERE $db_columnUser = '?'");
                $stmt->bind_param('ss', $email, $username);
                if ($stmt->execute()) {
                    echo 'Email успешно обновлен.';
                } else {
                    echo 'Запрос к базе завершился ошибкой.';
                }
                $stmt->close();
            }
        }
        ?>
        <div class="actions">
            <form action="" method="post">
                <input class="form-inp" placeholder="Новый Email" type="email" name="email" required><br>
                <input class="form-btn" type="submit" value="Сменить Email">
            </form>
            <a href="/account">Вернуться в аккаунт</a>
        </div>
    </div>
</div>
<footer>
    <p>Altbeta не связана с Mojang или Microsoft.</p>
    <p>© 2024 Команда Altbeta</p>
</footer>
</body>
</html>
