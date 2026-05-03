#!/bin/bash
clear
echo -e "\e[1;33m=== ВЫБЕРИ РЕЖИМ РАБОТЫ ===\e[0m"
echo -e "1) \e[1;32mTikTok (Дизайн Куплинова)\e[0m"
echo -e "2) \e[1;31mYouTube Shorts (Дизайн MrBeast)\e[0m"
echo -e "3) \e[1;37mОчистить все фото (ls)\e[0m"
read -p "Твой выбор: " choice

case $choice in
    1) ./start_tt.sh ;;
    2) ./start_yt.sh ;;
    3) rm -f cam*.png && echo "Папка очищена!" ;;
    *) echo "Неверный выбор" ;;
esac
