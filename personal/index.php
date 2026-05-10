<?php
session_start();
require_once __DIR__ . '/../db.php';
$message = '';
$userData = null;
$loggedIn = false;

if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

try {
    $mysqli = get_db_connection();
    if (isset($_SESSION['user'])) {
        $loggedIn = true;
        $userData = $_SESSION['user'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['pass'] ?? '';

        if ($login === '' || $password === '') {
            $message = 'Введіть нік та пароль.';
        } else {
            $userTable = find_table($mysqli, ['user', 'account', 'player', 'auth'], null);
            if (!$userTable) {
                $message = 'Таблиця користувачів не знайдена в базі.';
            } else {
                $columns = get_columns($mysqli, $userTable);
                $loginCol = find_column($columns, ['login', 'username', 'nick', 'nickname', 'user'], null);
                $passCol = find_column($columns, ['pass', 'password', 'pwd'], null);
                $balanceCol = find_column($columns, ['balance', 'cash', 'money', 'amount', 'score', 'sum'], null);

                if (!$loginCol || !$passCol) {
                    $message = 'Не знайдено полів логіну або паролю в таблиці користувачів.';
                } else {
                    $sql = 'SELECT * FROM ' . quote_identifier($userTable) . ' WHERE ' . quote_identifier($loginCol) . ' = ? LIMIT 1';
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('s', $login);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    $stmt->close();

                    if (!$user) {
                        $message = 'Користувача не знайдено.';
                    } else {
                        $storedPassword = $user[$passCol];
                        $verified = false;
                        if (password_verify($password, $storedPassword)) {
                            $verified = true;
                        } elseif (hash_equals((string)$storedPassword, $password)) {
                            $verified = true;
                        } elseif (hash_equals((string)$storedPassword, md5($password))) {
                            $verified = true;
                        } elseif (hash_equals((string)$storedPassword, sha1($password))) {
                            $verified = true;
                        }

                        if ($verified) {
                            $loggedIn = true;
                            $userData = [
                                'name' => $user[$loginCol],
                                'balance' => $balanceCol ? $user[$balanceCol] : null,
                                'server' => $_POST['server'] ?? 'Сервер 1',
                            ];
                            $_SESSION['user'] = $userData;
                        } else {
                            $message = 'Неправильний пароль.';
                        }
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    if ($message === '') {
        $message = 'Помилка підключення до бази даних: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Selection RP — Особистий кабінет</title>
<link rel="stylesheet" href="../wp-content/themes/smartrp/style.css">
</head>
<body>
<header class="header">
<div class="container">
<div class="row-flex header__row j-between a-center">
<div class="navbar header__navbar upper col">
<button class="btn burger"><span></span><span></span><span></span></button>
<nav class="nav">
<a class="nav__link" href="../index.html">Головна</a>
<a class="nav__link" href="../index.html#news">Новини</a>
<a class="nav__link" href="../rate/index.php">Рейтинг</a>
<a class="nav__link" href="../donate-multy/index.php">Донат</a>
<a class="nav__link active" href="index.php">Особистий кабінет</a>
</nav>
</div>
<a class="logo header__logo col flex a-center" href="../index.html">
<img class="logo__image" src="https://i.postimg.cc/QMQKKq4Z/Gemini-Generated-Image-mbd25lmbd25lmbd2-removebg-preview.png" alt="Selection RP">
<div class="logo__text text-center upper"><div class="logo__text-up">SELECTION</div><div class="logo__text-down">RP</div></div>
</a>
<div class="auth header__auth col">
<a href="../donate-multy/index.php" class="btn btn_blue auth__btn upper">Поповнити рахунок</a>
</div>
</div>
</div>
</header>
<main class="main">
<section class="form-page section">
<div class="container w-100">
<h2 class="section__title form-page__title text-center" data-back-font="User"><span class="section__title-value">Особистий кабінет</span></h2>
<div class="form-page__caption text-center">Увійдіть, щоб перевірити свій баланс та статус.</div>
<?php if ($message !== ''): ?>
<div class="form-page__notice" style="margin-bottom:20px;padding:18px;border-radius:14px;background:#1d1d1d;color:#fff;">
<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
</div>
<?php endif; ?>
<?php if ($loggedIn && $userData !== null): ?>
<div class="account-card" style="padding:28px;border-radius:24px;background:rgba(255,255,255,.06);max-width:520px;margin:0 auto;color:#fff;">
<h3 style="margin-bottom:12px;">Вітаємо, <?php echo htmlspecialchars($userData['name'], ENT_QUOTES, 'UTF-8'); ?>!</h3>
<p>Сервер: <?php echo htmlspecialchars($userData['server'], ENT_QUOTES, 'UTF-8'); ?></p>
<p>Баланс: <?php echo $userData['balance'] !== null ? htmlspecialchars(number_format((float)$userData['balance'], 0, '.', ' '), ENT_QUOTES, 'UTF-8') . ' Sel coin' : 'Немає даних про баланс'; ?></p>
<form method="post" action="">
<input type="hidden" name="action" value="logout">
<button class="btn btn_trans w-100" type="submit">Вийти</button>
</form>
</div>
<?php else: ?>
<form action="" method="post" class="form-page__form auth-form">
<input type="hidden" name="action" value="login">
<div class="group"><div class="select"><select name="server" id="server">
<option value="">Оберіть сервер</option>
<option value="server1" <?php echo (($_POST['server'] ?? '') === 'server1') ? 'selected' : ''; ?>>Сервер 1</option>
</select></div></div>
<div class="group"><input class="input w-100" id="name" type="text" name="login" placeholder="Введіть нік" value="<?php echo htmlspecialchars($_POST['login'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
<div class="group"><input class="input w-100" type="password" name="pass" placeholder="Введіть пароль"></div>
<button class="btn btn_blue w-100 form-page__btn">Увійти</button>
</form>
<?php endif; ?>
</div>
</section>
</main>
<footer class="footer text-center">
<div class="container">
<a class='logo footer-logo flex-inline a-center' href="../index.html"><img class='logo__image footer-logo__image' src="https://i.postimg.cc/QMQKKq4Z/Gemini-Generated-Image-mbd25lmbd25lmbd2-removebg-preview.png" alt="Selection RP">
<div class="logo__text text-center upper"><div class="logo__text-up footer-logo__text-up">SELECTION</div><div class="logo__text-down footer-logo__text-down">RP</div></div></a>
<div class="footer__links flex"><a href="../docs/privacy-policy and personal-data.pdf">Політика конфіденційності та згода на обробку даних</a><a href="../docs/return_info.pdf">Політика повернення</a></div>
<div class="footer__copyright copyright">Copyright © 2021 Selection RP</div>
<div class="footer__directors">Головний Технічний директор Selection RP Артем Процко та Головний директор Ярослав Куриленко</div>
</div>
</footer>
</body>
</html>
