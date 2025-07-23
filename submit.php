<?php
// Define the CSV file path
$csvFile = 'responses.csv';

// Collect all POST data
$data = [];
foreach ($_POST as $key => $value) {
    // Remove line breaks and extra spaces
    $data[$key] = trim(str_replace(["\r", "\n"], ' ', $value));
}

// If the file doesn't exist, add headers as the first row
if (!file_exists($csvFile)) {
    $header = array_keys($data);
    $fp = fopen($csvFile, 'w');
    fputcsv($fp, $header);
    fclose($fp);
}

// Append the data as a new row
$fp = fopen($csvFile, 'a');
fputcsv($fp, $data);
fclose($fp);

// Show thank you message
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .thankyou { margin-top: 100px; text-align: center; }
    </style>
</head>
<body>
    <div class="thankyou">
        <h1>Thank you for your submission!</h1>
        <p>Your responses have been recorded.</p>
        <a href="index.html">Back to Questionnaire</a>
    </div>
</body>
</html>