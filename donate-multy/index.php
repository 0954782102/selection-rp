<?php
session_start();
require_once __DIR__ . '/../db.php';
$message = '';
$success = false;
$coinName = 'Sel coin';
$currency = 'грн';
$paymentRequestCreated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['name'] ?? '');
    $server = trim($_POST['server'] ?? '');
    $amount = (float)$_POST['money'];

    if ($login === '' || $server === '' || $amount <= 0) {
        $message = 'Будь ласка, заповніть нік, оберіть сервер та введіть суму.';
    } else {
        try {
            $mysqli = get_db_connection();
            $message = 'Підключення до бази даних вдалося. ';

            $paymentTable = find_table($mysqli, ['donation', 'payments', 'payment', 'pay', 'order', 'orders'], null);
            if ($paymentTable) {
                $columns = get_columns($mysqli, $paymentTable);
                $loginCol = find_column($columns, ['login', 'user', 'name', 'nick'], $columns[0] ?? null);
                $amountCol = find_column($columns, ['amount', 'sum', 'money', 'value'], $columns[0] ?? null);
                $serverCol = find_column($columns, ['server', 'srv'], $columns[0] ?? null);
                $currencyCol = find_column($columns, ['currency', 'cur'], $columns[0] ?? null);
                $createdCol = find_column($columns, ['created', 'date', 'time', 'added'], $columns[0] ?? null);

                if ($loginCol && $amountCol) {
                    $insertColumns = [];
                    $insertValues = [];
                    $params = [];
                    $types = '';

                    foreach (['loginCol' => $login, 'amountCol' => $amount, 'serverCol' => $server, 'currencyCol' => $currency, 'createdCol' => date('Y-m-d H:i:s')] as $key => $value) {
                        if (!empty(${$key})) {
                            $insertColumns[] = quote_identifier(${$key});
                            $insertValues[] = '?';
                            $params[] = $value;
                            $types .= is_int($value) || is_float($value) ? 'd' : 's';
                        }
                    }

                    if (count($insertColumns) > 0) {
                        $sql = 'INSERT INTO ' . quote_identifier($paymentTable) . ' (' . implode(', ', $insertColumns) . ') VALUES (' . implode(', ', $insertValues) . ')';
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param($types, ...$params);
                        $stmt->execute();
                        $stmt->close();
                        $paymentRequestCreated = true;
                        $message .= 'Запит на поповнення збережено в базі.';
                    }
                }
            }

            $success = true;
        } catch (Exception $e) {
            $message = 'Помилка підключення до бази даних: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Selection RP — Донат</title>
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
<a class="nav__link active" href="index.php">Донат</a>
<a class="nav__link nav__link-mob" href="../personal/index.php">Особистий кабінет</a>
</nav>
</div>
<a class="logo header__logo col flex a-center" href="../index.html">
<img class="logo__image" src="https://i.postimg.cc/QMQKKq4Z/Gemini-Generated-Image-mbd25lmbd25lmbd2-removebg-preview.png" alt="Selection RP">
<div class="logo__text text-center upper"><div class="logo__text-up">SELECTION</div><div class="logo__text-down">RP</div></div>
</a>
<div class="auth header__auth col">
<a href="../personal/index.php" class="btn btn_trans auth__btn upper">Особистий кабінет</a>
<a href="index.php" class="btn btn_blue auth__btn upper">Поповнити рахунок</a>
</div>
</div>
</div>
</header>
<main class="main">
<section class="form-page section">
<div class="container w-100">
<h2 class="section__title form-page__title text-center" data-back-font="Donate"><span class="section__title-value">Поповнення рахунку</span></h2>
<div class="form-page__caption text-center">Поповнюйте рахунок у гривнях та отримуйте Sel coin для гри.</div>
<?php if ($message !== ''): ?>
<div class="form-page__notice" style="margin-bottom:20px;padding:18px;border-radius:14px;background:#1d1d1d;color:#fff;">
<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
</div>
<?php endif; ?>
<form method="post" action="" class="form-page__form donate-multy">
<div class="group"><input class="input w-100" id="name" type="text" name="name" placeholder="Введіть нік" value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
<div class="group"><div class="select"><select name="server" id="server">
<option value="">Оберіть сервер</option>
<option value="server1" <?php echo (($_POST['server'] ?? '') === 'server1') ? 'selected' : ''; ?>>Сервер 1</option>
</select></div></div>
<div class="group"><input class="input w-100" type="number" name="money" id="money" placeholder="Сума в грн" step="0.01" min="1" value="<?php echo htmlspecialchars($_POST['money'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
<button class="btn btn_blue w-100 form-page__btn">Поповнити</button>
</form>
<div class="form-page__currency text-center" style="margin-top:22px;">
<div class="form-page__currency-line">1 <?php echo $coinName; ?> = 1 <?php echo $currency; ?></div>
<div class="form-page__currency-line">1 <?php echo $coinName; ?> = 1000 ігрової валюти</div>
</div>
<?php if ($success && !$paymentRequestCreated): ?>
<div class="form-page__notice" style="margin-top:16px;padding:18px;border-radius:14px;background:#222;color:#fff;">Платіж прийнято, але таблиця для збереження платежів в базі не знайдена. Зверніться до адміністратора.</div>
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
