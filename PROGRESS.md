# Progres Pengerjaan SIM KKN (Laravel + MySQL 8.4)

Sedang dikerjakan: Tahap 2 selesai (menunggu pemeriksaan pemilik proyek)
Langkah berikutnya: Tahap 3 — Data master Admin (prodi, desa, DPL, templat & impor Excel)

---

## Tahap 1 — Proyek Laravel, Koneksi Database, Migrasi 14 Tabel, Seeder
- [x] Buat proyek Laravel di folder `aplikasi/` dan salin aset tampilan (`css`, `img`, `templat`, `icons`)
- [x] Konfigurasi `.env`, `config/app.php` (Asia/Jakarta), `config/database.php` (MySQL InnoDB), `config/auth.php` (guard `admin` & `mahasiswa`), `config/kkn.php`, `config/icons.php`
- [x] Hapus migrasi dan model bawaan Laravel (`users`, `cache`, `jobs`, `password_reset_tokens`)
- [x] Buat migrasi 14 tabel persis sesuai Bagian 3 spesifikasi + 14 model Eloquent (`$timestamps = false`)
- [x] Buat seeder `AdminSeeder`, `ProgramStudiSeeder`, dan `PeriodeSeeder`
- [x] Jalankan `php artisan migrate --seed` ke MySQL 8.4 setelah pemilik mengisi `DB_PASSWORD` di `.env` (15 tabel + Admin, 17 prodi, 1 periode, 2 ketentuan masuk)

> Catatan Tahap 1:
> - `DB_PASSWORD=` di `.env` sengaja dikosongkan agar diisi langsung oleh pemilik proyek.
> - `PeriodeSeeder` ditambahkan bersama `AdminSeeder` dan `ProgramStudiSeeder` agar tersedia periode aktif awal (`2026/2027`, tahap `persiapan`) dan ketentuan default Reguler/Nonreguler, karena use case tidak memiliki fitur "Buat periode baru".

---

## Tahap 2 — Login Dua Peran, Aktivasi Akun, Lupa Kata Sandi
- [x] U1: Masuk (Login) — `/` dan `/masuk`, tab Mahasiswa (NIM) / Admin (email), arahan ke Profil/Kelompok/Dashboard
- [x] U2: Aktifkan akun — `/aktivasi`, simpan kata sandi + jenis kelamin, langsung masuk ke Profil saya
- [x] U3: Pulihkan akun — `/pemulihan`, kata sandi baru, kembali ke halaman masuk
- [x] U4: Masukkan NIM dan kode — `KodeTiketService` (hash, 7 hari / 30 menit, sekali pakai, `lockForUpdate`)
- [x] U5: Buat kata sandi — 8–128 karakter, ulangan harus sama
- [x] U77: Keluar (Logout) — menu akun → Keluar akun
- [x] Kerangka halaman: bilah atas + menu akun, sidebar, navigasi bawah HP (`<x-nav-bawah />`), penguncian fitur mahasiswa
- [x] Tes: `tests/Feature/Auth/U1MasukTest.php`, `U2U5AktivasiPemulihanTest.php` (21 tes lulus)

> Catatan Tahap 2:
> - Halaman Dashboard, Profil saya, dan Kelompok saya baru berisi judul; isinya dibangun di Tahap 5, 6, dan 9.
> - Ditambahkan batas 5 percobaan gagal per menit untuk masuk, aktivasi, dan pemulihan (tidak ada di prototipe).
> - Pesan galat Admin memakai "Email atau kata sandi tidak sesuai." (prototipe: "…demo tidak sesuai.").
> - `style.css`: alamat gambar halaman masuk diperbaiki menjadi `../img/desa-kkn.webp`.

---

## Tahap 3 — Data Master Admin: Prodi, Desa, DPL, Templat & Impor Excel
- [ ] U41: Lihat daftar prodi
- [ ] U42: Tambah prodi
- [ ] U43: Ubah prodi
- [ ] U44: Lihat daftar desa
- [ ] U45: Tambah desa
- [ ] U46: Tambah desa dari Excel
- [ ] U47: Ubah desa
- [ ] U48: Lihat daftar DPL
- [ ] U49: Tambah DPL
- [ ] U50: Tambah DPL dari Excel
- [ ] U51: Ubah DPL

---

## Tahap 4 — Peserta: Unggah Excel per Prodi, Kode Aktivasi, Detail & Ubah, Rekap
- [ ] U31: Lihat daftar peserta
- [ ] U32: Lihat detail peserta
- [ ] U33: Ubah peserta
- [ ] U34: Buat kode pemulihan
- [ ] U35: Tambah peserta ke kelompok
- [ ] U36: Tambah peserta dari Excel
- [ ] U37: Unduh daftar peserta
- [ ] U38: Buat kode aktivasi
- [ ] U39: Unduh kode aktivasi
- [ ] U40: Unduh rekap ukuran baju

---

## Tahap 5 — Profil Mahasiswa
- [ ] U6: Lihat profil
- [ ] U7: Isi data profil

---

## Tahap 6 — Kelompok Mahasiswa dan Kelompok Admin
- [ ] U8: Lihat kelompok saya
- [ ] U9: Tambah kelompok
- [ ] U10: Gabung ke kelompok
- [ ] U11: Keluar dari kelompok
- [ ] U12: Tanggapi permintaan menjadi ketua
- [ ] U13: Lihat daftar anggota kelompok
- [ ] U14: Hapus anggota
- [ ] U15: Ubah ketua
- [ ] U16: Hapus kelompok
- [ ] U17: Ubah jenis KKN
- [ ] U18: Ajukan kelompok
- [ ] U19: Ajukan perubahan anggota
- [ ] U52: Lihat daftar kelompok
- [ ] U53: Lihat detail kelompok
- [ ] U54: Ubah anggota kelompok
- [ ] U55: Tanggapi permintaan perubahan anggota
- [ ] U56: Unduh daftar kelompok
- [ ] U57: Setujui kelompok
- [ ] U58: Minta perbaikan kelompok

---

## Tahap 7 — Desa & Proposal
- [ ] U20: Lihat status desa dan proposal
- [ ] U21: Lihat desa dan proposal kelompok
- [ ] U22: Pilih desa
- [ ] U23: Unggah proposal
- [ ] U24: Ubah pilihan desa
- [ ] U25: Ajukan desa dan proposal
- [ ] U59: Lihat daftar proposal
- [ ] U60: Lihat detail proposal
- [ ] U61: Setujui desa dan proposal
- [ ] U62: Minta perbaikan proposal
- [ ] U63: Batalkan pengajuan proposal
- [ ] U64: Ubah desa kelompok

---

## Tahap 8 — Penugasan DPL, Surat Pengantar, Laporan Akhir
- [ ] U65: Lihat daftar penugasan DPL
- [ ] U66: Tetapkan DPL kelompok
- [ ] U26: Unduh surat pengantar
- [ ] U67: Lihat daftar surat pengantar
- [ ] U68: Unggah surat pengantar
- [ ] U27: Lihat laporan akhir kelompok
- [ ] U28: Unggah laporan akhir
- [ ] U69: Lihat daftar laporan akhir
- [ ] U70: Lihat detail laporan akhir
- [ ] U71: Unduh laporan akhir
- [ ] U72: Unduh rekap laporan akhir

---

## Tahap 9 — Dasbor, Tahap Periode, Ketentuan, Jadwal
- [ ] U29: Lihat dasbor
- [ ] U30: Ubah tahap pelaksanaan
- [ ] U73: Lihat ketentuan kelompok
- [ ] U74: Ubah ketentuan kelompok
- [ ] U75: Lihat jadwal dan tenggat
- [ ] U76: Ubah jadwal dan tenggat
