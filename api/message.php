<?php
header('Content-Type: application/json');

$response = [
    'title' => 'Barangay Service Center',
    'description' => 'Click the button below to fetch the latest system status message from the API.',
    'buttonText' => 'Fetch Status',
    'responseMessage' => 'All barangay services are running smoothly. No pending critical alerts at this time.'
];

echo json_encode($response);
?>