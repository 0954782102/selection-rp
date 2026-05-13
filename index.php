<?php
// Вимикаємо вивід помилок на екран після налаштування (якщо треба відлагодити — змініть 0 на 1)
ini_set('display_errors', 0);
error_reporting(E_ALL);

$phpVersion = phpversion();
if (version_compare($phpVersion, '7.0.0', '<')) {
    die("PHP 7.0.0 or newer is required. $phpVersion does not meet this requirement.");
}

$dir = __DIR__;
$languageCookieName = 'xf_language_id'; // Для XenForo назва куки зазвичай має префікс xf_

if (!isset($_COOKIE[$languageCookieName]) && !headers_sent()) {
    $config = [];
    $configFile = $dir . '/src/config.php';

    if (file_exists($configFile)) {
        @include($configFile);

        if (!empty($config['db']['host']) && !empty($config['db']['username']) && !empty($config['db']['dbname'])) {
            $dbPort = !empty($config['db']['port']) ? intval($config['db']['port']) : 3306;
            
            try {
                $mysqli = new mysqli(
                    $config['db']['host'], 
                    $config['db']['username'], 
                    $config['db']['password'], 
                    $config['db']['dbname'], 
                    $dbPort
                );

                if ($mysqli->connect_errno === 0) {
                    $mysqli->set_charset('utf8mb4');
                    
                    $query = "SELECT language_id FROM xf_language 
                              WHERE language_code IN ('uk-UA', 'uk') 
                              OR title LIKE '%Ukrain%' 
                              OR title LIKE '%Україн%' 
                              LIMIT 1";
                    
                    if ($result = $mysqli->query($query)) {
                        $row = $result->fetch_assoc();
                        if ($row && !empty($row['language_id'])) {
                            $languageId = intval($row['language_id']);
                            
                            // Встановлюємо куку на 1 рік
                            setcookie($languageCookieName, $languageId, time() + 31536000, '/', '', false, true);
                            $_COOKIE[$languageCookieName] = $languageId;
                        }
                        $result->free();
                    }
                    $mysqli->close();
                }
            } catch (Exception $e) {
                // Мовчки пропускаємо помилки БД, щоб сайт завантажився стандартно
            }
        }
    }
}

// ПІСЛЯ ЦЬОГО КОДУ МАЄ ЙТИ ПІДКЛЮЧЕННЯ ОСНОВНОГО ФАЙЛУ XENFORO
// Наприклад: require($dir . '/index.php');
