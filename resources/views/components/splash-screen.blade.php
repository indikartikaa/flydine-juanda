{{-- Skrip Inline Bernonce: Hindari kedipan (no-flicker) jika sesi sudah pernah melihat splash screen --}}
<script nonce="{{ $cspNonce ?? '' }}">
    try {
        if (sessionStorage.getItem('flydine_splash_seen')) {
            document.documentElement.classList.add('flydine-splash-skip');
        }
    } catch (e) {}
</script>

<!-- FlyDine Official Fullscreen Splash Screen (Harmonis dengan Tema Bandara Juanda) -->
<div id="flydine-splash" 
     class="no-print"
     role="presentation"
     aria-hidden="true"
     tabindex="-1">
    
    <div class="flydine-splash-center">
        <!-- Logo FlyDine Besar & Gagah Sesuai Warna Asli Brand -->
        <img src="{{ asset('images/logo-flydine.png') }}" 
             alt="FlyDine" 
             class="flydine-splash-logo" 
             width="1376" 
             height="768"
             loading="eager"
             decoding="async">
        
        <!-- Garis Jejak Pesawat (Aksen Warna Brand Biru & Hijau Bandara) -->
        <div class="flydine-splash-line-bar"></div>

        <!-- Keterangan Bandara Resmi -->
        <p class="flydine-splash-airport">Bandara Internasional Juanda Surabaya</p>
    </div>
</div>

<script nonce="{{ $cspNonce ?? '' }}">
    (function () {
        var storageKey = 'flydine_splash_seen';
        var hasSeen = false;

        try {
            hasSeen = !!sessionStorage.getItem(storageKey);
        } catch (e) {
            hasSeen = false;
        }

        var splash = document.getElementById('flydine-splash');

        // Jika sudah pernah melihat di sesi ini, langsung buang dari DOM
        if (hasSeen) {
            if (splash && splash.parentNode) {
                splash.parentNode.removeChild(splash);
            }
            return;
        }

        // Tandai sesi sudah melihat splash
        try {
            sessionStorage.setItem(storageKey, '1');
        } catch (e) {}

        if (!splash) return;

        var dismissed = false;
        function dismissSplash() {
            if (dismissed) return;
            dismissed = true;
            splash.style.transition = 'opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1)';
            splash.style.opacity = '0';
            splash.style.pointerEvents = 'none';
            setTimeout(function () {
                if (splash && splash.parentNode) {
                    splash.parentNode.removeChild(splash);
                }
            }, 380);
        }

        // Tap atau klik untuk skip langsung
        splash.addEventListener('click', dismissSplash, { passive: true });
        splash.addEventListener('touchstart', dismissSplash, { passive: true });

        // Pengaman DOM: pastikan node terhapus otomatis setelah animasi keluar 3.0 detik
        setTimeout(function () {
            if (!dismissed && splash && splash.parentNode) {
                dismissed = true;
                splash.parentNode.removeChild(splash);
            }
        }, 3100);
    })();
</script>
