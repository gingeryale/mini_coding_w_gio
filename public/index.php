<?php

declare(strict_types = 1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

require APP_PATH . 'App.php';
require APP_PATH . 'helpers.php';

$files = getTransactionFiles(FILES_PATH);
$merged_transactions = [];


foreach($files as $file)
{
    $merged_transactions = array_merge($merged_transactions, getTransactions($file));
}
$totals = calcTotals($merged_transactions);

require VIEWS_PATH . "transactions.php";

