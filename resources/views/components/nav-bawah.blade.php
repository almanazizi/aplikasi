{{-- Navigasi bawah khusus layar HP (≤ 760px, spesifikasi bagian 6). Laptop tetap memakai sidebar. --}}
@props(['peran', 'tab' => [], 'lainnya' => [], 'lainnyaAktif' => false, 'profilBelumLengkap' => false])
<nav class="bottom-nav" aria-label="Navigasi bawah">
    @foreach ($tab as $item)
        @php($label = $item['pendek'] ?? $item['judul'])
        @if (! empty($item['kunci']))
            <button type="button" class="bottom-tab locked" data-locked-reason="{{ $item['kunci'] }}" aria-disabled="true"
                aria-label="{{ $label }} terkunci: {{ $item['kunci'] }}"><x-icon name="lock" /><span>{{ $label }}</span></button>
        @else
            <a class="bottom-tab{{ $item['aktif'] ? ' active' : '' }}" href="{{ $item['url'] }}" @if ($item['aktif']) aria-current="page" @endif>
                <x-icon :name="$item['ikon']" /><span>{{ $label }}</span>
                @if ($peran === 'mahasiswa' && ($item['pendek'] ?? '') === 'Profil' && $profilBelumLengkap)
                    <span class="tab-dot" title="Profil belum lengkap"></span>
                @endif
            </a>
        @endif
    @endforeach
    @if ($peran === 'admin')
        <button type="button" class="bottom-tab{{ $lainnyaAktif ? ' active' : '' }}" data-open-mobile-panel="mobile-more"
            aria-haspopup="dialog" aria-controls="mobile-more" aria-expanded="false"><x-icon name="menu" /><span>Lainnya</span></button>
    @endif
</nav>
@if ($peran === 'admin')
    <dialog class="mobile-panel" id="mobile-more" aria-labelledby="mobile-more-title">
        <div class="mobile-panel-heading">
            <h2 id="mobile-more-title">Menu lainnya</h2>
            <button type="button" class="mobile-panel-button" data-close-mobile-panel aria-label="Tutup Menu lainnya" autofocus><x-icon name="close" /></button>
        </div>
        <div class="mobile-panel-body">
            <nav aria-label="Menu lainnya">
                @foreach ($lainnya as $item)
                    <a class="side-link{{ $item['aktif'] ? ' active' : '' }}" href="{{ $item['url'] }}" @if ($item['aktif']) aria-current="page" @endif><x-icon :name="$item['ikon']" />{{ $item['judul'] }}</a>
                @endforeach
            </nav>
        </div>
    </dialog>
@endif
