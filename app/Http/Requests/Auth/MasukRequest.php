<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * U1: Masuk. Mahasiswa memakai NIM, Admin memakai email.
 */
class MasukRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peran' => ['required', Rule::in(['mahasiswa', 'admin'])],
            'nim' => ['exclude_unless:peran,mahasiswa', 'required', 'string', 'max:15'],
            'email' => ['exclude_unless:peran,admin', 'required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:128'],
        ];
    }

    public function messages(): array
    {
        return [
            'peran.*' => 'Pilih peran Mahasiswa atau Admin.',
            'nim.required' => 'Masukkan NIM.',
            'nim.*' => 'NIM atau kata sandi tidak sesuai. Untuk akses pertama, pilih Aktivasi akun.',
            'email.required' => 'Masukkan email.',
            'email.*' => 'Email atau kata sandi tidak sesuai.',
            'password.required' => 'Masukkan kata sandi.',
            'password.*' => 'Gunakan kata sandi 8–128 karakter.',
        ];
    }

    public function peran(): string
    {
        return $this->input('peran') === 'admin' ? 'admin' : 'mahasiswa';
    }
}
