#!/bin/bash

# Конфигурация (заполни своими данными)
TOKEN="8749123902:AAGnWBYBrUj4pHcE2btDjtBqpMVKMFQB7yQ"
CHAT_ID="-1003463348834"

echo -e "\e[1;33m[*] Запуск комплексной проверки системы...\e[0m"

# 1. Обновление пакетов (Дрова)
echo -e "\e[1;34m[*] Проверка обновлений пакетов...\e[0m"
pkg update -y && pkg upgrade -y
pkg install php cloudflared git curl -y

# 2. Проверка целостности ключевых файлов
echo -e "\e[1;34m[*] Проверка целостности файлов...\e[0m"
FILES=("index_tt.php" "interaction.js" "core_log.php" "post.php")
for file in "${FILES[@]}"; do
    if [ ! -f "$file" ]; then
        ERROR_MSG="Критическая ошибка: Файл $file отсутствует!"
        echo -e "\e[1;31m$ERROR_MSG\e[0m"
        curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" -d "chat_id=$CHAT_ID&text=❌ Ошибка в коде: $ERROR_MSG Обратитесь к разработчику."
    fi
done

# 3. Проверка туннеля (Cloudflared)
if pgrep -x "cloudflared" > /dev/null; then
    echo -e "\e[1;32m[+] Туннель активен.\e[0m"
else
    echo -e "\e[1;31m[-] Туннель не запущен!\e[0m"
fi

# 4. Проверка связи с ботом и отправка логов в случае ошибок
if [ -f "php_errors.log" ]; then
    LOG_SIZE=$(wc -c < "php_errors.log")
    if [ "$LOG_SIZE" -gt 0 ]; then
        echo -e "\e[1;31m[!] Обнаружены ошибки в логах PHP. Отправляю...\e[0m"
        LAST_LOGS=$(tail -n 5 php_errors.log)
        curl -s -X POST "https://api.telegram.org/bot$TOKEN/sendMessage" -d "chat_id=$CHAT_ID&text=⚠️ Ошибка в коде найдена: $LAST_LOGS %0A%0A Обратитесь к разработчику!"
    fi
fi

echo -e "\e[1;32m[+] Проверка завершена. Система готова к работе.\e[0m"
sleep 3
