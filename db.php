<?php
// Загальні налаштування підключення до MySQL
const DB_HOST = '127.0.0.1';
const DB_USER = 'user43104';
const DB_PASS = '4wJVPki5EPnA';
const DB_NAME = 'user43104';

function get_db_connection() {
    static $mysqli = null;
    if ($mysqli === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $mysqli->set_charset('utf8mb4');
    }
    return $mysqli;
}

function get_table_names(mysqli $mysqli): array {
    $tables = [];
    $result = $mysqli->query('SHOW TABLES');
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $tables[] = $row[0];
    }
    $result->free();
    return $tables;
}

function find_table(mysqli $mysqli, array $keywords, ?string $fallback = null): ?string {
    $tables = get_table_names($mysqli);
    foreach ($keywords as $keyword) {
        foreach ($tables as $table) {
            if (stripos($table, $keyword) !== false) {
                return $table;
            }
        }
    }
    return $fallback ?? ($tables[0] ?? null);
}

function get_columns(mysqli $mysqli, string $table): array {
    $columns = [];
    $result = $mysqli->query("SHOW COLUMNS FROM `" . $mysqli->real_escape_string($table) . "`");
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    $result->free();
    return $columns;
}

function find_column(array $columns, array $keywords, ?string $fallback = null): ?string {
    foreach ($keywords as $keyword) {
        foreach ($columns as $column) {
            if (stripos($column, $keyword) !== false) {
                return $column;
            }
        }
    }
    return $fallback;
}

function quote_identifier(string $value): string {
    return '`' . str_replace('`', '``', $value) . '`';
}
