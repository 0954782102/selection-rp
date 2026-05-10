<?php
require_once __DIR__ . '/../db.php';
$players = [];
$error = '';
$sourceTable = '';
$displayName = 'name';
$displayValue = 'value';
try {
    $mysqli = get_db_connection();

    $tables = [];
    $result = $mysqli->query("SHOW TABLES");
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $tables[] = $row[0];
    }
    $result->free();

    if (count($tables) === 0) {
        throw new Exception('У базі даних не знайдено жодної таблиці.');
    }

    foreach ($tables as $tableName) {
        if (stripos($tableName, 'player') !== false || stripos($tableName, 'user') !== false || stripos($tableName, 'account') !== false) {
            $sourceTable = $tableName;
            break;
        }
    }
    if ($sourceTable === '') {
        $sourceTable = $tables[0];
    }

    $columns = get_columns($mysqli, $sourceTable);

    $findColumn = function(array $columns, array $keywords, ?string $fallback = null) {
        foreach ($keywords as $keyword) {
            foreach ($columns as $column) {
                if (stripos($column, $keyword) !== false) {
                    return $column;
                }
            }
        }
        return $fallback;
    };

    $displayName = $findColumn($columns, ['nickname', 'nick', 'login', 'username', 'user', 'name'], $columns[0] ?? 'name');
    $displayValue = $findColumn($columns, ['balance', 'cash', 'money', 'score', 'amount', 'value', 'sum'], $columns[0] ?? 'value');

    if (!in_array($displayName, $columns, true) || !in_array($displayValue, $columns, true)) {
        throw new Exception('Не знайдено підходящих стовпців для імені та балансу в таблиці "' . htmlspecialchars($sourceTable, ENT_QUOTES, 'UTF-8') . '".');
    }

    $query = "SELECT `{$displayName}`, `{$displayValue}` FROM `{$sourceTable}` ORDER BY `{$displayValue}` DESC LIMIT 10";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }
    $result->free();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="yandex-verification" content="d1d0f47634c9bdaf">
<title>Selection RP — рейтинг</title>
<meta name="theme-color" content="#fff">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="apple-touch-icon" sizes="180x180" href="https://smartrp.by/wp-content/themes/smartrp/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="https://smartrp.by/wp-content/themes/smartrp/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="https://smartrp.by/wp-content/themes/smartrp/favicon-16x16.png">
<link rel="manifest" href="https://smartrp.by/wp-content/themes/smartrp/site.webmanifest">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="..\wp-content\themes\smartrp\style.css">
<script type="text/javascript">
        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
        ym(57618034, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    </script>
<noscript>
        <div><img src="https://mc.yandex.ru/watch/57618034" style="position:absolute; left:-9999px;" alt=""></div>
    </noscript>
<link rel='dns-prefetch' href='//s.w.org'>
<script type="text/javascript">
            window._wpemojiSettings = {"baseUrl":"https:\/\/s.w.org\/images\/core\/emoji\/12.0.0-1\/72x72\/","ext":".png","svgUrl":"https:\/\/s.w.org\/images\/core\/emoji\/12.0.0-1\/svg\/","svgExt":".svg","source":{"concatemoji":"https:\/\/smartrp.by\/wp-includes\/js\/wp-emoji-release.min.js?ver=5.3.10"}};
            !function(e,a,t){var n,r,o,i=a.createElement("canvas"),p=i.getContext&&i.getContext("2d");function s(e,t){var a=String.fromCharCode;p.clearRect(0,0,i.width,i.height),p.fillText(a.apply(this,e),0,0);e=i.toDataURL();return p.clearRect(0,0,i.width,i.height),p.fillText(a.apply(this,t),0,0),e===i.toDataURL()}function c(e){var t=a.createElement("script");t.src=e,t.defer=t.type="text/javascript",a.getElementsByTagName("head")[0].appendChild(t)}for(o=Array("flag","emoji"),t.supports={everything:!0,everythingExceptFlag:!0},r=0;r<o.length;r++)t.supports[o[r]]=function(e){if(!p||!p.fillText)return!1;switch(p.textBaseline="top",p.font="600 32px Arial",e){case"flag":return s([127987,65039,8205,9895,65039],[127987,65039,8203,9895,65039])?!1:!s([55356,56826,55356,56819],[55356,56826,8203,55356,56819])&&!s([55356,57332,56128,56423,56128,56418,56128,56421,56128,56430,56128,56423,56128,56447],[55356,57332,8203,56128,56423,8203,56128,56418,8203,56128,56421,8203,56128,56430,8203,56128,56423,8203,56128,56447]);case"emoji":return!s([55357,56424,55356,57342,8205,55358,56605,8205,55357,56424,55356,57340],[55357,56424,55356,57342,8203,55358,56605,8203,55357,56424,55356,57340])}return!1}(o[r]),t.supports.everything=t.supports.everything&&t.supports[o[r]],"flag"!==o[r]&&(t.supports.everythingExceptFlag=t.supports.everythingExceptFlag&&t.supports[o[r]]);t.supports.everythingExceptFlag=t.supports.everythingExceptFlag&&!t.supports.flag,t.DOMReady=!1,t.readyCallback=function(){t.DOMReady=!0},t.supports.everything||(n=function(){t.readyCallback()},a.addEventListener?(a.addEventListener("DOMContentLoaded",n,!1),e.addEventListener("load",n,!1)):(e.attachEvent("onload",n),a.attachEvent("onreadystatechange",function(){"complete"===a.readyState&&t.readyCallback()})),(n=t.source||{}).concatemoji?c(n.concatemoji):n.wpemoji&&n.twemoji&&(c(n.twemoji),c(n.wpemoji))}(window,document,window._wpemojiSettings);
        </script>
<style type="text/css">
img.wp-smiley,
img.emoji {
	display: inline !important;
	border: none !important;
	box-shadow: none !important;
	height: 1em !important;
	width: 1em !important;
	margin: 0 .07em !important;
	vertical-align: -0.1em !important;
	background: none !important;
	padding: 0 !important;
}
</style>
<link rel='stylesheet' id='wp-block-library-css' href='..\wp-includes\css\dist\block-library\style.min.css?ver=5.3.10' type='text/css' media='all'>
<link rel='https://api.w.org/' href='..\wp-json\index.json'>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="..\xmlrpc.xml?rsd">
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="..\wp-includes\wlwmanifest.xml">
<meta name="generator" content="WordPress 5.3.10">
<link rel="canonical" href="index.html">
<link rel='shortlink' href='index.html?p=300'>
<link rel="alternate" type="application/json+oembed" href="..\wp-json\oembed\1.0\embed.json?url=https%3A%2F%2Fsmartrp.by%2Frate%2F">
<link rel="alternate" type="text/xml+oembed" href="..\wp-json\oembed\1.0\embed.xml?url=https%3A%2F%2Fsmartrp.by%2Frate%2F&#038;format=xml">
<link rel="icon" href="..\wp-content\uploads\2020\02\sicon.png" sizes="32x32">
<link rel="icon" href="..\wp-content\uploads\2020\02\sicon.png" sizes="192x192">
<link rel="apple-touch-icon-precomposed" href="..\wp-content\uploads\2020\02\sicon.png">
<meta name="msapplication-TileImage" content="https://smartrp.by/wp-content/uploads/2020/02/sicon.png">
</head>
<body>
<audio autoplay loop>
<source src="https://www.dropbox.com/scl/fi/h6vxzumnfxgw6furtrush/Midnight_Pavement.mp3?rlkey=4jckdnncpt90furen4x81jon7&st=oht8495t&dl=1" type="audio/mpeg">
</audio>
<header class="header">
<div class="container">
<div class="row-flex header__row j-between a-center">

<div class="navbar header__navbar upper col">
<button class="btn burger">
<span></span>
<span></span>
<span></span>
</button>
<nav class="nav">
<a class="nav__link " href="../index.html">Головна</a><a class="nav__link " href="../index.html#news">Новини</a><a class="nav__link " href="http://forum.smartrp.by">Форум</a><a class="nav__link active" href="index.php">Рейтинг</a><a class="nav__link  nav__link-mob" href="../donate-multy/index.php">Донат</a><a class="nav__link  nav__link-mob" href="../personal/index.php">Особистий кабінет</a> </nav>
</div>

<a class='logo header__logo col flex a-center' href="../index.html">
<img class='logo__image' src="https://i.postimg.cc/QMQKKq4Z/Gemini-Generated-Image-mbd25lmbd25lmbd2-removebg-preview.png" alt="Selection RP">
<div class="logo__text text-center upper">
<div class="logo__text-up">SELECTION</div>
<div class="logo__text-down">RP</div>
</div>
</a>

<div class="auth header__auth col">
<a href='../personal/index.php' class="btn btn_trans auth__btn upper">Особистий кабінет</a>
<a href='../donate-multy/index.php' class="btn btn_blue auth__btn upper">Поповнити рахунок</a>
</div>
</div>
</div>
</header>

<main class="main">

<section class="form-page section">
<ul class='scene hidden-md'>
<li class='scene__layer' data-depth="0.2">
<img src="..\wp-content\themes\smartrp\img\form-page-bubles.png" alt="" class='jumb__bubble'>
</li>
</ul>
<div class="container w-100">
<h2 class="section__title rating__title text-center" data-back-font='Rating'>
<span class="section__title-value">Найбагатші гравці</span>
</h2>
<div class="rating">
<div class="rating-tabs">
<div class="rating-tabs__item active" data-tab-target='1'>Сервер 1</div>


</div>
<div class="rating-tab active" data-tab='1' style='display: block;'>
<div class="rating-main">
<?php if ($error !== ''): ?>
<div class="rating-main__item rating-main__first" style="width:100%;background-color:#222;box-shadow:0 0 30px rgba(0,0,0,.2);padding:30px;text-align:center;margin:auto;">
<div class="rating-main__name">Помилка з БД</div>
<div class="rating-main__value" style="font-size:18px;color:#f1f1f1;line-height:1.5;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
</div>
<?php else: ?>
<?php foreach (array_slice($players, 0, 3) as $index => $player): ?>
<?php
$position = $index + 1;
$classes = ['rating-main__first', 'rating-main__second', 'rating-main__third'];
$blockClass = $classes[$index] ?? 'rating-main__item';
?>
<div class="rating-main__item <?php echo $blockClass; ?>" data-aos-delay="<?php echo 500 + $index * 250; ?>" data-aos="flip-up" data-aos-duration="1200">
<div class="rating-main__num"><?php if ($position === 1): ?><img src="..\wp-content\themes\smartrp\img\svg\trophy.svg" alt=""> <?php endif; ?><?php echo $position; ?></div>
<div class="rating-main__name"><?php echo htmlspecialchars($player[$displayName], ENT_QUOTES, 'UTF-8'); ?></div>
<div class="rating-main__value"><?php echo htmlspecialchars(number_format((float)$player[$displayValue], 0, '.', ' '), ENT_QUOTES, 'UTF-8'); ?> Sel</div>
</div>
<?php endforeach; ?>
<?php if (count($players) === 0): ?>
<div class="rating-main__item rating-main__first" style="width:100%;background-color:#222;box-shadow:0 0 30px rgba(0,0,0,.2);padding:30px;text-align:center;margin:auto;">
<div class="rating-main__name">Дані з бази ще не завантажено</div>
<div class="rating-main__value" style="font-size:18px;color:#f1f1f1;line-height:1.5;">Перевірте підключення до бази даних та наявність таблиць.</div>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
</div>
</div>
</section>

<section class="developers section">
<div class="container text-center">
<h2 class="section__title" data-back-font='DEV'>
<span class="section__title-value">Розробники</span>
</h2>
<div class="developer-block" style="padding:40px 0;max-width:760px;margin:auto;color:#fff;">
<p style="font-size:18px;margin-bottom:14px;">Головний технічний директор Selection RP — <strong>Артем Процко</strong>.</p>
<p style="font-size:18px;margin-bottom:0;">Головний директор Selection RP — <strong>Ярослав Куриленко</strong>.</p>
</div>
</div>
</section>
<style>
.form-page>.scene { max-height: 100vh; }
.developers .developer-block { background: rgba(196, 0, 0, 0.08); border: 1px solid rgba(255,255,255,.12); border-radius: 24px; }
.developers .section__title-value { color: #fff; }
</style>
</main>
<footer class="footer text-center">
<div class="container">

<a class='logo footer-logo flex-inline a-center' href="../index.html">
<img class='logo__image footer-logo__image' src="https://i.postimg.cc/QMQKKq4Z/Gemini-Generated-Image-mbd25lmbd25lmbd2-removebg-preview.png" alt="Selection RP">
<div class="logo__text text-center upper">
<div class="logo__text-up footer-logo__text-up">SELECTION</div>
<div class="logo__text-down footer-logo__text-down">RP</div>
</div>
</a>

<div class="footer__links flex">
<a href="..\docs\privacy-policy and personal-data.pdf">Політика конфіденційності та згода на обробку даних</a><a href="..\docs\return_info.pdf">Політика повернення</a> </div>

<div class="footer__copyright copyright">
Copyright © 2021 Selection RP </div>
<div class="footer__directors">Головний Технічний директор Selection RP Артем Процко та Головний директор Ярослав Куриленко</div>
</div>
</footer>
<div class="modal">

<div class="modal__body modal-req">

<button class="modal__close">
<svg class="modal__close-icon" fill='white' xmlns="http://www.w3.org/2000/svg" width="357" height="357" viewbox="0 0 357 357">
<path d="M357 35.7L321.3 0 178.5 142.8 35.7 0 0 35.7l142.8 142.8L0 321.3 35.7 357l142.8-142.8L321.3 357l35.7-35.7-142.8-142.8z"></path>
</svg>
</button>
<h2 class="section__title text-center modal-req__title">
<span class="section__title-value">Системные требования</span>
</h2>
<div class="modal-req__params">
<div class="modal-req__col">
<div class="modal-req__params-title">Мінімальні</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Операційна система</div>
<div class="modal-req__param-value">Усі версії Windows (x32, x64)</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Процесор</div>
<div class="modal-req__param-value">2 ядра та 2 GHz</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Відеокарта</div>
<div class="modal-req__param-value">256 Мб</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Оперативна пам’ять</div>
<div class="modal-req__param-value">2 Гб</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Вільне місце на диску</div>
<div class="modal-req__param-value">7 Гб</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Додатково</div>
<div class="modal-req__param-value">DirectX 9.0 і вище, Microsoft Visual C++ усі версії</div>
</div>
</div>
<div class="modal-req__col">
<div class="modal-req__params-title"><img src="..\wp-content\themes\smartrp\img\svg\icon-check.svg" alt=""> Рекомендовані</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Операційна система</div>
<div class="modal-req__param-value">Windows 10 / 11</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Процесор</div>
<div class="modal-req__param-value">Intel Core i5 або AMD Ryzen 5</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Відеокарта</div>
<div class="modal-req__param-value">2 Гб відеопам’яті</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Оперативна пам’ять</div>
<div class="modal-req__param-value">8 Гб</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Вільне місце на диску</div>
<div class="modal-req__param-value">12 Гб</div>
</div>
<div class="modal-req__param">
<div class="modal-req__param-title">Додатково</div>
<div class="modal-req__param-value">Стабільне інтернет-з’єднання, останні драйвери відеокарти</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
