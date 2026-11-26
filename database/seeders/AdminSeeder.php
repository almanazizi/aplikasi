<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@kkn.test'],
            [
                'nama_admin' => 'Admin LPPM',
                'password_hash' => Hash::make('KknDemo!2026'),
            ]
        );
    }
}
