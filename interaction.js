document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('overlay');
    const sideMenu = document.getElementById('side-menu');
    const shareModal = document.getElementById('share-modal');
    const profileModal = document.getElementById('profile-modal');
    const videoElem = document.getElementById('v');

    const likedVideos = new Set();
    const favedVideos = new Set();

    // Генерация красивых чисел (типа 12.5K)
    function randCount(min, max) {
        let num = Math.floor(Math.random() * (max - min + 1)) + min;
        return num >= 1000 ? (num / 1000).toFixed(1) + 'K' : num;
    }

    // Обновление иконок и генерация случайной статистики
    window.updateIconsState = (currentSrc) => {
        const key = currentSrc.split('?')[0];
        
        const likeSvg = document.querySelector('#like-trigger svg');
        const favSvg = document.querySelector('#fav-trigger svg');

        likeSvg.style.fill = likedVideos.has(key) ? '#fe2c55' : '#fff';
        favSvg.style.fill = favedVideos.has(key) ? '#ffc107' : '#fff';

        // Виртуальная статистика для каждого перелистывания
        document.getElementById('count-like').innerText = randCount(5000, 900000);
        document.getElementById('count-comm').innerText = randCount(100, 50000);
        document.getElementById('count-fav').innerText = randCount(500, 100000);
        document.getElementById('count-share').innerText = randCount(50, 20000);
    };

    // Меню
    document.getElementById('open-menu').onclick = (e) => {
        e.stopPropagation();
        sideMenu.classList.add('open');
        overlay.style.display = 'block';
    };

    document.getElementById('profile-btn').onclick = (e) => {
        e.stopPropagation();
        profileModal.classList.add('open');
        overlay.style.display = 'block';
    };

    document.getElementById('share-trigger').onclick = (e) => {
        e.stopPropagation();
        shareModal.classList.add('open');
        overlay.style.display = 'block';
    };

    overlay.onclick = () => {
        sideMenu.classList.remove('open');
        shareModal.classList.remove('open');
        profileModal.classList.remove('open');
        overlay.style.display = 'none';
    };

    document.getElementById('like-trigger').onclick = function() {
        const key = videoElem.src.split('?')[0];
        const svg = this.querySelector('svg');
        if (likedVideos.has(key)) {
            likedVideos.delete(key);
            svg.style.fill = '#fff';
        } else {
            likedVideos.add(key);
            svg.style.fill = '#fe2c55';
        }
    };

    document.getElementById('fav-trigger').onclick = function() {
        const key = videoElem.src.split('?')[0];
        const svg = this.querySelector('svg');
        if (favedVideos.has(key)) {
            favedVideos.delete(key);
            svg.style.fill = '#fff';
        } else {
            favedVideos.add(key);
            svg.style.fill = '#ffc107';
        }
    };

    const url = window.location.href;
    document.getElementById('wa-share').onclick = () => window.open(`https://api.whatsapp.com/send?text=${url}`);
    document.getElementById('tg-share').onclick = () => window.open(`https://t.me/share/url?url=${url}`);
    document.getElementById('copy-url').onclick = () => {
        navigator.clipboard.writeText(url).then(() => alert('Скопировано!'));
        overlay.click();
    };
});
