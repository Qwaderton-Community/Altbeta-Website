<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Altbeta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/style.css?v=3">
</head>
<body>
<div class="container">
    <div class="header">
    <a href="/" class="logo"><img src="/altbeta_logo.png" alt="Altbeta" class="logo"></a>
            <?php
            define('INCLUDE_CHECK', true);
            include $_SERVER['DOCUMENT_ROOT'] . "/connect.php";
                if (isset($_POST['login'])) {
                    $login = $_POST['login'];
                    $email = $_POST['email'];
                    $pass = $_POST['passwd'];
                    $repass = $_POST['repasswd'];

                    $login = strtolower(trim($login));
                    $email = trim($email);
                    $pass = trim($pass);
                    $repass = trim($repass);

                    if (empty($login) || empty($email) || empty($pass) || empty($repass)) {
                        echo 'Не все поля заполнены.';
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo "Email введен некорректно.";
                    } elseif (preg_match("/[^0-9a-zA-Z_-]/", $login)) {
                        echo "Логин введен не корректно.";
                    } elseif (preg_match("/[^0-9a-zA-Z_-]/", $pass)) {
                        echo "Пароль введен не корректно.";
                    } elseif (preg_match("/[^0-9a-zA-Z_-]/", $repass)) {
                        echo "Повтор пароля введен не корректно.";
                    } else {
                        $login_proverka = $link->prepare("SELECT $db_columnUser FROM $db_table WHERE $db_columnUser = ?");
                        $login_proverka->bind_param('s', $login);
                        $login_proverka->execute();
                        $login_proverka->store_result();

                        if ($login_proverka->num_rows > 0) {
                            echo "Аккаунт <b>$login</b> уже существует.";
                        } elseif ((strlen($login) < 4) || (strlen($login) > 15)) {
                            echo "Логин должен содержать не меньше 4 символов и не больше 15.";
                        } elseif ((strlen($pass) < 4) || (strlen($pass) > 31)) {
                            echo "Пароль должен содержать не меньше 4 символов и не больше 31.";
                        } elseif ($pass !== $repass) {
                            echo "Пароли не совпадают.";
                        } else {
                            $hash = password_hash($pass, PASSWORD_BCRYPT);
                            $stmt = $link->prepare("INSERT INTO $db_table ($db_columnUser, $db_columnEmail, $db_columnPass) VALUES ('?', '?', '?')");
                            $stmt->bind_param('sss', $login, $email, $hash);
                            if ($stmt->execute()) {
                                echo "Аккаунт <b>$login</b> успешно зарегистрирован.";
                            } else {
                                echo "Запрос к базе завершился ошибкой.";
                            }
                        }
                        $login_proverka->close();
                    }
                }
            ?>
        <div class="actions">
            <form action="" method="post">
                <input class="form-inp" placeholder="Никнейм" type="text" name="login" minlength="4" maxlength="15"><br>
                <input class="form-inp" placeholder="Почта" type="email" name="email"><br>
                <input class="form-inp" placeholder="Пароль" type="password" name="passwd" minlength="4" maxlength="31"><br>
                <input class="form-inp" placeholder="Повторите пароль" type="password" name="repasswd" minlength="4" maxlength="31"><br>
                <input class="form-btn" type="submit" value="Зарегистрироваться">
            </form>
            <a href="/login">Уже зарегистрированы?</a>
        </div>
    </div> 
</div>
<footer>
    <p>Altbeta не связана с Mojang или Microsoft.</p>
    <p>© 2024 Команда Altbeta</p>
  </footer>
</body>
</html>
