@extends('layouts.auth')

@section('judul', 'Masuk')

@section('kartu')
    @php($mahasiswa = $peran === 'mahasiswa')
    <h1 class="auth-title">Masuk ke portal</h1>
    <p class="auth-sub">{{ $mahasiswa ? 'Gunakan NIM dan kata sandi yang dibuat saat aktivasi akun.' : 'Gunakan email dan kata sandi Admin.' }}</p>

    <form method="GET" action="{{ route('masuk') }}" class="role-tabs" aria-label="Pilih peran">
        @foreach (['mahasiswa' => 'Mahasiswa', 'admin' => 'Admin'] as $nilai => $label)
            <button type="submit" name="peran" value="{{ $nilai }}" class="{{ $peran === $nilai ? 'active' : '' }}"
                @if ($peran === $nilai) aria-current="true" @endif>{{ $label }}</button>
        @endforeach
    </form>

    <form method="POST" action="{{ route('masuk.kirim') }}" id="login-form">
        @csrf
        <input type="hidden" name="peran" value="{{ $peran }}">
        @if ($mahasiswa)
            <x-field label="NIM" name="nim" :value="old('nim', session('nim'))" required inputmode="numeric" maxlength="15" autocomplete="username" />
        @else
            <x-field label="Email" name="email" type="email" :value="old('email')" required autocomplete="username" />
        @endif
        <x-field label="Kata sandi" name="password" type="password" required autocomplete="current-password" maxlength="128" />
        <div id="form-error" class="form-error" role="alert">{{ $errors->first() }}</div>
        <button type="submit" class="btn btn-primary w-full"><x-icon name="user" />Masuk</button>
    </form>

    @if ($mahasiswa)
        <div class="auth-links">
            <a href="{{ route('aktivasi') }}">Aktivasi akun</a>
            <a href="{{ route('pemulihan') }}">Lupa kata sandi?</a>
        </div>
    @endif
@endsection
