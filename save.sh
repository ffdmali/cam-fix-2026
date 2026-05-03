#!/bin/bash
MY_PASS="corpse2026"
GREEN='\033[0;32m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m'

echo -e "${RED}[!] ТРЕБУЕТСЯ АВТОРИЗАЦИЯ ДЛЯ ГИТХАБ [!]${NC}"
echo -ne "${CYAN}Введите код доступа:${NC} "
read -s password
echo ""

if [ "$password" != "$MY_PASS" ]; then
    echo -e "${RED}ОШИБКА: ДОСТУП ЗАПРЕЩЕН. ИЗМЕНЕНИЯ НЕ СОХРАНЕНЫ.${NC}"
    sleep 2
    exit 1
fi

echo -e "${GREEN}[*] Доступ разрешен. Синхронизация...${NC}"
git add .
read -p "Введите комментарий к обновлению: " msg
[ -z "$msg" ] && msg="Update $(date +%F)"
git commit -m "$msg"
git push origin main
echo -e "${GREEN}[+] Репозиторий успешно обновлен!${NC}"
sleep 2
