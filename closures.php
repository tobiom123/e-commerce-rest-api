<?php

function changeDateFormat(array $dates): array
{
    $listOfDates = [];
    $formats = ['d/m/Y', 'Y/m/d', 'm-d-Y', 'Ymd'];
    $closure = function ($date) use (&$listOfDates, $formats) {
        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $date);

            if ($dateTime !== false) break;
        }
        if ($dateTime === false) {
            try {
                $dateTime = new DateTime($date);
            } catch (Exception $e) {
                echo "Error parsing date '{$date}': " . $e->getMessage() . "\n";
                return false;
            }
        }

        $formattedDate = $dateTime->format('d/m/Y');
        $listOfDates[] = $formattedDate;
    };

    array_map($closure, $dates);

    return $listOfDates;
}

$datesArray = array_slice($argv, 1);

if (!empty($datesArray)) {
    $formattedDates = changeDateFormat($datesArray);
    foreach ($formattedDates as $formattedDate) {
        echo $formattedDate . "\n";
    }
} else {
    echo "Please provide at least one date as an argument.\n";
}
