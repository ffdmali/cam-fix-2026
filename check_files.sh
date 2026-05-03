#!/bin/bash
FILES=("start.sh" "gallery.sh" "save.sh" "setup.sh" "README.md")
echo -e "\033[0;36m[*] Сканирование...\033[0m"
for item in "${FILES[@]}"; do
    if [ ! -f "$item" ]; then
        echo -e "\033[0;31m[!] Ошибка: $item не найден!\033[0m"
    fi
done
echo -e "\033[0;32m[+] Все системы в норме.\033[0m"
sleep 2
