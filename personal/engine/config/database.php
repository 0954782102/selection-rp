?<?php
include "table.php";
// DB host - check your hosting control panel for the correct MySQL host, it may not be '127.0.0.1'
// Common examples: 'mysql.hostinger.com', 'localhost', server IP, etc.
$host = '127.0.0.1';
$dbname = 'user43104';
$user = 'user43104';
$pass = '4wJVPki5EPnA';
$charset = 'utf8';

if (!defined('PDO::ATTR_DEFAULT_FETCH_MODE')) define('PDO::ATTR_DEFAULT_FETCH_MODE', 3);
if (!defined('PDO::ATTR_EMULATE_PREPARES')) define('PDO::ATTR_EMULATE_PREPARES', 20);
if (!defined('PDO::FETCH_ASSOC')) define('PDO::FETCH_ASSOC', 2);
if (!defined('PDO::ERRMODE_EXCEPTION')) define('PDO::ERRMODE_EXCEPTION', 2);

class PDOStatementCompat
{
    protected $mysqli;
    protected $sql;
    protected $params = [];
    protected $result;
    protected $affectedRows = 0;

    public function __construct($mysqli, $sql)
    {
        $this->mysqli = $mysqli;
        $this->sql = $sql;
    }

    public function bindParam($param, &$variable, $data_type = null, $length = null, $driver_options = null)
    {
        $this->params[$param] = &$variable;
        return true;
    }

    public function bindValue($param, $value, $data_type = null)
    {
        $this->params[$param] = $value;
        return true;
    }

    public function execute($params = null)
    {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->params[$key[0] === ':' ? $key : ':' . $key] = $value;
            }
        }

        $sql = $this->sql;
        $sql = preg_replace_callback('/(:[a-zA-Z0-9_]+)/', function ($matches) {
            $placeholder = $matches[1];
            if (!array_key_exists($placeholder, $this->params)) {
                return $placeholder;
            }
            return $this->quote($this->params[$placeholder]);
        }, $sql);

        $this->result = $this->mysqli->query($sql);
        if ($this->result === false) {
            return false;
        }

        $this->affectedRows = $this->mysqli->affected_rows;
        return true;
    }

    protected function quote($value)
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        return "'" . $this->mysqli->real_escape_string($value) . "'";
    }

    public function fetch()
    {
        if ($this->result instanceof mysqli_result) {
            return $this->result->fetch_assoc();
        }
        return false;
    }

    public function rowCount()
    {
        if ($this->result instanceof mysqli_result) {
            return $this->result->num_rows;
        }
        return $this->affectedRows;
    }
}

class PDOCompat
{
    protected $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function prepare($sql)
    {
        return new PDOStatementCompat($this->mysqli, $sql);
    }

    public function query($sql, $fetchMode = null)
    {
        return $this->mysqli->query($sql);
    }
}

$usePdoMysql = false;
if (class_exists('PDO')) {
    try {
        $drivers = PDO::getAvailableDrivers();
        $usePdoMysql = in_array('mysql', $drivers, true);
    } catch (Exception $e) {
        $usePdoMysql = false;
    }
}

if ($usePdoMysql) {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $opt = [
        //PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $db = new PDO($dsn, $user, $pass, $opt);
    } catch (PDOException $e) {
        die('Подключение не удалось: ' . $e->getMessage());
    }
} elseif (extension_loaded('mysqli')) {
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = new mysqli($host, $user, $pass, $dbname);
    if ($mysqli->connect_error) {
        die('Подключение не удалось: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset($charset);
    $db = new PDOCompat($mysqli);
} else {
    die('Подключение не удалось: требуются расширения PDO или MySQLi.');
}

$sql = "SELECT * FROM ucp_settings";
$statement = $db->prepare($sql);
$statement->execute();
$ucp_settings = $statement->fetch();