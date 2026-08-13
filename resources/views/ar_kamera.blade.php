<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AR Bangun Ruang - Kamera</title>

    <script src="https://aframe.io/releases/1.3.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mind-ar@1.2.2/dist/mindar-image-aframe.prod.js"></script>

    <style>
        body, html { margin: 0; padding: 0; overflow: hidden; font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif; background-color: transparent !important; }
        .mindar-ui-overlay, .mindar-ui-loading, .mindar-ui-scanning, .mindar-ui-error { display: none !important; opacity: 0 !important; visibility: hidden !important; z-index: -9999 !important; }
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
            /* ===== TOMBOL AR (Rumus/Jaring/Rusuk/Keluar) PINDAH KE BAWAH + FONT LEBIH TEBAL ===== */
            .ui-container { flex-direction: row; flex-wrap: wrap; justify-content: center; top: auto; bottom: 12px; right: 0; left: 0; width: 100%; padding: 0 10px; box-sizing: border-box; }
            .btn { min-width: 80px; padding: 11px 8px; font-size: 15px; font-weight: 900; flex: 1; }
            .rumus-content { flex-direction: column; justify-content: flex-start; height: auto; padding-bottom: 80px; }
            #panel-rumus { padding: 20px 15px; }
            .kotak-rumus { min-width: 100%; font-size: 18px; padding: 15px; box-sizing: border-box; }
            .kotak-rumus span { font-size: 20px; }
            .kiri-keterangan { margin-top: 50px; margin-bottom: 20px; width: 100%; }
        }

        /* ===== HP MODE LANDSCAPE: tombol tetap di KANAN & menumpuk vertikal (seperti screenshot) ===== */
        @media (orientation: landscape) and (max-height: 500px) {
            .ui-container {
                flex-direction: column;
                flex-wrap: nowrap;
                top: 10px; bottom: auto;
                right: 10px; left: auto;
                width: auto; padding: 0;
                gap: 10px;
                align-items: stretch;
            }
            .btn {
                flex: none;
                min-width: 120px;
                padding: 10px 16px;
                font-size: 15px;
                font-weight: 900;
            }
        }
    </style>
</head>
<body>

    <audio id="musik-latar" loop>
        <source src="/audio/musik_anak.mp3" type="audio/mpeg">
    </audio>
    <div id="notif-popup">Pesan dari AI muncul di sini!</div>

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
            <a-entity class="grup-bangun" position="0 0 0.2" rotation="90 0 0">
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

            2 => '<a-sphere class="objek-biasa" position="0 0 0.4" radius="0.4" material="color: #FF4C4C; opacity: 1;"></a-sphere>',

            // 3: KUBUS
            3 => '
            <a-entity class="grup-bangun" position="0 0 0.25" rotation="90 0 0">
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
            <a-entity class="grup-bangun" position="0 0 0.3" rotation="90 0 0">
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
            <a-entity class="grup-bangun" position="0 0 0.3" rotation="90 0 0">
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
            <a-entity class="grup-bangun" position="0 0 0.3" rotation="90 0 0">
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
            <a-entity class="grup-bangun" position="0 0 0.3" rotation="90 0 0">
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

            8 => '<a-entity class="grup-bangun" position="0 0 0.3" rotation="90 0 0"><a-cylinder class="objek-biasa" position="0 0 0" radius="0.3" height="0.6" material="color: #FFD700; opacity: 1;"></a-cylinder></a-entity>',
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

            const panelRumus = document.getElementById('panel-rumus');
            const btnKembaliMenu = document.getElementById('btn-kembali-menu');
            const btnRumus = document.getElementById('btn-rumus');
            const btnJaring = document.getElementById('btn-jaring');
            const btnRusuk = document.getElementById('btn-rusuk');
            const btnTutupRumus = document.getElementById('btn-tutup-rumus');

            const musikLatar = document.getElementById('musik-latar');
            let statusMusik = false;
            let isJaringTerbuka = false;
            let timeoutId = null;
            let idMarkerAktif = null;

            const semuaMarker = document.querySelectorAll('.pelacak-marker');
            const sceneEl = document.querySelector('a-scene');

            // SIMPAN & PULIHKAN WARNA ASLI
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
            // --- Siapkan daftar suara lebih dulu. Browser memuat suara secara ASINKRON,
            //     jadi tanpa ini panggilan pertama saat klik Rumus sering "telat/tidak bunyi". ---
            let suaraTerpilih = null;
            function muatSuara() {
                if (!('speechSynthesis' in window)) return;
                const daftar = window.speechSynthesis.getVoices();
                if (!daftar || !daftar.length) return;
                suaraTerpilih = daftar.find(v => /id[-_]?ID/i.test(v.lang))
                             || daftar.find(v => /^id/i.test(v.lang))
                             || suaraTerpilih;
            }
            if ('speechSynthesis' in window) {
                muatSuara();
                window.speechSynthesis.onvoiceschanged = muatSuara;
                // "Pemanasan" mesin suara pada interaksi pertama pengguna
                const panaskanSuara = () => {
                    try {
                        muatSuara();
                        const diam = new SpeechSynthesisUtterance(' ');
                        diam.volume = 0;
                        window.speechSynthesis.speak(diam);
                    } catch(e) {}
                };
                document.addEventListener('click', panaskanSuara, { once: true });
                document.addEventListener('touchstart', panaskanSuara, { once: true });
            }

            function mainkanSuaraAI(teksSpelled) {
                if (!('speechSynthesis' in window)) return;
                const sintesis = window.speechSynthesis;
                sintesis.cancel();                 // bersihkan antrian lama
                if (!suaraTerpilih) muatSuara();   // coba muat lagi kalau belum siap

                let suaraRobot = new SpeechSynthesisUtterance(teksSpelled);
                suaraRobot.lang = 'id-ID';
                suaraRobot.rate = 0.9;
                suaraRobot.pitch = 1.2;
                if (suaraTerpilih) suaraRobot.voice = suaraTerpilih;
                musikLatar.volume = 0.2;
                suaraRobot.onend = function() { musikLatar.volume = 1.0; };
                suaraRobot.onerror = function() { musikLatar.volume = 1.0; };

                sintesis.speak(suaraRobot);
                try { sintesis.resume(); } catch(e) {}   // atasi bug Chrome yang kadang "pause"
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

            // ===== TOMBOL KELUAR: kembali ke halaman Beranda (file terpisah) =====
            btnKembaliMenu.addEventListener('click', () => {
                try { musikLatar.pause(); } catch(e) {}
                hentikanSuaraAI();
                window.location.href = "{{ url('/') }}";
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

            // ===== MUSIK LATAR: putar saat halaman AR dibuka (atau saat sentuhan pertama) =====
            (function mulaiMusikLatar() {
                musikLatar.volume = 1.0;
                const coba = musikLatar.play();
                if (coba && typeof coba.then === 'function') {
                    coba.then(() => { statusMusik = true; }).catch(() => {
                        const mulai = () => {
                            musikLatar.play().catch(() => {});
                            statusMusik = true;
                            document.removeEventListener('click', mulai);
                            document.removeEventListener('touchstart', mulai);
                        };
                        document.addEventListener('click', mulai);
                        document.addEventListener('touchstart', mulai);
                    });
                }
            })();

            // ===== PERBAIKAN AR SAAT LAYAR DIPUTAR (portrait <-> landscape) =====
            // MindAR kadang tidak menyesuaikan kamera/kanvas saat orientasi berubah,
            // sehingga objek 3D "hilang" di mode landscape. Di sini kita segarkan ulang.
            function segarkanKameraAR() {
                try {
                    const sistemAR = sceneEl.systems && sceneEl.systems['mindar-image-system'];
                    if (sistemAR && typeof sistemAR.stop === 'function' && typeof sistemAR.start === 'function') {
                        sistemAR.stop();
                        setTimeout(() => {
                            try { sistemAR.start(); } catch(e) {}
                            window.dispatchEvent(new Event('resize'));
                        }, 250);
                    } else {
                        window.dispatchEvent(new Event('resize'));
                    }
                } catch(e) {
                    window.dispatchEvent(new Event('resize'));
                }
            }
            window.addEventListener('orientationchange', () => setTimeout(segarkanKameraAR, 400));
        });
    </script>
</body>
</html>
