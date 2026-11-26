<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F5EFF">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('judul')@yield('judul') · @endif SIM KKN · Universitas Pahlawan</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-up.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,700&family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-navigation.css') }}">
</head>
<body>
    <div id="app">@yield('body')</div>
    <div id="toast" role="status" aria-live="polite">{{ session('toast') }}</div>
    <script>
    (() => {
        const toast = document.getElementById('toast');
        window.tampilkanPesan = (teks) => {
            toast.textContent = teks;
            toast.classList.add('visible');
            clearTimeout(toast.timer);
            toast.timer = setTimeout(() => toast.classList.remove('visible'), 4500);
        };
        if (toast.textContent.trim()) window.tampilkanPesan(toast.textContent.trim());

        document.addEventListener('click', (ev) => {
            const terkunci = ev.target.closest('[data-locked-reason]');
            if (terkunci) { window.tampilkanPesan(terkunci.dataset.lockedReason); return; }

            const buka = ev.target.closest('[data-open-mobile-panel]');
            if (buka) {
                const panel = document.getElementById(buka.dataset.openMobilePanel);
                if (panel && !panel.open) { panel.showModal(); buka.setAttribute('aria-expanded', 'true'); }
                return;
            }
            if (ev.target.closest('[data-close-mobile-panel]') || ev.target.matches('dialog.mobile-panel')) {
                ev.target.closest('dialog')?.close();
            }
            document.querySelectorAll('details.account-menu[open]').forEach((menu) => {
                if (!menu.contains(ev.target)) menu.removeAttribute('open');
            });
        });
        document.addEventListener('close', (ev) => {
            document.querySelectorAll('[aria-controls="' + ev.target.id + '"]').forEach((b) => b.setAttribute('aria-expanded', 'false'));
        }, true);
    })();
    </script>
</body>
</html>
