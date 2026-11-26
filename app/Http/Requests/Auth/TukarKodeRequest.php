<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * U2/U3 dengan U4 (NIM + kode) dan U5 (kata sandi baru 8–128 karakter, diulang sama).
 * Aktivasi menambah isian jenis kelamin.
 */
class TukarKodeRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function aktivasi(): bool
    {
        return $this->routeIs('aktivasi.*');
    }

    public function rules(): array
    {
        return [
            'nim' => ['required', 'string', 'max:15'],
            'code' => ['required', 'string', 'max:20'],
            'gender' => $this->aktivasi() ? ['required', 'in:L,P'] : ['exclude'],
            'password' => ['required', 'string', 'min:8', 'max:128'],
            'confirm' => ['required', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => 'Masukkan NIM.',
            'nim.*' => 'NIM atau kode tidak sesuai, sudah digunakan, atau sudah kedaluwarsa.',
            'code.required' => $this->aktivasi() ? 'Masukkan kode aktivasi.' : 'Masukkan kode pemulihan.',
            'code.*' => 'NIM atau kode tidak sesuai, sudah digunakan, atau sudah kedaluwarsa.',
            'gender.*' => 'Pilih jenis kelamin.',
            'password.*' => 'Gunakan kata sandi 8–128 karakter.',
            'confirm.*' => 'Konfirmasi kata sandi tidak sama.',
        ];
    }
}
