{{-- U2 Aktivasi akun dan U3 Lupa kata sandi; keduanya memakai U4 (NIM + kode) dan U5 (kata sandi baru). --}}
@extends('layouts.auth')

@section('judul', $aktivasi ? 'Aktivasi akun' : 'Pemulihan akun')
@section('auth_kelas', 'compact')

@section('kartu')
    <h1 class="auth-title">{{ $aktivasi ? 'Aktivasi akun' : 'Lupa kata sandi' }}</h1>
    <p class="auth-sub">{{ $aktivasi ? 'Gunakan NIM dan kode aktivasi dari sekretaris prodi.' : 'Gunakan NIM dan kode pemulihan dari Admin KKN.' }}</p>

    <form method="POST" action="{{ route($aktivasi ? 'aktivasi.kirim' : 'pemulihan.kirim') }}" id="credential-form">
        @csrf
        <x-field label="NIM" name="nim" :value="old('nim')" required inputmode="numeric" maxlength="15" autocomplete="username" />
        <x-field :label="$aktivasi ? 'Kode aktivasi' : 'Kode pemulihan'" name="code" :value="old('code')"
            :hint="$aktivasi ? null : 'Berlaku 30 menit.'"
            required autocomplete="one-time-code" autocapitalize="characters" maxlength="20" placeholder="XXXX-XXXX-XXXX" />
        @if ($aktivasi)
            <div class="field">
                <label for="gender">Jenis kelamin</label>
                <select id="gender" name="gender" required @error('gender') aria-invalid="true" aria-describedby="form-error" @enderror>
                    <option value="">Pilih jenis kelamin</option>
                    <option value="L" @selected(old('gender') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender') === 'P')>Perempuan</option>
                </select>
            </div>
        @endif
        <x-field label="Kata sandi baru" name="password" type="password" hint="Minimal 8 karakter."
            required minlength="8" maxlength="128" autocomplete="new-password" />
        <x-field label="Ulangi kata sandi" name="confirm" type="password" required maxlength="128" autocomplete="new-password" />
        <div id="form-error" class="form-error" role="alert">{{ $errors->first() }}</div>
        <button type="submit" class="btn btn-primary w-full"><x-icon name="check" />{{ $aktivasi ? 'Aktifkan akun' : 'Atur ulang kata sandi' }}</button>
        <div class="auth-links center">
            @if ($aktivasi)
                <span>Sudah punya akun? <a href="{{ route('masuk') }}">Masuk</a></span>
            @else
                <a href="{{ route('masuk') }}">Kembali ke halaman masuk</a>
            @endif
        </div>
    </form>
@endsection
