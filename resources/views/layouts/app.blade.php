@extends('layouts.base')

@section('body')
    <header class="topbar app-topbar">
        <div class="container-page">
            <div class="brand-with-period">
                @include('partials.brand')
                @if ($periode)
                    <span class="period-chip" title="Periode KKN yang sedang dibuka">Periode {{ $periode->tahun_akademik }}</span>
                @endif
            </div>
            <details class="account-menu">
                <summary aria-label="Akun {{ $namaTampil }}"><span class="avatar">{{ $inisial }}</span><span class="account-name">{{ $namaTampil }} ▾</span></summary>
                <div class="account-options">
                    @if ($peran === 'mahasiswa')
                        <a class="btn btn-outline" href="{{ route('mahasiswa.profil') }}"><x-icon name="user" />Profil saya</a>
                    @endif
                    <form method="POST" action="{{ route('keluar') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline w-full"><x-icon name="exit" />Keluar akun</button>
                    </form>
                </div>
            </details>
        </div>
    </header>

    <x-nav-bawah :peran="$peran" :tab="$tabBawah" :lainnya="$menuLainnya ?? []" :lainnya-aktif="$lainnyaAktif ?? false"
        :profil-belum-lengkap="$profilBelumLengkap ?? false" />

    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-label">{{ $judulMenu }}</div>
            <nav aria-label="{{ $judulMenu }}">
                @foreach ($menuSamping as $item)
                    @if (isset($item['bagian']))
                        <div class="sidebar-section">
                            <div class="side-link sidebar-parent"><x-icon :name="$item['ikon']" /><span>{{ $item['bagian'] }}</span></div>
                            <div class="sidebar-children">
                                @foreach ($item['anak'] as $anak)
                                    <a class="side-link{{ $anak['aktif'] ? ' active' : '' }}" href="{{ $anak['url'] }}" @if ($anak['aktif']) aria-current="page" @endif><x-icon :name="$anak['ikon']" />{{ $anak['judul'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @elseif (! empty($item['kunci']))
                        <div class="side-link locked-feature" aria-disabled="true" title="{{ $item['kunci'] }}"><x-icon name="lock" /><span>{{ $item['judul'] }}</span></div>
                    @else
                        <a class="side-link{{ $item['aktif'] ? ' active' : '' }}" href="{{ $item['url'] }}" @if ($item['aktif']) aria-current="page" @endif><x-icon :name="$item['ikon']" />{{ $item['judul'] }}</a>
                    @endif
                @endforeach
            </nav>
        </aside>
        <main class="content">
            @if ($periode?->tahap_periode === 'ditutup')
                <div class="archive-banner"><x-icon name="lock" />Arsip periode {{ $periode->tahun_akademik }}. Data dan dokumen hanya dapat dilihat.</div>
            @endif
            @yield('content')
        </main>
    </div>
@endsection
