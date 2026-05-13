<?php
/**
 * Підключення до онлайн БД Selectio RP
 * Структура таблиць для особистого кабінету та ігрового сервера
 */

// === НАЛАШТУВАННЯ БД ===
define('DB_HOST', '127.0.0.1');
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
 * Функція автоматично кешує з'єднання для повторного використання
 * 
 * @return mysqli|false
 */
function get_db_connection() {
    static $mysqli = null;
    
    if ($mysqli === null) {
        // Підключитись до БД без кидання помилок
        $mysqli = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Перевірити помилку підключення
        if ($mysqli->connect_error) {
            error_log('[DB Error] Не вдалося підключитись до БД: ' . $mysqli->connect_error);
            return false;
        }
        
        // Встановити UTF-8 кодування
        if (!$mysqli->set_charset('utf8mb4')) {
            error_log('[DB Error] Помилка встановлення кодування: ' . $mysqli->error);
        }
    }
    
    return $mysqli;
}

/**
 * Виконати SQL запит
 * @param string $sql SQL запит
 * @return mysqli_result|bool Результат запиту або false при помилці
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
 * @param mysqli_result $result Результат запиту
 * @return array|null
 */
function db_fetch_assoc($result) {
    if (!$result) return null;
    return $result->fetch_assoc();
}

/**
 * Отримати один рядок та закрити результат
 * @param mysqli_result $result Результат запиту
 * @return array|null
 */
function db_fetch_one($result) {
    $row = db_fetch_assoc($result);
    if ($result) $result->free();
    return $row;
}

/**
 * Отримати всі рядки з результату
 * @param mysqli_result $result Результат запиту
 * @return array
 */
function db_fetch_all($result) {
    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
    }
    return $rows;
}

/**
 * Екранувати рядок для безпеки (SQL Injection захист)
 * @param string $str Рядок для екранування
 * @return string Екранований рядок
 */
function db_escape($str) {
    $mysqli = get_db_connection();
    if (!$mysqli) return $str;
    return $mysqli->real_escape_string($str);
}

/**
 * Отримати ID останнього вставленого рядка
 * @return int ID
 */
function db_insert_id() {
    $mysqli = get_db_connection();
    if (!$mysqli) return 0;
    return $mysqli->insert_id;
}

/**
 * Отримати кількість затронутих рядків останнім запитом
 * @return int Кількість рядків
 */
function db_affected_rows() {
    $mysqli = get_db_connection();
    if (!$mysqli) return 0;
    return $mysqli->affected_rows;
}

/**
 * Отримати текст останньої помилки БД
 * @return string Текст помилки
 */
function db_error() {
    $mysqli = get_db_connection();
    if (!$mysqli) return 'Не вдалося підключитись до БД';
    return $mysqli->error ?: 'Невідома помилка';
}

/**
 * Закрити підключення до БД
 */
function db_close() {
    static $closed = false;
    if ($closed) return;
    
    $mysqli = get_db_connection();
    if ($mysqli) {
        $mysqli->close();
        $closed = true;
    }
}
