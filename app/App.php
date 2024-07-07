<?php

declare(strict_types = 1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) 
    {
        if(is_dir($file))
        {
            continue;
        }
        $files[]=$dirPath . $file;
    }
    return $files;
}

function getTransactions(string $filename): array
{
    if(!file_exists($filename))
    {
        trigger_error('File"' . $filename . '"does not exist .', E_USER_ERROR);
    }

    $file = fopen($filename,'r');
    $transactions = [];

    fgetcsv($file);

    while(($transaction = fgetcsv($file)) !== false)
    {
        $transactions[] = $transaction;
    }

    return $transactions;
}

function ParseDataRow(string $transactionRow): array
{
    // array destructuring - strip away symbols from $amount
    [$date, $checkNumber, $description,$amount] = $transactionRow;

    $amount = (float) str_replace(['$',','], '', $amount);
    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'decsription' => $description,
        'amount' => $amount,
    ];
}