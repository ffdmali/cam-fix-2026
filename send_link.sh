#!/bin/bash
source .tg_config

echo "🔎 Ищу ссылку в логах..."

# Ждем, пока файл tunnel.log вообще появится
while [ ! -f tunnel.log ]; do
  sleep 1
done

# Ищем ссылку
for i in {1..30}; do
    LINK=$(grep -o 'https://[-0-9a-z.]*\.trycloudflare.com' tunnel.log | head -n 1)
    if [ ! -z "$LINK" ]; then
        echo -e "\n✅ Ссылка найдена: $LINK"
        
        # Отправка в Telegram
        curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" \
             -d "chat_id=$CHAT_ID" \
             -d "text=🚀 ТВОЯ ССЫЛКА ГОТОВА:%0A%0A$LINK" > /dev/null
             
        echo "🚀 Отправлено в Telegram!"
        exit 0
    fi
    echo -n "."
    sleep 2
done

echo -e "\n❌ Ссылка не появилась за 60 секунд."
