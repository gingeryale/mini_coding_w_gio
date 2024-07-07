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


function parseDataRow(array $transactionRow): array
{
    // array destructuring - strip away symbols from $amount
    [$date, $checkNumber, $description,$amount] = $transactionRow;

    $amount = (float) str_replace(['$',','], '', $amount);
    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount,
    ];
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
        $transactions[] = parseDataRow($transaction);
    }

    return $transactions;
}

function calcTotals(array $transactions): array
{
    $totals = [
        'netTotal' =>0,
        'totalIncome'=>0,
        'totalExpense'=>0
    ];

    foreach($transactions as $transact)
    {
        $totals['netTotal']+=$transact['amount'];

        if($transact['amount'] >= 0)
        {
            $totals['totalIncome'] += $transact['amount'];
        }
        else
        {
            $totals['totalExpense'] += $transact['amount'];
        }
    }

    return $totals;
}