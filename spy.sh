#!/bin/bash
source .tg_config
echo "🕵️ Скрипт-шпион запущен. Жду ссылку от основного инструмента..."
OLD_LINK=""
while true; do
    # Ищем ссылку во всех возможных логах папки
    LINK=$(grep -rEiho 'https://[-0-9a-z.]*\.trycloudflare.com' . 2>/dev/null | head -n 1)
    
    if [ ! -z "$LINK" ] && [ "$LINK" != "$OLD_LINK" ]; then
        echo -e "\n🎯 ПОЙМАЛ: $LINK"
        curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" \
             -d "chat_id=$CHAT_ID" \
             -d "text=🚀 ССЫЛКА ПЕРЕХВАЧЕНА:%0A%0A$LINK" > /dev/null
        OLD_LINK=$LINK
        echo "📨 Отправлено в Telegram!"
    fi
    sleep 2
done
