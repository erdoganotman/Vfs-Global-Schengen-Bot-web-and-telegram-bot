<?php
$url = 'https://api.schengenvisaappointments.com/api/visa-list/'; // replace with a valid API endpoint
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

// filter data where source_country is "Türkiye" and appointment_date is not empty and mission_country is in the specified list and visa_subcategory contains "touris"
$turkeyData = array_filter($data, function($item) {
    return $item['source_country'] == 'Turkiye' 
        &&!empty($item['appointment_date']) 
        && in_array($item['mission_country'], ['France', 'Netherlands','Luxembourg', 'Spain','Hungary','Portugal','Greece'])
        && (strpos($item['visa_subcategory'], 'TURIZM VIZE BASVURUSU')!== false 
            || strpos($item['visa_subcategory'], 'TOURISM VISA APPLICATION')!== false 
            || strpos($item['visa_subcategory'], 'Short Term Standard')!== false 
            || strpos($item['visa_subcategory'], 'tourism')!== false 
            || strpos($item['visa_subcategory'], 'turizm')!== false 
            || strpos($item['visa_subcategory'], 'touristic')!== false 
            || strpos($item['visa_subcategory'], 'tourist')!== false);
});

// Sort data by mission_country alphabetically
usort($turkeyData, function($a, $b) {
    return strcmp($a['mission_country'], $b['mission_country']);
});

// Assuming you have a Telegram bot token and chat ID
$botToken = 'your-bottoken';
$chatId = 'your-chatid';

if (!empty($turkeyData)) {
    $message = '';
    foreach ($turkeyData as $item) {
        $message.= $item['mission_country']. ', Bu Tarihde: '. $item['appointment_date'].$item['center_name'].'Turistik Randevu Açtı'. PHP_EOL;
    }

    // Check if the new message is the same as the previous one
    $previousMessage = file_get_contents('previous_message.txt');
    if ($message!= $previousMessage) {
        // Send the new message to Telegram
        $telegramUrl = 'https://api.telegram.org/bot'. $botToken. '/sendMessage';
        $telegramData = array(
            'chat_id' => $chatId,
            'text' => $message
        );

        $telegramCurl = curl_init($telegramUrl);
        curl_setopt($telegramCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($telegramCurl, CURLOPT_POST, true);
        curl_setopt($telegramCurl, CURLOPT_POSTFIELDS, http_build_query($telegramData));
        $response = curl_exec($telegramCurl);
        curl_close($telegramCurl);

        // Save the new message to file or variable
        file_put_contents('previous_message.txt', $message);
    }
} else {
    $message = 'Şuanda açık randevu yok';
    // Check if the new message is the same as the previous one
    $previousMessage = file_get_contents('previous_message.txt');
    if ($message!= $previousMessage) {
        // Send the new message to Telegram
        $telegramUrl = 'https://api.telegram.org/bot'. $botToken. '/sendMessage';
        $telegramData = array(
            'chat_id' => $chatId,
            'text' => $message
        );

        $telegramCurl = curl_init($telegramUrl);
        curl_setopt($telegramCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($telegramCurl, CURLOPT_POST, true);
        curl_setopt($telegramCurl, CURLOPT_POSTFIELDS, http_build_query($telegramData));
        $response = curl_exec($telegramCurl);
        curl_close($telegramCurl);

        // Save the new message to file or variable
        file_put_contents('previous_message.txt', $message);
    }
}
?>