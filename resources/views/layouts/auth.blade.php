@extends('layouts.base')

@section('body')
    <header class="topbar"><div class="container-page">@include('partials.brand')</div></header>
    <main class="auth-split @yield('auth_kelas')">
        <aside class="auth-visual">
            @if ($periodePublik)
                <span class="auth-pill"><x-icon name="calendar" />Tahun Akademik {{ $periodePublik->tahun_akademik }}</span>
            @endif
            <p class="auth-display">Kuliah<br><em>Kerja Nyata.</em></p>
            <span class="auth-place"><x-icon name="pin" />Kampar, Riau</span>
        </aside>
        <section class="card auth-card">@yield('kartu')</section>
    </main>
    <footer class="footer">
        <div class="container-page">
            <span>© 2026 Admin Universitas Pahlawan Tuanku Tambusai</span>
            <span>SIM KKN <span class="muted">/</span> Kabupaten Kampar, Riau</span>
        </div>
    </footer>
@endsection
