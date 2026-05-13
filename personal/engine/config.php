<?php
/*
=====================================================
 Copyright (c) © 2026 Selection RP
=====================================================
*/

if(!defined("GRANDRULZ")) exit("HACKING ATTEMPT!");

// Налаштування таблиць (згідно з твоїми дефайнами)
$tableconf = array(
    // Основні таблиці (твої дефайни)
    'TABLE_ACCOUNTS'       => 'accounts',
    'TABLE_ADMIN'          => 'admin',
    'TABLE_OTHERS'         => 'others',
    'TABLE_BANIP'          => 'banip',
    'TABLE_BIZZ'           => 'bizz',
    'TABLE_HOUSE'          => 'house',
    'TABLE_ATM'            => 'atm',
    'TABLE_BAN'            => 'ban',
    'TABLE_GANGZONE'       => 'gangzone',
    'TABLE_CARS'           => 'cars',
    'TABLE_GREENZONE'      => 'greenzone',
    'TABLE_TICKETS'        => 'tickets',
    'TABLE_SLED'           => 'tracking',
    'T_PROMOCODE'          => 'promocode',
    'T_PROMOCODE_USED'     => 'promocode_used',

    // Поля в таблиці accounts
    'TABLE_ID'             => 'ID',
    'TABLE_NAME'           => 'NickName',
    'TABLE_PASSWORD'       => 'Password',
    'TABLE_LEVEL'          => 'Level',
    'TABLE_MONEY'          => 'Money',
    'TABLE_CASH'           => 'cash',
    'TABLE_DONATE'         => 'VirMoney',

    // Додаємо ці ключі, щоб прибрати помилки зі скріншоту
    'X2_DONATE'            => false, 
    'action'               => '',

    'unitpay' => array(
        'shop_id'    => '60203-83cdf',
        'secret_key' => ''
    )
);
/*
 * Налаштування серверів
 */
$servers = array(
    "Selection RP" => array(
        "IP"             => "188.127.241.74",
        "PORT"           => "4136",
        "MYSQL_HOST"     => "127.0.0.1", // Зміни на зовнішній IP для Render
        "MYSQL_LOGIN"    => "user43104",
        "MYSQL_PASSWORD" => "4wJVPki5EPnA",
        "MYSQL_DB"       => "user43104",
        "MYSQL_TABLE"    => "accounts"
    )
);
