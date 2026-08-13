<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AR Bangun Ruang - Tentang</title>
    <style>
        body, html { margin: 0; padding: 0; overflow: hidden; font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif; }
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
        .btn-menu { padding: 15px 40px; font-size: 24px; font-weight: bold; color: white; border: 4px solid #fff; border-radius: 15px; cursor: pointer; box-shadow: 0px 8px 0px rgba(0,0,0,0.2); transition: 0.1s; font-family: inherit; text-transform: uppercase; letter-spacing: 2px; display: inline-block; text-decoration: none; }
        .btn-simulasi { background-color: #2ED175; }
        .btn-menu:active { transform: translateY(8px); box-shadow: 0px 0px 0px rgba(0,0,0,0); }

        @media (max-width: 768px) {
            .tentang-kartu { max-width: 92%; padding: 20px 18px; border-radius: 24px; }
            .tentang-judul { font-size: 24px; }
            .tentang-teks { font-size: 16px; line-height: 1.65; }
            .tentang-hiasan .bentuk { opacity: 0.15; }
            .tentang-hiasan .b1, .tentang-hiasan .b2, .tentang-hiasan .b3, .tentang-hiasan .b4 { width: 70px; height: 70px; }
            .tentang-hiasan .b5, .tentang-hiasan .b6 { width: 55px; height: 55px; }
        }
    </style>
</head>
<body>

    <div id="menu-tentang" style="display: flex;">
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
            <a class="btn-menu btn-simulasi" href="{{ url('/') }}" style="width: auto; padding: 10px 40px;">Kembali</a>
        </div>
    </div>

    <script>
        // Auto-fit: kartu otomatis mengecil agar selalu pas 1 layar (tanpa scroll)
        function paskanKartuTentang() {
            const kartu = document.querySelector('.tentang-kartu');
            if (!kartu) return;
            kartu.style.transform = 'scale(1)';
            const lebar = kartu.offsetWidth, tinggi = kartu.offsetHeight;
            if (!lebar || !tinggi) return;
            const skala = Math.min(1, (window.innerWidth * 0.94) / lebar, (window.innerHeight * 0.94) / tinggi);
            kartu.style.transform = 'scale(' + skala + ')';
        }
        window.addEventListener('load', () => setTimeout(paskanKartuTentang, 60));
        window.addEventListener('resize', paskanKartuTentang);
        window.addEventListener('orientationchange', () => setTimeout(paskanKartuTentang, 150));
    </script>

</body>
</html>
