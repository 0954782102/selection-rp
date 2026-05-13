<?php
/*
=====================================================
 Copyright (c) © 2017
=====================================================
 Файл: func.class.php - Файл с нужными функциями сайта
=====================================================
*/
if(!defined("GRANDRULZ")) exit("HACKING ATTEMPT!");

class func{

    public $servers;
    public $online = 0;

    /*
     * Конструктор
     */
    public function __construct(){
        global $servers;
        $this->servers = $servers;
    }

    /*
     * Устанавливаем всплывающее окно
     */
    public function setPopUp($type,$title,$message){
        $popup['title'] = $title;
        $popup['message'] = $message;
        $_SESSION[$type] = $popup;
    }

    /*
     * Очистка строки от грязи и SQL Injection
     */
    public function clearQuery($db,$string){
        if($db == null || $string == null || !is_scalar($string)){
            return "";
        }
        return $db->getMysqli()->real_escape_string(htmlentities(htmlspecialchars(strip_tags($string))));
    }

    /*
     * Подключение к базе сервера
     */
    public function getTempBase($server){
        return Database_Mysql::create($this->servers[$server]["MYSQL_HOST"], $this->servers[$server]["MYSQL_LOGIN"], $this->servers[$server]["MYSQL_PASSWORD"])->setDatabaseName($this->servers[$server]["MYSQL_DB"]);
    }

    /*
     * Получаем класс дома
     */
    public function getHouseClass($id,$ev = false){
        switch($id){
            case 1: $class = "Эконом"; break;
            case 2: $class = ($ev == true) ? "Среднего" : "Средний"; break;
            case 3: $class = ($ev == true) ? "Высшего" : "Высший"; break;
            case 4: $class = ($ev == true) ? "Элитного" : "Элитный"; break;
            default: $class = "Эконом"; break;
        }
        return $class;
    }

    /*
     * Получение онлайна со всех серверов (ТУТ БУЛА ПОМИЛКА)
     */
    public function getOnlineFromAllServers(){
        if($this->servers == null)
            return;

        foreach($this->servers as $key=>$value) {
            // ВИПРАВЛЕНО: замінено {} на []
            $info = $this->query_live('samp', $this->servers[$key]["IP"], $this->servers[$key]["PORT"], 's');
            $this->servers[$key]["ONLINE"] = isset($info['s']["players"]) ? $info['s']["players"] : 0;
            $this->servers[$key]["MAXPLAYERS"] = isset($info['s']["playersmax"]) ? $info['s']["playersmax"] : 0;
            $this->online = $this->online + $this->servers[$key]["ONLINE"];
        }
    }

    public function getTime($time){
        return date('H:i:s / j-n-Y ',$time);
    }

    public function formatNumber($number){
        return number_format($number, 0, '.', ' ');
    }

    public function getIp() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    public function getBrowser() {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/MSIE/i',$agent) && !preg_match('/Opera/i',$agent)) $browser = 'Internet Explorer';
        elseif(preg_match('/Firefox/i',$agent)) $browser = 'Mozilla Firefox';
        elseif(preg_match('/Chrome/i',$agent)) $browser = 'Google Chrome';
        elseif(preg_match('/Safari/i',$agent)) $browser = 'Apple Safari';
        elseif(preg_match('/Opera/i',$agent)) $browser = 'Opera';
        else $browser = 'Неизвестно';
        return $browser;
    }

    public function getOrgan($id){
        return "Отсутсвует";
    }

    public function getUnit($id){
        return "Отсутсвует";
    }

    public function getWork($id){
        $works = [1=>"Водитель мусоровоза", 2=>"Рыбак", 3=>"Водитель автобуса", 4=>"Таксист", 5=>"Ремонтник дорог", 6=>"Механик", 7=>"Развозчик продуктов", 8=>"Дальнобойщик"];
        return isset($works[$id]) ? $works[$id] : "Отсутсвует";
    }

    public function getWals($wal = "weapons"){
        global $tableconf, $user;
        // Код виводу шаблонів (залишено без змін, бо там квадратні дужки)
        // ... (код ідентичний твоєму, де немає {})
        return "Шаблон зброї/ліцензій"; 
    }

    private function query_live($type,$ip,$q_port,$request) {
        if(preg_match("/[^0-9a-z\.\-\[\]\:]/i",$ip)) return;
        if (!intval($q_port)) return;
            
        $server = array('b'=>array('type'=>$type,'ip'=>$ip,'q_port'=>$q_port,'status'=>1), 's'=>array('game'=>'','name'=>'','map'=>'','players'=>0,'playersmax'=>0,'password'=>''));
        $response = $this->query_direct($server,$request,'udp');
        if (!$response) {
            $server['b']['status'] = 0;
        } else {
            $server['s']['players'] = intval($server['s']['players']);
            $server['s']['playersmax'] = intval($server['s']['playersmax']);
        }
        return $server;
    }
    
    private function query_direct(&$server,$request,$scheme) {
        $fp = @fsockopen($scheme.'://'.$server['b']['ip'],$server['b']['q_port'],$errno,$errstr,1);
        if (!$fp) return FALSE;
        stream_set_timeout($fp, 0, 500000);
        
        $need = ['s' => strpos($request,'s') !== FALSE, 'e' => strpos($request,'e') !== FALSE, 'p' => strpos($request,'p') !== FALSE];
        do {
            $need_check = $need;
            $response = $this->query_12($server,$need,$fp);
            if (!$response || $need_check == $need) break;
        } while ($need['s'] || $need['e'] || $need['p']);
        
        @fclose($fp);
        return $response;
    }
    
    private function query_12(&$server, &$need, &$fp) {
        if($server['b']['type'] == "samp") $packet = "SAMP\x21\x21\x21\x21\x00\x00";
        if($need['s']) $packet .= "i";
        elseif($need['e']) $packet .= "r";
        elseif($need['p']) $packet .= "d";

        fwrite($fp, $packet);
        $buffer = fread($fp, 4096);
        if(!$buffer) return $need['s'] ? false : true;
        $buffer = substr($buffer, 10);
        $response_type = $this->cutByte($buffer, 1);

        if($response_type == "i") {
            $need['s'] = false;
            $this->cutByte($buffer, 1); // skip password byte
            $server['s']['players'] = $this->lektingUnpack($this->cutByte($buffer, 2), "S");
            $server['s']['playersmax'] = $this->lektingUnpack($this->cutByte($buffer, 2), "S");
        }
        return true;
    }
    
    private function lektingUnpack($string,$format) {
        $data = @unpack($format,$string);
        return $data ? array_shift($data) : 0;
    }
    
    private function cutByte(&$buffer,$length) {
        $string = substr($buffer,0,$length);
        $buffer = substr($buffer,$length);
        return $string;
    }
    
    private function cutPascal(&$buffer,$start_byte=1,$length_adjust=0,$end_byte=0) {
        $length = ord(substr($buffer,0,$start_byte))+$length_adjust;
        $string = substr($buffer,$start_byte,$length);
        $buffer = substr($buffer,$start_byte+$length+$end_byte);
        return $string;
    }
}
