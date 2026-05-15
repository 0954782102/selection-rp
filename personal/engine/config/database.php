<?php
// test_db.php
echo "<pre>";

// 1. Перевірка розширень
echo "PDO завантажено: " . (class_exists('PDO') ? 'ТАК' : 'НІ') . "\n";
echo "MySQLi завантажено: " . (extension_loaded('mysqli') ? 'ТАК' : 'НІ') . "\n";

if (class_exists('PDO')) {
    echo "PDO драйвери: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
}

echo "\n";

// 2. Спроба підключення через PDO
$hosts = ['localhost', '127.0.0.1', 'mysql', 'db'];
$user   = 'user43104';
$pass   = '4wJVPki5EPnA';
$dbname = 'user43104';

foreach ($hosts as $h) {
    try {
        $pdo = new PDO("mysql:host=$h;dbname=$dbname;charset=utf8mb4", $user, $pass);
        echo "? PDO підключився через: $h\n";
        break;
    } catch (Exception $e) {
        echo "? PDO $h: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// 3. Спроба через MySQLi
foreach ($hosts as $h) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $m = @new mysqli($h, $user, $pass, $dbname);
    if (!$m->connect_error) {
        echo "? MySQLi підключився через: $h\n";
        break;
    } else {
        echo "? MySQLi $h: " . $m->connect_error . "\n";
    }
}

echo "</pre>";