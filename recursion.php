<?php

function numberOfItems(array $arr, string $needle): int {
    $count = 0;
    
    foreach ($arr as $element) {
        switch (gettype($element)) {
            case 'array':
                $count += numberOfItems($element, $needle);
                break;
            case 'string':
                if ($element === $needle) {
                    $count++;
                }
                break;
        }
    }
    
    return $count;
}

if ($argc != 3) {
    echo "Invalid amount of arguments passed, expected: php recursion.php '\"json_encoded_array\"' 'needle'\n";
    exit(1);
}

$arr = json_decode($argv[1], true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error decoding JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

$needle = $argv[2];

echo numberOfItems($arr, $needle) . PHP_EOL;