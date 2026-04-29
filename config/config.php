<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');
define('PRODUCTS_FILE', DATA_PATH . '/produits.json');
define('INVOICES_FILE', DATA_PATH . '/factures.json');
define('USERS_FILE', DATA_PATH . '/utilisateurs.json');
define('TVA_TAUX', 0.18);
define('APP_NAME', 'Système de Facturation');

date_default_timezone_set('Africa/Kinshasa');

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function post($key, $default = '')
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function get($key, $default = '')
{
    return isset($_GET[$key]) ? trim((string) $_GET[$key]) : $default;
}

function format_currency($amount)
{
    return number_format((float) $amount, 2, ',', ' ') . ' CDF';
}

function today_date()
{
    return date('Y-m-d');
}

function now_time()
{
    return date('H:i:s');
}
