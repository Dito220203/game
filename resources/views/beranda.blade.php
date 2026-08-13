<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AR Bangun Ruang - Beranda</title>
    <style>
        body, html { margin: 0; padding: 0; overflow: hidden; font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif; background-color: transparent !important; }
        #menu-utama {
            position: fixed; top: 0; left: 0;
            width: 100vw;
            height: 100vh;      /* fallback untuk browser lama */
            height: 100dvh;     /* mengikuti tinggi layar HP walau ada toolbar dinamis */
            /* Latar animasi modern (selalu pas 1 layar di HP maupun laptop).
               Ingin pakai gambar sendiri? Ganti baris "background" di bawah dengan:
               background-image: url('/images/bg_menu.png'); background-size: cover; background-position: center; */
            background: linear-gradient(180deg, #6EC6FF 0%, #9FE0FF 34%, #D9F4FF 58%, #EAFBE7 100%);
            overflow: hidden;
            z-index: 100000;
            display: block;
        }

        /* ===== SCENE MENU UTAMA (tema anak-anak, responsif 1 layar) ===== */
        #menu-utama .awan { position: absolute; background: #fff; border-radius: 100px; opacity: 0.9; box-shadow: 0 8px 18px rgba(0,0,0,0.08); z-index: 1; }
        #menu-utama .awan::before, #menu-utama .awan::after { content: ""; position: absolute; background: #fff; border-radius: 50%; }
        .awan-1 { width: 120px; height: 38px; top: 10%; left: 8%; animation: geser-awan 26s ease-in-out infinite; }
        .awan-2 { width: 90px;  height: 30px; top: 20%; right: 10%; animation: geser-awan 34s ease-in-out infinite reverse; }
        .awan-1::before { width: 50px; height: 50px; top: -22px; left: 18px; }
        .awan-1::after  { width: 38px; height: 38px; top: -14px; right: 20px; }
        .awan-2::before { width: 40px; height: 40px; top: -18px; left: 14px; }
        .awan-2::after  { width: 30px; height: 30px; top: -10px; right: 16px; }
        @keyframes geser-awan { 0% { transform: translateX(0); } 50% { transform: translateX(28px); } 100% { transform: translateX(0); } }

        #menu-utama .tanah { position: absolute; bottom: 0; left: -5%; width: 110%; height: 15%; background: linear-gradient(180deg, #8FE07A 0%, #5FB74E 100%); border-top-left-radius: 50% 46px; border-top-right-radius: 50% 46px; z-index: 1; }

        .menu-judul { position: absolute; top: 7%; left: 0; width: 100%; text-align: center; z-index: 3; pointer-events: none; }
        .menu-judul .kecil { display: block; font-size: clamp(15px, 4.5vw, 26px); font-weight: 900; color: #fff; letter-spacing: 4px; text-shadow: 0 3px 0 #2b6cb0, 0 5px 6px rgba(0,0,0,0.25); }
        .menu-judul .besar { display: block; font-size: clamp(30px, 10vw, 66px); font-weight: 900; letter-spacing: 2px; color: #FFD93B; -webkit-text-stroke: 3px #2b6cb0; paint-order: stroke fill; text-shadow: 0 6px 0 #2b6cb0, 0 10px 14px rgba(0,0,0,0.30); line-height: 1.05; }

        .menu-hiasan { position: absolute; inset: 0; z-index: 2; pointer-events: none; }
        .menu-hiasan .bentuk { position: absolute; width: clamp(52px, 16vmin, 108px); height: clamp(52px, 16vmin, 108px); animation: goyang 5s ease-in-out infinite; filter: drop-shadow(0 8px 10px rgba(0,0,0,0.18)); }
        .menu-hiasan .bentuk svg { width: 100%; height: 100%; display: block; }
        .menu-hiasan .m1 { top: 24%; left: 7%;  animation-delay: 0s; }
        .menu-hiasan .m2 { top: 30%; right: 8%; animation-delay: 0.8s; }
        .menu-hiasan .m3 { top: 46%; left: 11%; animation-delay: 1.6s; }
        .menu-hiasan .m4 { top: 43%; right: 12%; animation-delay: 0.4s; }
        .menu-hiasan .m5 { top: 60%; left: 20%; animation-delay: 2.2s; }
        .menu-hiasan .m6 { top: 58%; right: 20%; animation-delay: 1.2s; }
        @keyframes goyang { 0%,100% { transform: translateY(0) rotate(-4deg); } 50% { transform: translateY(-16px) rotate(4deg); } }

        .wadah-tombol-menu { z-index: 5; }

        /* ===== BURUNG TERBANG DI LANGIT ===== */
        .burung { position: absolute; z-index: 2; width: clamp(26px, 6vmin, 46px); pointer-events: none; }
        .burung svg { width: 100%; height: auto; display: block; transform-origin: 50% 50%; animation: kepak 0.42s ease-in-out infinite; }
        .burung svg path { stroke: #5a6b7a; stroke-width: 7; fill: none; stroke-linecap: round; }
        @keyframes kepak { 0%, 100% { transform: scaleY(1); } 50% { transform: scaleY(0.5); } }
        .b-1 { top: 14%; animation: terbang-kanan 20s linear infinite; }
        .b-2 { top: 22%; width: clamp(20px, 4.6vmin, 34px); animation: terbang-kiri 26s linear infinite; animation-delay: -7s; }
        .b-3 { top: 9%;  width: clamp(18px, 4vmin, 30px);  animation: terbang-kanan 24s linear infinite; animation-delay: -13s; }
        .b-4 { top: 18%; width: clamp(22px, 5vmin, 38px);  animation: terbang-kiri 22s linear infinite; animation-delay: -3s; }
        .b-5 { top: 5%;  width: clamp(16px, 3.6vmin, 26px); animation: terbang-kanan 28s linear infinite; animation-delay: -18s; }
        @keyframes terbang-kanan { 0% { transform: translate(-18vw, 0); } 50% { transform: translate(52vw, -20px); } 100% { transform: translate(125vw, 0); } }
        @keyframes terbang-kiri  { 0% { transform: translate(125vw, 0); } 50% { transform: translate(52vw, 18px); } 100% { transform: translate(-18vw, 0); } }
        .wadah-tombol-menu { position: absolute; bottom: 25%; left: 0; width: 100%; display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; }
        .btn-menu { padding: 15px 40px; font-size: 24px; font-weight: bold; color: white; border: 4px solid #fff; border-radius: 15px; cursor: pointer; box-shadow: 0px 8px 0px rgba(0,0,0,0.2); transition: 0.1s; font-family: inherit; text-transform: uppercase; letter-spacing: 2px; }
        .btn-simulasi { background-color: #2ED175; }
        .btn-materi { background-color: #4C51F7; }
        .btn-menu:active { transform: translateY(8px); box-shadow: 0px 0px 0px rgba(0,0,0,0); }

        /* tombol menu tampil sebagai link agar bisa pindah halaman */
        .btn-menu { display: inline-block; text-decoration: none; }

        @media (max-width: 768px) {
            .menu-judul { top: 6%; }
            .menu-hiasan .m5 { top: 55%; left: 6%; }
            .menu-hiasan .m6 { top: 53%; right: 6%; }

            .wadah-tombol-menu { bottom: 18%; gap: 10px; }
            .btn-menu { font-size: 16px; padding: 12px 20px; }
        }
    </style>
</head>
<body>

    <div id="menu-utama">
        <div class="awan awan-1"></div>
        <div class="awan awan-2"></div>

        <div class="burung b-1"><svg viewBox="0 0 100 40"><path d="M8 28 Q26 6 50 24 Q74 6 92 28"/></svg></div>
        <div class="burung b-2"><svg viewBox="0 0 100 40"><path d="M8 28 Q26 6 50 24 Q74 6 92 28"/></svg></div>
        <div class="burung b-3"><svg viewBox="0 0 100 40"><path d="M8 28 Q26 6 50 24 Q74 6 92 28"/></svg></div>
        <div class="burung b-4"><svg viewBox="0 0 100 40"><path d="M8 28 Q26 6 50 24 Q74 6 92 28"/></svg></div>
        <div class="burung b-5"><svg viewBox="0 0 100 40"><path d="M8 28 Q26 6 50 24 Q74 6 92 28"/></svg></div>

        <div class="menu-judul">
            <span class="kecil">AR EDUKASI</span>
            <span class="besar">BANGUN RUANG</span>
        </div>

        <div class="menu-hiasan">
            <!-- Kubus -->
            <div class="bentuk m1"><svg viewBox="0 0 120 120"><polygon points="30,45 60,28 90,45 60,62" fill="#8ED0FF"/><polygon points="30,45 60,62 60,100 30,83" fill="#4C9BE0"/><polygon points="90,45 60,62 60,100 90,83" fill="#2E6FB0"/></svg></div>
            <!-- Bola (lucu) -->
            <div class="bentuk m2"><svg viewBox="0 0 120 120"><circle cx="60" cy="60" r="42" fill="#FF6B6B"/><ellipse cx="60" cy="74" rx="42" ry="26" fill="#FF5252" opacity="0.45"/><circle cx="47" cy="55" r="9" fill="#fff"/><circle cx="49" cy="57" r="4" fill="#333"/><circle cx="73" cy="55" r="9" fill="#fff"/><circle cx="71" cy="57" r="4" fill="#333"/><path d="M50 78 Q60 87 70 78" stroke="#7a1f1f" stroke-width="3" fill="none" stroke-linecap="round"/></svg></div>
            <!-- Kerucut (lucu) -->
            <div class="bentuk m3"><svg viewBox="0 0 120 120"><path d="M60 18 L34 92 L86 92 Z" fill="#57C84D"/><ellipse cx="60" cy="92" rx="26" ry="9" fill="#3FA637"/><circle cx="52" cy="72" r="6" fill="#fff"/><circle cx="53" cy="73" r="3" fill="#333"/><circle cx="68" cy="72" r="6" fill="#fff"/><circle cx="67" cy="73" r="3" fill="#333"/></svg></div>
            <!-- Tabung -->
            <div class="bentuk m4"><svg viewBox="0 0 120 120"><rect x="38" y="35" width="44" height="55" fill="#FFC93C"/><ellipse cx="60" cy="90" rx="22" ry="8" fill="#E9A800"/><ellipse cx="60" cy="35" rx="22" ry="8" fill="#FFE08A"/></svg></div>
            <!-- Limas / Piramida -->
            <div class="bentuk m5"><svg viewBox="0 0 120 120"><polygon points="60,22 30,92 60,80" fill="#B084F0"/><polygon points="60,22 90,92 60,80" fill="#8A5CE0"/><polygon points="30,92 90,92 60,80" fill="#6E3FC0"/></svg></div>
            <!-- Prisma segitiga -->
            <div class="bentuk m6"><svg viewBox="0 0 120 120"><polygon points="35,40 60,28 60,72 35,84" fill="#FF9F45"/><polygon points="60,28 92,40 92,84 60,72" fill="#F97316"/><polygon points="35,84 60,96 92,84 60,72" fill="#EA580C"/></svg></div>
        </div>

        <div class="tanah"></div>

        <div class="wadah-tombol-menu">
            <a class="btn-menu btn-simulasi" href="{{ url('/ar') }}">Bangun Ruang</a>
            <a class="btn-menu btn-materi" href="{{ url('/tentang') }}">Tentang</a>
        </div>
    </div>

</body>
</html>
