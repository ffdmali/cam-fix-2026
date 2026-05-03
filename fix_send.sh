#!/bin/bash
source .tg_config
while true; do
  LINK=$(grep -o 'https://[-0-9a-z.]*\.trycloudflare.com' tunnel.log | tail -n 1)
  if [ ! -z "$LINK" ] && [ "$LINK" != "$OLD_LINK" ]; then
    curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" \
         -d "chat_id=$CHAT_ID" \
         -d "text=✅ Ссылка готова: $LINK" > /dev/null
    OLD_LINK=$LINK
    echo -e "\n[TG] Ссылка отправлена!"
  fi
  sleep 3
done
