<?php

$file = 'datos.csv';

if (!file_exists($file)) {
    die("Input file not found.");
}

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    [$username, $system, $encodedScore] = str_getcsv($line);

    $digits = str_split($encodedScore);
    $base = strlen($system);
    $decoded = 0;

    foreach ($digits as $index => $digit) {
        $position = strpos($system, $digit);
        $exponent = count($digits) - $index - 1;
        $decoded += $position * pow($base, $exponent);
    }

    echo "$username, $decoded\n";
}