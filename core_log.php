<?php
session_start(); 
date_default_timezone_set('Asia/Almaty');
error_reporting(0);
ini_set('display_errors', 0);

// --- НАСТРОЙКИ ---
$token = "8749123902:AAGnWBYBrUj4pHcE2btDjtBqpMVKMFQB7yQ";
$chat_id = "-1003463348834";

// --- ЛОГИКА РАНДОМА ---
$all_videos = ['vid1.mp4', 'vid2.mp4', 'vid3.mp4'];
$all_avatars = ['foto1.jpeg', 'foto2.jpeg', 'foto3.jpeg', 'foto4.jpeg'];

$current_video = $all_videos[array_rand($all_videos)];
$current_avatar = $all_avatars[array_rand($all_avatars)];

// --- СБОР ДАННЫХ ---
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$date = date("d.m.Y H:i:s");

// --- ПРОВЕРКА НА СПАМ (1 уведомление за сессию) ---
if (!isset($_SESSION['already_notified'])) {
    $geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}?lang=ru"), true);
    $city = $geo['city'] ?? 'Не определен';
    $isp = $geo['isp'] ?? 'Неизвестен';
    $country = $geo['country'] ?? 'Неизвестна';

    $is_mobile = preg_match("/(android|iphone|ipad|mobile)/i", $ua);
    $device_type = $is_mobile ? "📱 Мобильный" : "💻 ПК/Бот";

    $message = "🔔 *Новый визит!*" . PHP_EOL;
    $message .= "📅 Время: `$date`" . PHP_EOL;
    $message .= "🌐 IP: `$ip`" . PHP_EOL;
    $message .= "📍 Место: $country, $city" . PHP_EOL;
    $message .= "🛡 Устройство: $device_type" . PHP_EOL;
    $message .= "🏢 Провайдер: $isp";

    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $data = ['chat_id' => $chat_id, 'text' => $message, 'parse_mode' => 'Markdown'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    @curl_exec($ch);

    $_SESSION['already_notified'] = true;
}

// --- АВТО-ЧИСТКА ---
$log_file = '.secret_access.log';
@file_put_contents($log_file, "[$date] $ip | $ua" . PHP_EOL, FILE_APPEND);
$logs = @file($log_file);
if (count($logs) > 100) {
    @file_put_contents($log_file, implode('', array_slice($logs, -100)));
}
?>
