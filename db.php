<?php
/**
 * Підключення до онлайн БД Selection RP
 * Структура таблиць для особистого кабінету та ігрового сервера
 */

// === НАЛАШТУВАННЯ БД (Дані з панелі хостингу) ===
define('DB_HOST', '127.0.0.1'); // Замінити на зовнішній IP, якщо сайт на іншому хостингу
define('DB_USER', 'user43104');
define('DB_PASS', '4wJVPki5EPnA');
define('DB_NAME', 'user43104');

// === ТАБЛИЦІ ІГРОВОГО СЕРВЕРА ===
define('TABLE_ACCOUNTS', 'accounts');        // Облікові записи гравців
define('TABLE_ADMIN', 'admin');              // Адміністратори
define('TABLE_OTHERS', 'others');            // Інші налаштування
define('TABLE_BANIP', 'banip');              // Забанені IP
define('TABLE_BIZZ', 'bizz');                // Бізнес
define('TABLE_HOUSE', 'house');              // Будинки
define('TABLE_ATM', 'atm');                  // ATM
define('TABLE_BAN', 'ban');                  // Забаніння
define('TABLE_GANGZONE', 'gangzone');        // Банди/Зони
define('TABLE_CARS', 'cars');                // Автомобілі
define('TABLE_GREENZONE', 'greenzone');      // Зелені зони
define('TABLE_TICKETS', 'tickets');          // Квитанції
define('TABLE_SLED', 'tracking');            // Відслідковування
define('TABLE_PROMOCODE', 'promocode');      // Промокоди
define('TABLE_PROMOCODE_USED', 'promocode_used'); // Використані промокоди

/**
 * Отримати підключення до БД
 */
function get_db_connection() {
    static $mysqli = null;
    
    if ($mysqli === null) {
        mysqli_report(MYSQLI_REPORT_OFF); // Вимикаємо стандартні помилки для власної обробки
        $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($mysqli->connect_error) {
            die('Помилка підключення до бази даних. Перевірте IP, логін або пароль.');
        }
        
        // Встановлюємо правильне кодування для підтримки всіх символів
        if (!$mysqli->set_charset('utf8mb4')) {
            error_log('[DB Error] Помилка встановлення кодування: ' . $mysqli->error);
        }
    }
    
    return $mysqli;
}

/**
 * Виконати SQL запит
 */
function db_query($sql) {
    $mysqli = get_db_connection();
    if (!$mysqli) return false;
    
    $result = $mysqli->query($sql);
    if (!$result) {
        error_log('[DB Error] SQL: ' . $sql . ' | Error: ' . $mysqli->error);
    }
    return $result;
}

/**
 * Отримати один рядок з результату
 */
function db_fetch_assoc($result) {
    if (!$result) return null;
    return $result->fetch_assoc();
}

/**
 * Екранувати рядок для безпеки (Захист від SQL Injection)
 */
function db_escape($str) {
    $mysqli = get_db_connection();
    if (!$mysqli) return $str;
    return $mysqli->real_escape_string($str);
}
?>
