<?php
$url = 'https://api.schengenvisaappointments.com/api/visa-list/'; // replace with a valid API endpoint
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

// filter data where source_country is "Türkiye" and appointment_date is not empty
$turkeyData = array_filter($data, function($item) {
    return $item['source_country'] == 'Turkiye' && !empty($item['appointment_date']);
});

// Sort data by mission_country alphabetically
usort($turkeyData, function($a, $b) {
    return strcmp($a['mission_country'], $b['mission_country']);
});

?>
