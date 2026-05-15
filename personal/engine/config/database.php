<?php
// Åñëè ôàéë table.php ëåæèò â òîé æå ïàïêå, îñòàâëÿåì òàê. 
// Åñëè íåò — ïğîâåğü ïóòü.
include_once "table.php";

// ÎÑÍÎÂÍÛÅ ÍÀÑÒĞÎÉÊÈ
// Ïîïğîáóé 'localhost' âìåñòî '127.0.0.1', åñëè îøèáêà ïîâòîğèòñÿ
$host    = 'server.MYSQL18'; 
$dbname  = 'user43104';
$user    = 'user43104';
$pass    = '4wJVPki5EPnA';
$charset = 'utf8mb4'; // utf8mb4 ëó÷øå ïîääåğæèâàåò ñîâğåìåííûå ñèìâîëû

// Ïğåäâàğèòåëüíîå îïğåäåëåíèå êîíñòàíò (íà ñëó÷àé, åñëè PDO íå çàãğóæåí)
if (!defined('PDO::ATTR_DEFAULT_FETCH_MODE')) define('PDO::ATTR_DEFAULT_FETCH_MODE', 3);
if (!defined('PDO::ATTR_EMULATE_PREPARES'))   define('PDO::ATTR_EMULATE_PREPARES', 20);
if (!defined('PDO::FETCH_ASSOC'))             define('PDO::FETCH_ASSOC', 2);
if (!defined('PDO::ERRMODE_EXCEPTION'))       define('PDO::ERRMODE_EXCEPTION', 2);

/**
 * İìóëÿöèÿ PDOStatement ÷åğåç MySQLi äëÿ ñòàğûõ ñèñòåì
 */
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

/**
 * İìóëÿöèÿ PDO ÷åğåç MySQLi
 */
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

// ËÎÃÈÊÀ ÏÎÄÊËŞ×ÅÍÈß
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
    // Âàğèàíò 1: ×åğåç ñòàíäàğòíûé PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $db = new PDO($dsn, $user, $pass, $opt);
    } catch (PDOException $e) {
        die('Îøèáêà ïîäêëş÷åíèÿ (PDO): ' . $e->getMessage());
    }
} elseif (extension_loaded('mysqli')) {
    // Âàğèàíò 2: ×åğåç ıìóëÿöèş è MySQLi
    mysqli_report(MYSQLI_REPORT_OFF);
    $mysqli = @new mysqli($host, $user, $pass, $dbname);
    
    if ($mysqli->connect_error) {
        die('Îøèáêà ïîäêëş÷åíèÿ (MySQLi): ' . $mysqli->connect_error . ' (Êîä: ' . $mysqli->connect_errno . ')');
    }
    
    $mysqli->set_charset($charset);
    $db = new PDOCompat($mysqli);
} else {
    die('Êğèòè÷åñêàÿ îøèáêà: íà ñåğâåğå íå óñòàíîâëåíû ğàñøèğåíèÿ PDO èëè MySQLi.');
}

// ÒÅÑÒÎÂÛÉ ÇÀÏĞÎÑ
try {
    $sql = "SELECT * FROM ucp_settings";
    $statement = $db->prepare($sql);
    $statement->execute();
    $ucp_settings = $statement->fetch();
    
    // Åñëè íóæíî ïğîâåğèòü ğåçóëüòàò:
    // print_r($ucp_settings);
} catch (Exception $e) {
    echo "Îøèáêà âûïîëíåíèÿ çàïğîñà: " . $e->getMessage();
}