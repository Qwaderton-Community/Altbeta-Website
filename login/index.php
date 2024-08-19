<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход - Altbeta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/style.css?v=3">
</head>
<body>
    <div class="container">
        <a href="/" class="logo"><img src="/altbeta_logo.png" alt="Altbeta" class="logo"></a>
        <div class="tagline">
            <?php
            session_start();
            define('INCLUDE_CHECK', true);
            include $_SERVER['DOCUMENT_ROOT'] . "/connect.php";

            if (isset($_POST['login'])) {
                $login = strtolower(trim($_POST['login']));
                $pass = trim($_POST['passwd']);

                if (empty($login) || empty($pass)) {
                    echo 'Пожалуйста, заполните все поля.';
                } elseif (preg_match("/[^0-9a-zA-Z_-]/", $login)) {
                    echo "Логин введен некорректно.";
                } else {
                    $stmt = $link->prepare("SELECT $db_columnPass FROM $db_table WHERE $db_columnUser = ?");
                    $stmt->bind_param('s', $login);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($hash);
                        $stmt->fetch();
                        if (password_verify($pass, $hash)) {
                            $_SESSION['user'] = $login;
                            header("Location: https://altbeta.qwa.su/account");
                            exit();
                        } else {
                            echo "Неверный логин или пароль.";
                        }
                    } else {
                        echo "Неверный логин или пароль.";
                    }
                    $stmt->close();
                }
            }
            ?>
        </div>
        <div class="actions">
            <form action="" method="post">
                <input class="form-inp" placeholder="Никнейм" type="text" name="login"><br>
                <input class="form-inp" placeholder="Пароль" type="password" name="passwd"><br>
                <input class="form-btn" type="submit" value="Войти">
            </form>
            <a href="/register">Нет аккаунта?</a>
        </div>
    </div>
<footer>
    <p>Altbeta не связана с Mojang или Microsoft.</p>
    <p>© 2024 Команда Altbeta</p>
  </footer>
</body>
</html>
