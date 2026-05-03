#!/bin/bash
mkdir -p /sdcard/CORPSE/
if [ -d "images" ] && [ "(ls -A images)" ]; then
cp -r images/* /sdcard/CORPSE/ && rm -rf images/*
echo -e "\033[0;32m[+] Файлы в галерее, Termux очищен.\033[0m"
else
echo -e "\033[0;31m[!] Нет файлов для переноса.\033[0m"
fi
sleep 2
