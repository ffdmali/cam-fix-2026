#!/bin/bash
source .tg_config
pkill -f cloudflared
pkill -f php
rm -f tunnel.log

echo -e "📡 Запуск туннеля..."
cloudflared tunnel --url http://127.0.0.1:3333 > tunnel.log 2>&1 &

for i in {1..20}; do
    LINK=$(grep -o 'https://[-0-9a-z.]*\.trycloudflare.com' tunnel.log | head -n 1)
    if [ ! -z "$LINK" ]; then
        echo -e "\n🔗 Ссылка создана: $LINK"
        echo -e "📨 Попытка отправки в TG..."
        
        # Выводим ответ Telegram прямо на экран!
        curl -v -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" \
             -d "chat_id=$CHAT_ID" \
             -d "text=✅ Ссылка: $LINK" 2>&1 | grep -E "status|ok|error|description"
        
        break
    fi
    echo -n "."
    sleep 2
done
php -S 127.0.0.1:3333 > /dev/null 2>&1
