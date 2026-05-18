# 🌲 PineusTilu — Website Reservasi Glamping & Outbound

Proyek website resmi Pineus Tilu untuk informasi dan pemesanan aktivitas Glamping dan Outbound. Dibangun dengan Laravel, Vite, Tailwind, dan Blade.

## ✨ Fitur Utama
- Reservasi Glamping: selector area, galeri, dan peta.
- Reservasi Outbound: kategori aktivitas, galeri, dan detail info.
- Komponen UI konsisten: track + knob, tombol kategori/area, main image + thumbnails.
- Build tooling Vite untuk aset CSS/JS modern.

## 🛠️ Teknologi
- Backend: PHP 8.2+, Laravel 10+
- Frontend: Vite, Tailwind CSS, JavaScript modular
- View: Blade Templates
- Database: MySQL/SQLite (opsional)

## ⚙️ Persyaratan
- Composer, PHP 8.2+
- Node.js 18+ dan npm
- Database (opsional, jika data dinamis)

## 🚀 Setup Lokal (Windows)
1) Clone & masuk folder:
````powershell
git clone <repo-url>
cd PineusTilu
````
2) Backend:
````powershell
composer install
copy .env.example .env
php artisan key:generate
# opsional: set DB di .env, lalu jalankan migrasi
php artisan migrate --seed
````
3) Frontend:
````powershell
npm install
````
4) Jalankan aplikasi:
````powershell
php artisan serve
npm run dev
````
5) Akses:
- http://127.0.0.1:8000

Build production:
````powershell
npm run build
````

---

## 📁 Struktur Direktori
- resources/views/partials
  - reservasi-glamping/
    - styles.blade.php
    - … komponen tampilan glamping
  - reservasi-outbound/
    - styles.blade.php
    - detail.blade.php
    - … komponen tampilan outbound
- resources/js/pages
  - reservasi-glamping.js
  - reservasi-outbound.js

---

## 🎯 Catatan UI/Interaksi
- Glamping
  - .area-items: grid kolom tetap, jarak kiri–kanan setara.
  - Knob (#areaKnob) mengikuti item aktif, update saat resize.
- Outbound
  - .out-cat-list: grid repeat dengan lebar kolom sama.
  - Knob (#outKnob) sinkron dengan tombol aktif dan resize.

Palet warna utama: hijau #017249. Komponen galeri: main image + thumbnails.

---

## 📦 Skrip npm
- npm run dev — development dengan HMR
- npm run build — bundling aset untuk produksi

---

## 🧩 Troubleshooting
- Perubahan CSS/JS tidak muncul:
  - Pastikan npm run dev aktif atau jalankan npm run build.
  - Hard refresh (Ctrl+F5).
- Knob tidak sejajar:
  - Pastikan listener resize aktif di file JS terkait.

- Google Login error `Missing required parameter: client_id`:
  - Isi variabel berikut di `.env`:
    - `GOOGLE_CLIENT_ID`
    - `GOOGLE_CLIENT_SECRET`
    - `GOOGLE_REDIRECT_URI` (default: `${APP_URL}/auth/google/callback`)
  - Di Google Cloud Console, set **Authorized redirect URI** persis sama dengan `GOOGLE_REDIRECT_URI`.
  - Setelah mengubah `.env`, jalankan: `php artisan config:clear`

---

## 🔒 Lisensi
Hak cipta milik pemilik proyek. Penggunaan internal Pineus Tilu.
````