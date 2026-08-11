<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AR Bangun Ruang - Edukasi</title>

    <script src="https://aframe.io/releases/1.3.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.2/dist/mindar-image-aframe.prod.js"></script>

    <style>
        body, html { margin: 0; padding: 0; overflow: hidden; font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif; background-color: transparent !important; }
        .mindar-ui-overlay, .mindar-ui-loading, .mindar-ui-scanning, .mindar-ui-error { display: none !important; opacity: 0 !important; visibility: hidden !important; z-index: -9999 !important; }

        /* ===== BACKGROUND MENU RESPONSIF (HP / TABLET / LAPTOP) ===== */
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

        /* ================= HALAMAN TENTANG (VERSI MODERN + RESPONSIF) ================= */
        #menu-tentang {
            display: none; position: fixed; top: 0; left: 0;
            width: 100vw; height: 100vh; height: 100dvh;
            background: linear-gradient(135deg, #5B6BF0 0%, #00BFFF 55%, #2ED175 100%);
            z-index: 100001; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; padding: 20px; box-sizing: border-box; overflow: hidden;
        }

        /* Lapisan lukisan bangun ruang melayang di belakang kartu */
        .tentang-hiasan { position: absolute; inset: 0; z-index: 1; overflow: hidden; pointer-events: none; }
        .tentang-hiasan .bentuk { position: absolute; opacity: 0.20; animation: apung 6s ease-in-out infinite; }
        .tentang-hiasan .bentuk svg { display: block; width: 100%; height: 100%; }
        .tentang-hiasan .b1 { width: 120px; height: 120px; top: 6%;  left: 6%;  animation-delay: 0s; }
        .tentang-hiasan .b2 { width: 90px;  height: 90px;  top: 12%; right: 8%; animation-delay: 1.2s; }
        .tentang-hiasan .b3 { width: 110px; height: 110px; bottom: 8%;  left: 10%; animation-delay: 0.6s; }
        .tentang-hiasan .b4 { width: 100px; height: 100px; bottom: 10%; right: 7%; animation-delay: 1.8s; }
        .tentang-hiasan .b5 { width: 80px;  height: 80px;  top: 46%; left: 3%;  animation-delay: 2.4s; }
        .tentang-hiasan .b6 { width: 85px;  height: 85px;  top: 40%; right: 3%; animation-delay: 3s; }
        @keyframes apung { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-20px) rotate(8deg); } }

        /* Kartu isi (glassmorphism) supaya teks tetap jelas & bisa di-scroll di HP */
        .tentang-kartu {
            position: relative; z-index: 2;
            width: 100%; max-width: 640px;
            background: rgba(255,255,255,0.94);
            -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
            border: 5px solid rgba(255,255,255,0.85); border-radius: 30px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.30);
            padding: 26px 26px; box-sizing: border-box;
            transform-origin: center center; will-change: transform;
        }
        .tentang-judul { color: #2b2b7a; margin: 0 0 20px; font-size: 32px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; }
        .tentang-teks { font-size: 20px; color: #2d2d2d; line-height: 1.75; margin-bottom: 28px; font-weight: 800; }
        .tentang-teks span { color: #4C51F7; font-weight: 900; }

        .ui-layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; pointer-events: none; }
        .ui-container { position: absolute; top: 20px; right: 20px; display: flex; flex-direction: column; gap: 15px; pointer-events: auto; }
        .btn { padding: 12px 20px; background-color: #00BFFF; color: white; border: 2px solid white; border-radius: 10px; font-size: 16px; font-weight: 800; cursor: pointer; box-shadow: 2px 2px 5px rgba(0,0,0,0.3); text-align: center; min-width: 130px; font-family: inherit; }
        .btn-merah { background-color: #FF4C4C; }
        .btn:active { transform: scale(0.95); }

        #panel-rumus { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(43, 160, 216, 0.95); color: yellow; font-weight: bold; padding: 40px; box-sizing: border-box; pointer-events: auto; overflow-y: auto; }
        .rumus-content { display: flex; justify-content: space-around; align-items: center; height: 100%; width: 100%; }
        .kotak-rumus { border: 3px solid white; border-radius: 15px; padding: 20px; font-size: 24px; margin-bottom: 20px; min-width: 300px; background-color: rgba(0,0,0,0.2); box-shadow: 4px 4px 0px rgba(0,0,0,0.1); }
        .kotak-rumus span { color: white; font-size: 28px; display: block; margin-top: 10px; }
        .kiri-keterangan { color: white; font-size: 18px; text-align: center; display: flex; flex-direction: column; align-items: center;}
        .kiri-keterangan p { text-align: left; background-color: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px; width: 100%; box-sizing: border-box; }

        /* NOTIFIKASI POP-UP KETIKA JARING TIDAK TERSEDIA */
        #notif-popup {
            position: fixed; top: 20px; left: 50%;
            transform: translate(-50%, -300%); /* tersembunyi total di atas layar, berapa pun tinggi teks */
            background-color: #FF4C4C; color: white; font-weight: bold; font-size: 18px;
            padding: 15px 30px; border-radius: 20px; border: 3px solid white;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.3); z-index: 100002;
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            text-align: center; width: 80%; max-width: 400px; box-sizing: border-box;
        }
        #notif-popup.tampil { transform: translate(-50%, 0); }

        @media (max-width: 768px) {
            /* ===== SCENE MENU UTAMA dirapikan untuk layar HP ===== */
            .menu-judul { top: 6%; }
            .menu-hiasan .m5 { top: 55%; left: 6%; }
            .menu-hiasan .m6 { top: 53%; right: 6%; }

            .wadah-tombol-menu { bottom: 18%; gap: 10px; }
            .btn-menu { font-size: 16px; padding: 12px 20px; }

            /* ===== TOMBOL AR (Rumus/Jaring/Rusuk/Keluar) PINDAH KE BAWAH + FONT LEBIH TEBAL ===== */
            .ui-container { flex-direction: row; flex-wrap: wrap; justify-content: center; top: auto; bottom: 12px; right: 0; left: 0; width: 100%; padding: 0 10px; box-sizing: border-box; }
            .btn { min-width: 80px; padding: 11px 8px; font-size: 15px; font-weight: 900; flex: 1; }

            /* ===== HALAMAN TENTANG responsif di HP ===== */
            .tentang-kartu { max-width: 92%; padding: 20px 18px; border-radius: 24px; }
            .tentang-judul { font-size: 24px; }
            .tentang-teks { font-size: 16px; line-height: 1.65; }
            .tentang-hiasan .bentuk { opacity: 0.15; }
            .tentang-hiasan .b1, .tentang-hiasan .b2, .tentang-hiasan .b3, .tentang-hiasan .b4 { width: 70px; height: 70px; }
            .tentang-hiasan .b5, .tentang-hiasan .b6 { width: 55px; height: 55px; }

            .rumus-content { flex-direction: column; justify-content: flex-start; height: auto; padding-bottom: 80px; }
            #panel-rumus { padding: 20px 15px; }
            .kotak-rumus { min-width: 100%; font-size: 18px; padding: 15px; box-sizing: border-box; }
            .kotak-rumus span { font-size: 20px; }
            .kiri-keterangan { margin-top: 50px; margin-bottom: 20px; width: 100%; }
        }
    </style>
</head>
<body>

    <audio id="musik-latar" loop>
        <source src="/audio/musik_anak.mp3" type="audio/mpeg">
    </audio>
    <div id="notif-popup">Pesan dari AI muncul di sini!</div>

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
            <button class="btn-menu btn-simulasi" id="tombol-mulai-ar">Bangun Ruang</button>
            <button class="btn-menu btn-materi" id="tombol-buka-tentang">Tentang</button>
        </div>
    </div>

    <div id="menu-tentang">
        <!-- Lukisan bangun ruang melayang di latar -->
        <div class="tentang-hiasan">
            <div class="bentuk b1"><svg viewBox="0 0 100 100"><g fill="none" stroke="#fff" stroke-width="4" stroke-linejoin="round"><rect x="20" y="32" width="45" height="45"/><path d="M20 32 L40 15 L85 15 L65 32"/><path d="M65 32 L65 77 L85 60 L85 15"/></g></svg></div>
            <div class="bentuk b2"><svg viewBox="0 0 100 100"><g fill="none" stroke="#fff" stroke-width="4"><circle cx="50" cy="50" r="35"/><ellipse cx="50" cy="50" rx="35" ry="13"/></g></svg></div>
            <div class="bentuk b3"><svg viewBox="0 0 100 100"><g fill="none" stroke="#fff" stroke-width="4"><ellipse cx="50" cy="25" rx="30" ry="12"/><path d="M20 25 L20 75"/><path d="M80 25 L80 75"/><path d="M20 75 A30 12 0 0 0 80 75"/></g></svg></div>
            <div class="bentuk b4"><svg viewBox="0 0 100 100"><g fill="none" stroke="#fff" stroke-width="4" stroke-linejoin="round"><path d="M50 12 L20 78"/><path d="M50 12 L80 78"/><ellipse cx="50" cy="78" rx="30" ry="11"/></g></svg></div>
            <div class="bentuk b5"><svg viewBox="0 0 100 100"><g fill="none" stroke="#fff" stroke-width="4" stroke-linejoin="round"><path d="M50 12 L15 75 L60 88 L85 55 Z"/><path d="M50 12 L60 88"/><path d="M15 75 L85 55"/></g></svg></div>
            <div class="bentuk b6"><svg viewBox="0 0 100 100"><g fill="none" stroke="#fff" stroke-width="4" stroke-linejoin="round"><path d="M25 30 L50 15 L50 58 L25 73 Z"/><path d="M50 15 L85 25 L85 68 L50 58"/><path d="M25 73 L60 83 L85 68"/></g></svg></div>
        </div>

        <div class="tentang-kartu">
            <h2 class="tentang-judul">TENTANG APLIKASI</h2>
            <div class="tentang-teks">
                Peneliti<br><span>Umik Jubaedah, S.Pd</span><br><br>
                Judul Penelitian:<br><span>Pengaruh Model Discovery Learning Berbantuan Augmented Reality<br>terhadap Literasi Numerasi dan Hasil belajar kognitif melalui<br>Kemampuan Visual-Spasial pada Materi Bangun Ruang</span><br><br>
                Dosen Pembimbing<br><span>Dr. Rina Sugiarti Dwi Gita, M.Si<br>Dr. Fauzan Adhim, M.Pd.I</span><br><br>
                Programmer & Desain<br><span>Dito Febriansyah A.md.Kom</span><br><br>
                Terimakasih Kepada<br><span>Allah SWT<br>Universitas PGRI Argopuro Jember</span><br><br>
                Musik<br><span>https://youtu.be/PcB84rIl9ns?si=uyujJu-FgDnRU5be</span>
            </div>
            <button class="btn-menu btn-simulasi" id="tombol-tutup-tentang" style="width: auto; padding: 10px 40px;">Kembali</button>
        </div>
    </div>

    <div class="ui-layer">
        <div class="ui-container">
            <button class="btn" id="btn-rumus">Rumus</button>
            <button class="btn" id="btn-jaring">Jaring Jaring</button>
            <button class="btn" id="btn-rusuk">Rusuk</button>
            <button class="btn btn-merah" id="btn-kembali-menu">Keluar</button>
        </div>

        <div id="panel-rumus">
            <div class="rumus-content">
                <div class="kiri-keterangan" id="wadah-keterangan"></div>
                <div id="wadah-rumus" style="width: 100%; max-width: 500px;">
                    <div class="kotak-rumus" id="kotak-rumus-1"></div>
                    <div class="kotak-rumus" id="kotak-rumus-2"></div>
                </div>
            </div>
            <button class="btn btn-merah" id="btn-tutup-rumus" style="position: fixed; bottom: 20px; right: 20px; z-index: 10;">Tutup</button>
        </div>
    </div>

    <!-- KUMPULAN 16 OBJEK 3D DENGAN ENGSEL ANIMASI LENGKAP -->
    @php
        $daftar_objek = [
            0 => '<a-plane class="objek-biasa" position="0 0 0" width="0.8" height="0.6" material="color: #8B4513; side: double;"></a-plane>',

            // 1: BALOK
            1 => '
            <a-entity class="grup-bangun">
                <a-box class="objek-padat" position="0 0 0" width="0.8" height="0.4" depth="0.4" material="color: #00BFFF; opacity: 1;"></a-box>
                <a-entity class="objek-jaring" visible="false" position="0 -0.2 0" rotation="-90 0 0">
                    <a-plane position="0 0 0" width="0.8" height="0.4" material="color: #4C51F7; side: double;"></a-plane>
                    <a-entity position="0 -0.2 0" class="engsel engsel-depan" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="1600" data-delay-tutup="0"><a-plane position="0 -0.2 0" width="0.8" height="0.4" material="color: #FF4C4C; side: double;"></a-plane></a-entity>
                    <a-entity position="0 0.2 0" class="engsel engsel-belakang" rotation="90 0 0" data-tutup="90 0 0" data-delay-buka="1600" data-delay-tutup="0">
                        <a-plane position="0 0.2 0" width="0.8" height="0.4" material="color: #FF4C4C; side: double;"></a-plane>
                        <a-entity position="0 0.4 0" class="engsel engsel-atas" rotation="90 0 0" data-tutup="90 0 0" data-delay-buka="0" data-delay-tutup="1600"><a-plane position="0 0.2 0" width="0.8" height="0.4" material="color: #4C51F7; side: double;"></a-plane></a-entity>
                    </a-entity>
                    <a-entity position="-0.4 0 0" class="engsel engsel-kiri" rotation="0 90 0" data-tutup="0 90 0" data-delay-buka="800" data-delay-tutup="800"><a-plane position="-0.2 0 0" width="0.4" height="0.4" material="color: #2ED175; side: double;"></a-plane></a-entity>
                    <a-entity position="0.4 0 0" class="engsel engsel-kanan" rotation="0 -90 0" data-tutup="0 -90 0" data-delay-buka="800" data-delay-tutup="800"><a-plane position="0.2 0 0" width="0.4" height="0.4" material="color: #2ED175; side: double;"></a-plane></a-entity>
                </a-entity>
            </a-entity>
            ',

            2 => '<a-sphere class="objek-biasa" position="0 0 0" radius="0.4" material="color: #FF4C4C; opacity: 1;"></a-sphere>',

            // 3: KUBUS
            3 => '
            <a-entity class="grup-bangun">
                <a-box class="objek-padat" position="0 0 0" width="0.5" height="0.5" depth="0.5" material="color: #32CD32; opacity: 1;"></a-box>
                <a-entity class="objek-jaring" visible="false" position="0 -0.25 0" rotation="-90 0 0">
                    <a-plane position="0 0 0" width="0.5" height="0.5" material="color: #4C51F7; side: double;"></a-plane>
                    <a-entity position="0 -0.25 0" class="engsel engsel-depan" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="1600" data-delay-tutup="0"><a-plane position="0 -0.25 0" width="0.5" height="0.5" material="color: #FF4C4C; side: double;"></a-plane></a-entity>
                    <a-entity position="0 0.25 0" class="engsel engsel-belakang" rotation="90 0 0" data-tutup="90 0 0" data-delay-buka="1600" data-delay-tutup="0">
                        <a-plane position="0 0.25 0" width="0.5" height="0.5" material="color: #FF4C4C; side: double;"></a-plane>
                        <a-entity position="0 0.5 0" class="engsel engsel-atas" rotation="90 0 0" data-tutup="90 0 0" data-delay-buka="0" data-delay-tutup="1600"><a-plane position="0 0.25 0" width="0.5" height="0.5" material="color: #4C51F7; side: double;"></a-plane></a-entity>
                    </a-entity>
                    <a-entity position="-0.25 0 0" class="engsel engsel-kiri" rotation="0 90 0" data-tutup="0 90 0" data-delay-buka="800" data-delay-tutup="800"><a-plane position="-0.25 0 0" width="0.5" height="0.5" material="color: #FFFF00; side: double;"></a-plane></a-entity>
                    <a-entity position="0.25 0 0" class="engsel engsel-kanan" rotation="0 -90 0" data-tutup="0 -90 0" data-delay-buka="800" data-delay-tutup="800"><a-plane position="0.25 0 0" width="0.5" height="0.5" material="color: #FFFF00; side: double;"></a-plane></a-entity>
                </a-entity>
            </a-entity>
            ',

            // 4: LIMAS SEGIEMPAT
            4 => '
            <a-entity class="grup-bangun">
                <a-cone class="objek-padat" position="0 0 0" radius-bottom="0.4" height="0.6" segments-radial="4" rotation="0 45 0" material="color: #8A2BE2; opacity: 1;"></a-cone>
                <a-entity class="objek-jaring" visible="false" position="0 -0.3 0" rotation="-90 0 0">
                    <a-plane position="0 0 0" width="0.56" height="0.56" material="color: #4C51F7; side: double;"></a-plane>
                    <a-entity position="0 -0.28 0" class="engsel engsel-depan" rotation="-117.8 0 0" data-tutup="-117.8 0 0" data-delay-buka="0" data-delay-tutup="800"><a-triangle vertex-a="0 -0.6 0" vertex-b="-0.28 0 0" vertex-c="0.28 0 0" material="color: #FF4C4C; side: double;"></a-triangle></a-entity>
                    <a-entity position="0 0.28 0" class="engsel engsel-belakang" rotation="117.8 0 0" data-tutup="117.8 0 0" data-delay-buka="0" data-delay-tutup="800"><a-triangle vertex-a="0 0.6 0" vertex-b="0.28 0 0" vertex-c="-0.28 0 0" material="color: #FF4C4C; side: double;"></a-triangle></a-entity>
                    <a-entity position="-0.28 0 0" class="engsel engsel-kiri" rotation="0 117.8 0" data-tutup="0 117.8 0" data-delay-buka="800" data-delay-tutup="0"><a-triangle vertex-a="-0.6 0 0" vertex-b="0 -0.28 0" vertex-c="0 0.28 0" material="color: #FFFF00; side: double;"></a-triangle></a-entity>
                    <a-entity position="0.28 0 0" class="engsel engsel-kanan" rotation="0 -117.8 0" data-tutup="0 -117.8 0" data-delay-buka="800" data-delay-tutup="0"><a-triangle vertex-a="0.6 0 0" vertex-b="0 0.28 0" vertex-c="0 -0.28 0" material="color: #FFFF00; side: double;"></a-triangle></a-entity>
                </a-entity>
            </a-entity>
            ',

            // 5: LIMAS SEGITIGA
            5 => '
            <a-entity class="grup-bangun">
                <a-cone class="objek-padat" position="0 0 0" radius-bottom="0.4" height="0.6" segments-radial="3" material="color: #FFA500; opacity: 1;"></a-cone>
                <a-entity class="objek-jaring" visible="false" position="0 -0.3 0" rotation="-90 0 0">
                    <a-triangle vertex-a="0 0.4 0" vertex-b="-0.346 -0.2 0" vertex-c="0.346 -0.2 0" material="color: #4C51F7; side: double;"></a-triangle>
                    <a-entity rotation="0 0 0"><a-entity position="0 -0.2 0" class="engsel engsel-depan" rotation="-108 0 0" data-tutup="-108 0 0" data-delay-buka="0" data-delay-tutup="800"><a-triangle vertex-a="0 -0.63 0" vertex-b="-0.346 0 0" vertex-c="0.346 0 0" material="color: #FF4C4C; side: double;"></a-triangle></a-entity></a-entity>
                    <a-entity rotation="0 0 120"><a-entity position="0 -0.2 0" class="engsel engsel-kiri" rotation="-108 0 0" data-tutup="-108 0 0" data-delay-buka="800" data-delay-tutup="0"><a-triangle vertex-a="0 -0.63 0" vertex-b="-0.346 0 0" vertex-c="0.346 0 0" material="color: #FFFF00; side: double;"></a-triangle></a-entity></a-entity>
                    <a-entity rotation="0 0 -120"><a-entity position="0 -0.2 0" class="engsel engsel-kanan" rotation="-108 0 0" data-tutup="-108 0 0" data-delay-buka="800" data-delay-tutup="0"><a-triangle vertex-a="0 -0.63 0" vertex-b="-0.346 0 0" vertex-c="0.346 0 0" material="color: #FF4C4C; side: double;"></a-triangle></a-entity></a-entity>
                </a-entity>
            </a-entity>
            ',

            // 6: PRISMA SEGIENAM  ===== ANIMASI BUKA/TUTUP DIPERBAIKI =====
            // Urutan BUKA : tutup atas melipat rata dulu (delay 0), lalu ke-6 sisi turun bersamaan (delay 800).
            // Urutan TUTUP: ke-6 sisi berdiri dulu (delay 0), lalu tutup atas mengunci (delay 800).
            6 => '
            <a-entity class="grup-bangun">
                <a-cylinder class="objek-padat" position="0 0 0" radius="0.4" height="0.6" segments-radial="6" material="color: #FF69B4; opacity: 1;"></a-cylinder>
                <a-entity class="objek-jaring" visible="false" position="0 -0.3 0" rotation="-90 0 0">
                    <a-circle radius="0.4" segments="6" rotation="0 0 30" material="color: #4C51F7; side: double;"></a-circle>
                    <a-entity rotation="0 0 30">
                        <a-entity position="0 -0.346 0" class="engsel engsel-depan" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800">
                            <a-plane position="0 -0.3 0" width="0.4" height="0.6" material="color: #2ED175; side: double;"></a-plane>
                            <a-entity position="0 -0.6 0" class="engsel engsel-atas" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="800" data-delay-tutup="0"><a-circle position="0 -0.346 0" radius="0.4" segments="6" rotation="0 0 0" material="color: #FF4C4C; side: double;"></a-circle></a-entity>
                        </a-entity>
                    </a-entity>
                    <a-entity rotation="0 0 90"><a-entity position="0 -0.346 0" class="engsel engsel-kiri" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-plane position="0 -0.3 0" width="0.4" height="0.6" material="color: #FFFF00; side: double;"></a-plane></a-entity></a-entity>
                    <a-entity rotation="0 0 150"><a-entity position="0 -0.346 0" class="engsel engsel-kiri" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-plane position="0 -0.3 0" width="0.4" height="0.6" material="color: #2ED175; side: double;"></a-plane></a-entity></a-entity>
                    <a-entity rotation="0 0 210"><a-entity position="0 -0.346 0" class="engsel engsel-belakang" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-plane position="0 -0.3 0" width="0.4" height="0.6" material="color: #FFFF00; side: double;"></a-plane></a-entity></a-entity>
                    <a-entity rotation="0 0 270"><a-entity position="0 -0.346 0" class="engsel engsel-kanan" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-plane position="0 -0.3 0" width="0.4" height="0.6" material="color: #2ED175; side: double;"></a-plane></a-entity></a-entity>
                    <a-entity rotation="0 0 330"><a-entity position="0 -0.346 0" class="engsel engsel-kanan" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-plane position="0 -0.3 0" width="0.4" height="0.6" material="color: #FFFF00; side: double;"></a-plane></a-entity></a-entity>
                </a-entity>
            </a-entity>
            ',

            // 7: PRISMA SEGITIGA
            7 => '
            <a-entity class="grup-bangun">
                <a-cylinder class="objek-padat" position="0 0 0" radius="0.4" height="0.6" segments-radial="3" rotation="0 -30 0" material="color: #00CED1; opacity: 1;"></a-cylinder>
                <a-entity class="objek-jaring" visible="false" position="0 -0.3 0" rotation="-90 0 0">
                    <a-plane position="0 0 0" width="0.7" height="0.6" material="color: #4C51F7; side: double;"></a-plane>
                    <a-entity position="-0.35 0 0" class="engsel engsel-kiri" rotation="0 120 0" data-tutup="0 120 0" data-delay-buka="800" data-delay-tutup="0"><a-plane position="-0.35 0 0" width="0.7" height="0.6" material="color: #FF4C4C; side: double;"></a-plane></a-entity>
                    <a-entity position="0.35 0 0" class="engsel engsel-kanan" rotation="0 -120 0" data-tutup="0 -120 0" data-delay-buka="800" data-delay-tutup="0"><a-plane position="0.35 0 0" width="0.7" height="0.6" material="color: #FF4C4C; side: double;"></a-plane></a-entity>
                    <a-entity position="0 0.3 0" class="engsel engsel-belakang" rotation="90 0 0" data-tutup="90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-triangle vertex-a="0 0.606 0" vertex-b="0.35 0 0" vertex-c="-0.35 0 0" material="color: #FFFF00; side: double;"></a-triangle></a-entity>
                    <a-entity position="0 -0.3 0" class="engsel engsel-depan" rotation="-90 0 0" data-tutup="-90 0 0" data-delay-buka="0" data-delay-tutup="800"><a-triangle vertex-a="0 -0.606 0" vertex-b="-0.35 0 0" vertex-c="0.35 0 0" material="color: #FFFF00; side: double;"></a-triangle></a-entity>
                </a-entity>
            </a-entity>
            ',

            8 => '<a-cylinder class="objek-biasa" position="0 0 0" radius="0.3" height="0.6" material="color: #FFD700; opacity: 1;"></a-cylinder>',
            9 => '<a-plane class="objek-biasa" position="0 0 0" width="0.8" height="0.5" material="color: #DAA520; side: double;"></a-plane>',
            10 => '<a-plane class="objek-biasa" position="0 0 0" width="0.5" height="0.5" rotation="0 0 45" material="color: #32CD32; side: double;"></a-plane>',
            11 => '<a-plane class="objek-biasa" position="0 0 0" width="0.4" height="0.7" rotation="0 0 45" material="color: #9370DB; side: double;"></a-plane>',
            12 => '<a-circle class="objek-biasa" position="0 0 0" radius="0.4" material="color: #FF4500; side: double;"></a-circle>',
            13 => '<a-plane class="objek-biasa" position="0 0 0" width="0.5" height="0.5" material="color: #0000CD; side: double;"></a-plane>',
            14 => '<a-plane class="objek-biasa" position="0 0 0" width="0.8" height="0.4" material="color: #228B22; side: double;"></a-plane>',
            15 => '<a-triangle class="objek-biasa" position="0 0 0" vertex-a="0 0.4 0" vertex-b="-0.4 -0.4 0" vertex-c="0.4 -0.4 0" material="color: #DAA520; side: double;"></a-triangle>'
        ];
    @endphp

    <a-scene mindar-image="imageTargetSrc: /marker/targets.mind; uiLoading: no; uiScanning: no; uiError: no; filterMinCF: 0.0005; filterBeta: 0.001;" color-space="sRGB" renderer="antialias: true; colorManagement: true; highQuality: true; physicallyCorrectLights: true;" vr-mode-ui="enabled: false" device-orientation-permission-ui="enabled: false">
        <a-camera position="0 0 0" look-controls="enabled: false"></a-camera>

        @foreach ($daftar_objek as $index => $bentuk_3d)
            <a-entity class="pelacak-marker" mindar-image-target="targetIndex: {{ $index }}" data-index="{{ $index }}">
                {!! $bentuk_3d !!}
            </a-entity>
        @endforeach
    </a-scene>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseMarkerUrl = "/marker";

            const dataRumus = {
                0: { nama: "Trapesium", img: "trapesium_marker.jpg", ket: "a, b : Sisi sejajar<br>t : Tinggi", rumus1: "Luas :<br><span>L = 1/2 &times; (a + b) &times; t</span>", rumus2: "Keliling :<br><span>K = a + b + c + d</span>", suara: "Ini adalah Trapesium. Rumus luasnya adalah, setengah dikali sisi sejajar a ditambah b, dikali tinggi. Kelilingnya adalah, sisi a ditambah b ditambah c ditambah d." },
                1: { nama: "Balok", img: "balok_marker.jpg", ket: "p : Panjang<br>l : Lebar<br>t : Tinggi", rumus1: "Volume :<br><span>V = p &times; l &times; t</span>", rumus2: "Luas Permukaan :<br><span>L = 2 &times; ((p&times;l) + (p&times;t) + (l&times;t))</span>", suara: "Ini adalah Balok. Rumus volumenya adalah panjang, dikali lebar, dikali tinggi. Luas permukaannya adalah dua dikali panjang kali lebar, ditambah panjang kali tinggi, ditambah lebar kali tinggi." },
                2: { nama: "Bola", img: "bola_marker.jpg", ket: "r : Jari-jari<br>&pi; : 22/7 atau 3.14", rumus1: "Volume :<br><span>V = 4/3 &times; &pi; &times; r&sup3;</span>", rumus2: "Luas Permukaan :<br><span>L = 4 &times; &pi; &times; r&sup2;</span>", suara: "Ini adalah Bola. Rumus volumenya adalah empat per tiga, dikali pi, dikali jari-jari pangkat tiga. Luas permukaannya adalah empat dikali pi dikali jari-jari kuadrat." },
                3: { nama: "Kubus", img: "kubus_marker.jpg", ket: "s : Panjang Rusuk", rumus1: "Volume :<br><span>V = s &times; s &times; s</span>", rumus2: "Luas Permukaan :<br><span>L = 6 &times; s&sup2;</span>", suara: "Ini adalah Kubus. Rumus volumenya adalah sisi dikali sisi dikali sisi. Luas permukaannya adalah enam dikali sisi kuadrat." },
                4: { nama: "Limas Segiempat", img: "limas_segiempat_marker.jpg", ket: "La : Luas Alas<br>t : Tinggi Limas", rumus1: "Volume :<br><span>V = 1/3 &times; La &times; t</span>", rumus2: "Luas Permukaan :<br><span>L = La + Luas Selubung</span>", suara: "Ini adalah Limas Segi empat. Rumus volumenya adalah sepertiga dikali luas alas dikali tinggi. Luas permukaannya adalah luas alas ditambah luas selubung tegak." },
                5: { nama: "Limas Segitiga", img: "limas_segitiga_marker.jpg", ket: "La : Luas Alas<br>t : Tinggi Limas", rumus1: "Volume :<br><span>V = 1/3 &times; La &times; t</span>", rumus2: "Luas Permukaan :<br><span>L = La + Luas Selubung</span>", suara: "Ini adalah Limas Segitiga. Rumus volumenya adalah sepertiga dikali luas alas dikali tinggi. Luas permukaannya adalah luas alas ditambah luas selubung tegak." },
                6: { nama: "Prisma Segienam", img: "prisma_segienam.jpg", ket: "La : Luas Alas<br>Ka : Keliling Alas<br>t : Tinggi Prisma", rumus1: "Volume :<br><span>V = La &times; t</span>", rumus2: "Luas Permukaan :<br><span>L = (2 &times; La) + (Ka &times; t)</span>", suara: "Ini adalah Prisma Segi enam. Rumus volumenya adalah luas alas dikali tinggi. Luas permukaannya adalah dua dikali luas alas, ditambah keliling alas dikali tinggi." },
                7: { nama: "Prisma Segitiga", img: "prisma_segitiga_marker.jpg", ket: "La : Luas Alas<br>Ka : Keliling Alas<br>t : Tinggi Prisma", rumus1: "Volume :<br><span>V = La &times; t</span>", rumus2: "Luas Permukaan :<br><span>L = (2 &times; La) + (Ka &times; t)</span>", suara: "Ini adalah Prisma Segitiga. Rumus volumenya adalah luas alas dikali tinggi. Luas permukaannya adalah dua dikali luas alas, ditambah keliling alas dikali tinggi." },
                8: { nama: "Tabung", img: "tabung_marker.jpg", ket: "r : Jari-jari Alas<br>t : Tinggi Tabung<br>&pi; : 22/7 atau 3.14", rumus1: "Volume :<br><span>V = &pi; &times; r&sup2; &times; t</span>", rumus2: "Luas Permukaan :<br><span>L = 2 &times; &pi; &times; r &times; (r + t)</span>", suara: "Ini adalah Tabung. Rumus volumenya adalah pi dikali jari-jari kuadrat dikali tinggi. Luas permukaannya adalah dua dikali pi dikali jari-jari, dikali dalam kurung jari-jari ditambah tinggi." },
                9: { nama: "Jajar Genjang", img: "jajargenjang_marker.jpg", ket: "a : Alas<br>t : Tinggi<br>m : Sisi Miring", rumus1: "Luas :<br><span>L = a &times; t</span>", rumus2: "Keliling :<br><span>K = 2 &times; (a + m)</span>", suara: "Ini adalah Jajar Genjang. Rumus luasnya adalah alas dikali tinggi. Kelilingnya adalah dua dikali alas ditambah sisi miring." },
                10: { nama: "Belah Ketupat", img: "ketupat_marker.jpg", ket: "d1, d2 : Diagonal<br>s : Panjang Sisi", rumus1: "Luas :<br><span>L = 1/2 &times; d1 &times; d2</span>", rumus2: "Keliling :<br><span>K = 4 &times; s</span>", suara: "Ini adalah Belah Ketupat. Rumus luasnya adalah setengah dikali diagonal satu dikali diagonal dua. Kelilingnya adalah empat dikali sisi." },
                11: { nama: "Layang-Layang", img: "layang_marker.jpg", ket: "d1, d2 : Diagonal<br>a, b : Sisi miring", rumus1: "Luas :<br><span>L = 1/2 &times; d1 &times; d2</span>", rumus2: "Keliling :<br><span>K = 2 &times; (a + b)</span>", suara: "Ini adalah Layang-layang. Rumus luasnya adalah setengah dikali diagonal satu dikali diagonal dua. Kelilingnya adalah dua dikali sisi miring a ditambah b." },
                12: { nama: "Lingkaran", img: "lingkaran_marker.jpg", ket: "r : Jari-jari<br>d : Diameter<br>&pi; : 22/7 atau 3.14", rumus1: "Luas :<br><span>L = &pi; &times; r&sup2;</span>", rumus2: "Keliling :<br><span>K = 2 &times; &pi; &times; r</span>", suara: "Ini adalah Lingkaran. Rumus luasnya adalah pi dikali jari-jari kuadrat. Kelilingnya adalah dua dikali pi dikali jari-jari." },
                13: { nama: "Persegi", img: "persegi_marker.jpg", ket: "s : Panjang Sisi", rumus1: "Luas :<br><span>L = s &times; s</span>", rumus2: "Keliling :<br><span>K = 4 &times; s</span>", suara: "Ini adalah Persegi. Rumus luasnya adalah sisi dikali sisi. Kelilingnya adalah empat dikali sisi." },
                14: { nama: "Persegi Panjang", img: "persegipanjang_marker.jpg", ket: "p : Panjang<br>l : Lebar", rumus1: "Luas :<br><span>L = p &times; l</span>", rumus2: "Keliling :<br><span>K = 2 &times; (p + l)</span>", suara: "Ini adalah Persegi Panjang. Rumus luasnya adalah panjang dikali lebar. Kelilingnya adalah dua dikali panjang ditambah lebar." },
                15: { nama: "Segitiga", img: "segitiga_marker.jpg", ket: "a : Alas<br>t : Tinggi<br>a,b,c : Panjang Sisi", rumus1: "Luas :<br><span>L = 1/2 &times; a &times; t</span>", rumus2: "Keliling :<br><span>K = a + b + c</span>", suara: "Ini adalah Segitiga. Rumus luasnya adalah setengah dikali alas dikali tinggi. Kelilingnya adalah sisi a ditambah sisi b ditambah sisi c." }
            };

            const dataSudut = {
                0: [ {t:'A', p:'-0.45 -0.35 0.05'}, {t:'B', p:'0.45 -0.35 0.05'}, {t:'C', p:'0.45 0.35 0.05'}, {t:'D', p:'-0.45 0.35 0.05'} ],
                1: [ {t:'A', p:'-0.45 -0.25 0.25'}, {t:'B', p:'0.45 -0.25 0.25'}, {t:'C', p:'0.45 -0.25 -0.25'}, {t:'D', p:'-0.45 -0.25 -0.25'},
                     {t:'E', p:'-0.45 0.25 0.25'}, {t:'F', p:'0.45 0.25 0.25'}, {t:'G', p:'0.45 0.25 -0.25'}, {t:'H', p:'-0.45 0.25 -0.25'} ],
                3: [ {t:'A', p:'-0.3 -0.3 0.3'}, {t:'B', p:'0.3 -0.3 0.3'}, {t:'C', p:'0.3 -0.3 -0.3'}, {t:'D', p:'-0.3 -0.3 -0.3'},
                     {t:'E', p:'-0.3 0.3 0.3'}, {t:'F', p:'0.3 0.3 0.3'}, {t:'G', p:'0.3 0.3 -0.3'}, {t:'H', p:'-0.3 0.3 -0.3'} ],
                4: [ {t:'A', p:'0 -0.35 0.45'}, {t:'B', p:'0.45 -0.35 0'}, {t:'C', p:'0 -0.35 -0.45'}, {t:'D', p:'-0.45 -0.35 0'}, {t:'T', p:'0 0.35 0'} ],
                5: [ {t:'A', p:'0 -0.35 0.45'}, {t:'B', p:'0.4 -0.35 -0.2'}, {t:'C', p:'-0.4 -0.35 -0.2'}, {t:'T', p:'0 0.35 0'} ],
                6: [ {t:'A', p:'0.45 -0.35 0'}, {t:'B', p:'0.25 -0.35 0.4'}, {t:'C', p:'-0.25 -0.35 0.4'}, {t:'D', p:'-0.45 -0.35 0'}, {t:'E', p:'-0.25 -0.35 -0.4'}, {t:'F', p:'0.25 -0.35 -0.4'},
                     {t:'G', p:'0.45 0.35 0'}, {t:'H', p:'0.25 0.35 0.4'}, {t:'I', p:'-0.25 0.35 0.4'}, {t:'J', p:'-0.45 0.35 0'}, {t:'K', p:'-0.25 0.35 -0.4'}, {t:'L', p:'0.25 0.35 -0.4'} ],
                7: [ {t:'A', p:'0 0.35 0.45'}, {t:'B', p:'0.4 0.35 -0.2'}, {t:'C', p:'-0.4 0.35 -0.2'},
                     {t:'D', p:'0 -0.35 0.45'}, {t:'E', p:'0.4 -0.35 -0.2'}, {t:'F', p:'-0.4 -0.35 -0.2'} ],
                9: [ {t:'A', p:'-0.45 -0.3 0.05'}, {t:'B', p:'0.45 -0.3 0.05'}, {t:'C', p:'0.45 0.3 0.05'}, {t:'D', p:'-0.45 0.3 0.05'} ],
                10: [ {t:'A', p:'-0.3 -0.3 0.05'}, {t:'B', p:'0.3 -0.3 0.05'}, {t:'C', p:'0.3 0.3 0.05'}, {t:'D', p:'-0.3 0.3 0.05'} ],
                11: [ {t:'A', p:'-0.25 -0.4 0.05'}, {t:'B', p:'0.25 -0.4 0.05'}, {t:'C', p:'0.25 0.4 0.05'}, {t:'D', p:'-0.25 0.4 0.05'} ],
                13: [ {t:'A', p:'-0.3 -0.3 0.05'}, {t:'B', p:'0.3 -0.3 0.05'}, {t:'C', p:'0.3 0.3 0.05'}, {t:'D', p:'-0.3 0.3 0.05'} ],
                14: [ {t:'A', p:'-0.45 -0.25 0.05'}, {t:'B', p:'0.45 -0.25 0.05'}, {t:'C', p:'0.45 0.25 0.05'}, {t:'D', p:'-0.45 0.25 0.05'} ],
                15: [ {t:'A', p:'0 0.45 0.05'}, {t:'B', p:'-0.45 -0.45 0.05'}, {t:'C', p:'0.45 -0.45 0.05'} ]
            };

            const menuUtama = document.getElementById('menu-utama');
            const menuTentang = document.getElementById('menu-tentang');
            const panelRumus = document.getElementById('panel-rumus');

            const btnMulaiAR = document.getElementById('tombol-mulai-ar');
            const btnBukaTentang = document.getElementById('tombol-buka-tentang');
            const btnTutupTentang = document.getElementById('tombol-tutup-tentang');
            const btnKembaliMenu = document.getElementById('btn-kembali-menu');

            const btnRumus = document.getElementById('btn-rumus');
            const btnJaring = document.getElementById('btn-jaring');
            const btnRusuk = document.getElementById('btn-rusuk');
            const btnTutupRumus = document.getElementById('btn-tutup-rumus');

            const musikLatar = document.getElementById('musik-latar');
            let statusMusik = false;
            let isJaringTerbuka = false;
            let timeoutId = null;

            // ===== SISTEM PENGINGAT HALAMAN (agar REFRESH tidak balik ke menu awal) =====
            const KUNCI_STATE = 'ar_bangun_ruang_halaman';
            function simpanState(s) { try { localStorage.setItem(KUNCI_STATE, s); } catch(e) {} }
            function bacaState() { try { return localStorage.getItem(KUNCI_STATE) || 'menu'; } catch(e) { return 'menu'; } }

            let idMarkerAktif = null;
            const semuaMarker = document.querySelectorAll('.pelacak-marker');
            const sceneEl = document.querySelector('a-scene');

            // SIMPAN WARNA ASLI SETIAP OBJEK (agar tidak jadi hitam setelah mode Rusuk)
            function simpanSemuaMaterialAsli() {
                document.querySelectorAll('.objek-padat, .objek-biasa').forEach(el => {
                    const m = el.getAttribute('material') || {};
                    const c = m.color || '#ffffff';
                    const o = (m.opacity !== undefined) ? m.opacity : 1;
                    const s = m.side || 'front';
                    el.dataset.matAsli = `color: ${c}; opacity: ${o}; side: ${s}`;
                });
            }
            if (sceneEl.hasLoaded) simpanSemuaMaterialAsli();
            else sceneEl.addEventListener('loaded', simpanSemuaMaterialAsli);

            // KEMBALIKAN WARNA ASLI (matikan wireframe & buang warna #333 dari mode Rusuk)
            function pulihkanMaterial(el) {
                const dasar = el.dataset.matAsli || 'color: #ffffff; opacity: 1; side: front';
                el.setAttribute('material', dasar + '; wireframe: false');
            }

            // 1. INJEKSI HURUF SUDUT
            semuaMarker.forEach(marker => {
                let index = marker.getAttribute('data-index');
                if(dataSudut[index]) {
                    let entityObjek = marker.querySelector('.objek-biasa') || marker.querySelector('.objek-padat');
                    dataSudut[index].forEach(titik => {
                        let teks = document.createElement('a-text');
                        teks.setAttribute('value', titik.t);
                        teks.setAttribute('position', titik.p);
                        teks.setAttribute('color', '#FFFF00');
                        teks.setAttribute('scale', '1.5 1.5 1.5');
                        teks.setAttribute('align', 'center');
                        teks.setAttribute('class', 'huruf-sudut');
                        teks.setAttribute('visible', 'false');
                        entityObjek.appendChild(teks);
                    });
                }
                marker.addEventListener('targetFound', () => idMarkerAktif = index);
            });

            // 2. SISTEM SUARA AI & NOTIFIKASI
            function mainkanSuaraAI(teksSpelled) {
                window.speechSynthesis.cancel();
                let suaraRobot = new SpeechSynthesisUtterance(teksSpelled);
                suaraRobot.lang = 'id-ID';
                suaraRobot.rate = 0.9;
                suaraRobot.pitch = 1.2;
                musikLatar.volume = 0.2;
                suaraRobot.onend = function() { musikLatar.volume = 1.0; };
                window.speechSynthesis.speak(suaraRobot);
            }

            function hentikanSuaraAI() {
                window.speechSynthesis.cancel();
                musikLatar.volume = 1.0;
            }

            function tampilkanNotifikasi(pesan) {
                const popup = document.getElementById('notif-popup');
                popup.innerText = pesan;
                popup.classList.add('tampil');
                if(timeoutId) clearTimeout(timeoutId);
                timeoutId = setTimeout(() => popup.classList.remove('tampil'), 5000);
            }

            // AUTO-FIT halaman Tentang: kartu otomatis mengecil agar selalu pas 1 layar (tanpa scroll)
            function paskanKartuTentang() {
                const kartu = document.querySelector('.tentang-kartu');
                if (!kartu || menuTentang.style.display === 'none') return;
                kartu.style.transform = 'scale(1)';
                const lebar = kartu.offsetWidth;
                const tinggi = kartu.offsetHeight;
                if (!lebar || !tinggi) return;
                const skala = Math.min(1, (window.innerWidth * 0.94) / lebar, (window.innerHeight * 0.94) / tinggi);
                kartu.style.transform = 'scale(' + skala + ')';
            }
            window.addEventListener('resize', paskanKartuTentang);
            window.addEventListener('orientationchange', () => setTimeout(paskanKartuTentang, 150));

            // 3. LOGIKA TOMBOL MENU
            btnMulaiAR.addEventListener('click', () => {
                menuUtama.style.display = 'none';
                simpanState('ar'); // ingat: sedang di halaman kamera/bangun ruang
                if(!statusMusik) {
                    musikLatar.volume = 1.0;
                    musikLatar.play().catch(e => console.log("Gagal memutar musik: ", e));
                    statusMusik = true;
                }
            });

            btnBukaTentang.addEventListener('click', () => { menuTentang.style.display = 'flex'; simpanState('tentang'); setTimeout(paskanKartuTentang, 30); });
            btnTutupTentang.addEventListener('click', () => { menuTentang.style.display = 'none'; simpanState('menu'); });
            btnKembaliMenu.addEventListener('click', () => {
                menuUtama.style.display = 'flex';
                simpanState('menu'); // kembali ke tampilan awal
                musikLatar.pause();
                statusMusik = false;
                hentikanSuaraAI();
            });

            // 4. LOGIKA TOMBOL RUMUS
            btnRumus.addEventListener('click', () => {
                if (idMarkerAktif !== null && dataRumus[idMarkerAktif]) {
                    const data = dataRumus[idMarkerAktif];
                    const urlGambarAsli = baseMarkerUrl + '/' + data.img;

                    document.getElementById('wadah-keterangan').innerHTML = `
                        <img src="${urlGambarAsli}" alt="${data.nama}" style="width: 150px; height: 150px; object-fit: contain; border: 4px solid yellow; border-radius: 15px; background-color: white; padding: 10px; margin-bottom: 20px;">
                        <p style="color: yellow; margin-bottom: 5px; text-align: center; background: none; font-size: 24px; padding: 0;">${data.nama}</p>
                        <p>${data.ket}</p>
                    `;
                    document.getElementById('kotak-rumus-1').innerHTML = data.rumus1;
                    document.getElementById('kotak-rumus-2').innerHTML = data.rumus2;

                    panelRumus.style.display = 'block';
                    mainkanSuaraAI(data.suara);
                } else {
                    tampilkanNotifikasi("Arahkan kamera ke salah satu gambar terlebih dahulu!");
                }
            });

            btnTutupRumus.addEventListener('click', () => {
                panelRumus.style.display = 'none';
                hentikanSuaraAI();
            });

            // 5. SISTEM ENGINE ANIMASI PRESISI TINGGI
            function jalankanAnimasi(elemen, dari, ke, waktuJeda) {
                elemen.removeAttribute('animation');
                setTimeout(() => {
                    elemen.setAttribute('animation', `property: rotation; from: ${dari}; to: ${ke}; dur: 800; delay: ${waktuJeda}; easing: easeInOutQuad`);
                }, 10);
            }

            // 6. LOGIKA TOMBOL RUSUK (TOGGLE: WIREFRAME <-> 3D SEMULA)
            let isRusukAktif = false;
            btnRusuk.addEventListener('click', function() {
                isJaringTerbuka = false;
                btnJaring.innerText = "Buka Jaring";

                if (!isRusukAktif) {
                    // ---- TAMPILKAN MODE RUSUK ----
                    document.querySelectorAll('.objek-jaring').forEach(el => el.setAttribute('visible', 'false'));
                    document.querySelectorAll('.objek-padat').forEach(el => {
                        el.setAttribute('visible', 'true');
                        el.setAttribute('material', 'color: #333333; wireframe: true;');
                    });
                    document.querySelectorAll('.objek-biasa').forEach(el => {
                        el.removeAttribute('animation');
                        if(el.tagName.toLowerCase() !== 'a-plane') el.setAttribute('rotation', '0 0 0');
                        el.setAttribute('material', 'color: #333333; wireframe: true;');
                    });
                    document.querySelectorAll('.huruf-sudut').forEach(huruf => huruf.setAttribute('visible', 'true'));

                    isRusukAktif = true;
                    btnRusuk.innerText = "3D Semula";
                } else {
                    // ---- KEMBALIKAN KE 3D SEMULA ----
                    document.querySelectorAll('.huruf-sudut').forEach(huruf => huruf.setAttribute('visible', 'false'));
                    document.querySelectorAll('.objek-jaring').forEach(el => el.setAttribute('visible', 'false'));
                    document.querySelectorAll('.objek-padat').forEach(el => {
                        el.setAttribute('visible', 'true');
                        pulihkanMaterial(el);
                    });
                    document.querySelectorAll('.objek-biasa').forEach(el => {
                        el.removeAttribute('animation');
                        if(el.tagName.toLowerCase() !== 'a-plane') el.setAttribute('rotation', '0 0 0');
                        pulihkanMaterial(el);
                    });

                    isRusukAktif = false;
                    btnRusuk.innerText = "Rusuk (A,B,C)";
                }
            });

            // 7. LOGIKA TOMBOL JARING (MEKAR BERTAHAP)
            btnJaring.addEventListener('click', function() {
                if (idMarkerAktif === null) {
                    tampilkanNotifikasi("Arahkan kamera ke salah satu gambar terlebih dahulu!");
                    return;
                }

                document.querySelectorAll('.huruf-sudut').forEach(huruf => huruf.setAttribute('visible', 'false'));

                // reset status Rusuk agar label tombol tetap konsisten
                if (isRusukAktif) { isRusukAktif = false; btnRusuk.innerText = "Rusuk (A,B,C)"; }

                let markerAktifSekarang = document.querySelector(`.pelacak-marker[data-index="${idMarkerAktif}"]`);
                let adaJaring3D = markerAktifSekarang.querySelector('.objek-jaring');

                // PENGECEKAN BANGUN TANPA JARING
                if (!adaJaring3D) {
                    let pesanSuara = "Maaf, animasi jaring-jaring untuk bangun ini belum tersedia.";
                    if (idMarkerAktif == 2) pesanSuara = "Bola adalah bangun ruang sisi lengkung murni, secara matematika BOLA TIDAK MEMILIKI jaring-jaring yang datar!";
                    else if (idMarkerAktif == 8) pesanSuara = "Tabung memiliki selimut melengkung yang tidak bisa dilipat dengan kertas biasa.";
                    else pesanSuara = "Ini adalah Bangun Datar! Bangun dua dimensi tidak bisa dibongkar pasang seperti kardus.";

                    tampilkanNotifikasi(pesanSuara);
                    mainkanSuaraAI(pesanSuara);

                    markerAktifSekarang.querySelectorAll('.objek-biasa').forEach(el => {
                        pulihkanMaterial(el);
                        if(el.tagName.toLowerCase() !== 'a-plane' && el.tagName.toLowerCase() !== 'a-circle' && el.tagName.toLowerCase() !== 'a-triangle') {
                            el.setAttribute('animation', 'property: rotation; to: 0 360 0; loop: true; dur: 4000; easing: linear;');
                        }
                    });
                    return;
                }

                // ANIMASI JARING UNTUK SEMUA BANGUN 3D BERSUDUT
                if(!isJaringTerbuka) {
                    document.querySelectorAll('.engsel').forEach(p => p.setAttribute('rotation', p.getAttribute('data-tutup')));

                    document.querySelectorAll('.objek-padat').forEach(el => el.setAttribute('visible', 'false'));
                    document.querySelectorAll('.objek-jaring').forEach(el => el.setAttribute('visible', 'true'));

                    document.querySelectorAll('.objek-jaring').forEach(el => {
                        el.querySelectorAll('.engsel').forEach(p => {
                            let delay = p.getAttribute('data-delay-buka');
                            jalankanAnimasi(p, p.getAttribute('data-tutup'), '0 0 0', delay);
                        });
                    });

                    isJaringTerbuka = true;
                    btnJaring.innerText = "Tutup Jaring";
                } else {
                    document.querySelectorAll('.objek-jaring').forEach(el => {
                        el.querySelectorAll('.engsel').forEach(p => {
                            let delay = p.getAttribute('data-delay-tutup');
                            jalankanAnimasi(p, '0 0 0', p.getAttribute('data-tutup'), delay);
                        });
                    });

                    setTimeout(() => {
                        if(!isJaringTerbuka) {
                            document.querySelectorAll('.objek-jaring').forEach(el => el.setAttribute('visible', 'false'));
                            document.querySelectorAll('.objek-padat').forEach(el => {
                                el.setAttribute('visible', 'true');
                                pulihkanMaterial(el);
                            });
                        }
                    }, 2500);

                    isJaringTerbuka = false;
                    btnJaring.innerText = "Buka Jaring";
                }
            });

            // ===== PULIHKAN HALAMAN TERAKHIR SAAT REFRESH (jalan paling akhir) =====
            (function pulihkanHalaman() {
                const s = bacaState();
                if (s === 'ar') {
                    // tetap di halaman kamera / bangun ruang
                    menuUtama.style.display = 'none';
                    menuTentang.style.display = 'none';
                    musikLatar.volume = 1.0;
                    const coba = musikLatar.play();
                    if (coba && typeof coba.then === 'function') {
                        coba.then(() => { statusMusik = true; }).catch(() => {
                            // browser blokir autoplay: putar musik saat sentuhan/klik pertama
                            const mulaiMusik = () => {
                                musikLatar.play().catch(() => {});
                                statusMusik = true;
                                document.removeEventListener('click', mulaiMusik);
                                document.removeEventListener('touchstart', mulaiMusik);
                            };
                            document.addEventListener('click', mulaiMusik);
                            document.addEventListener('touchstart', mulaiMusik);
                        });
                    }
                } else if (s === 'tentang') {
                    // tetap di halaman tentang (menu tetap di belakang, siap saat klik Kembali)
                    menuUtama.style.display = 'block';
                    menuTentang.style.display = 'flex';
                    setTimeout(paskanKartuTentang, 60);
                } else {
                    // tampilan awal
                    menuUtama.style.display = 'block';
                    menuTentang.style.display = 'none';
                }
            })();
        });
    </script>
</body>
</html>
