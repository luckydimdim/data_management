<?php declare(strict_types=1);

/**
 * Reads CSV file to array
 *
 * @param string $path Path to file
 * @return array List of rows
 */
function readSurvey(string $path): array {
    $result = [];
    $fields = [];
    $index = 0;
    $file = fopen($path, 'r');

    while (($row = fgetcsv($file)) !== false) {
        if (!$fields) {
            $fields = $row;
            continue;
        }

        foreach ($row as $field => $value) {
            $result[$index][$fields[$field]] = $value;
        }

        $index++;
    }

    fclose($file);

    return $result;
}

$surveyA = readSurvey('question2_survey_A.csv');
$surveyB = readSurvey('question2_survey_B.csv');
$surveyC = readSurvey('question2_survey_C.csv');
$surveyMap = readSurvey('question2_map.csv');

$rowNumber = 0;
$resultRows = [];

// Loop through all surveys and build combined result
while ($rowNumber < count($surveyA)) {
    $colNumber = 0;
    $resultRow = [];

    // Retrieve data from surveys according to the map
    while ($colNumber < count($surveyMap)) {
        $resultRow["Q$colNumber"] = '';

        // Take not empty survey results, combine them and add source (A,B,C)
        if (isset($surveyMap[$colNumber]['Survey A'])) {
            if (isset($surveyA[$rowNumber][$surveyMap[$colNumber]['Survey A']])) {
                $resultRow["Q$colNumber"] = 'A'. $surveyA[$rowNumber][$surveyMap[$colNumber]['Survey A']];
            }
        }

        if (isset($surveyMap[$colNumber]['Survey B'])) {
            if (isset($surveyC[$rowNumber][$surveyMap[$colNumber]['Survey B']])) {
                $resultRow["Q$colNumber"] .= 'B'. $surveyC[$rowNumber][$surveyMap[$colNumber]['Survey B']];
            }
        }

        if (isset($surveyMap[$colNumber]['Survey C'])) {
            if (isset($surveyC[$rowNumber][$surveyMap[$colNumber]['Survey C']])) {
                $resultRow["Q$colNumber"] .= 'C'. $surveyC[$rowNumber][$surveyMap[$colNumber]['Survey C']];
            }
        }

        $colNumber += 1;
    }

    $resultRows[] = $resultRow;
    $rowNumber += 1;
}

// Save results to a file
if (!file_exists('question2_combined.csv')) {
    touch('question2_combined.csv');
}

$csv = fopen('question2_combined.csv', 'w');

foreach ($resultRows as $fields) {
    fputcsv($csv, $fields);
}

fclose($csv);