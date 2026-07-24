# 📍 Lokasi File-File Penting

## 🔍 Anda Bertanya: "Tidak Ada index.php, Adanya .htaccess"

Ini **NORMAL**! Karena `index.php` dan `.htaccess` ada di **folder `public`**, bukan di root.

---

## 📂 Struktur Folder Aplikasi Laravel Anda

```
aplikasi_pos/                    👈 ROOT FOLDER (yang Anda lihat sekarang)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/                      👈 FOLDER INI yang isinya harus ke public_html
│   ├── index.php               ✅ File ini ADA di sini
│   ├── .htaccess               ✅ File ini ADA di sini
│   ├── build/
│   ├── uploads/
│   ├── favicon.ico
│   └── robots.txt
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
├── .env                         👈 File konfigurasi (harus diedit)
├── artisan
├── composer.json
└── PANDUAN_DEPLOYMENT.md
```

---

## ✅ Yang Harus Anda Lakukan

### Langkah Sederhana:

**1. Buka folder `public`** di File Manager hosting
   - Klik folder `public` untuk masuk ke dalamnya
   - Di dalam folder ini Anda akan lihat `index.php` dan `.htaccess`

**2. Pindahkan ISI folder `public` ke `public_html`**
   - Tandai semua file dalam folder `public` (index.php, .htaccess, build/, uploads/, dll)
   - Copy atau pindahkan ke `public_html`

**3. Pindahkan folder LAIN (selain public) keluar dari public_html**
   - Buat folder `aplikasi_pos` sejajar dengan `public_html`
   - Pindahkan semua folder dan file (kecuali isi `public`) ke folder `aplikasi_pos`

---

## 🎯 Hasil Akhir di Hosting

```
/home/username/                       👈 Home directory
│
├── public_html/                      👈 Yang bisa diakses publik
│   ├── index.php                     ✅ Dipindah dari folder public
│   ├── .htaccess                     ✅ Dipindah dari folder public
│   ├── build/                        ✅ Dipindah dari folder public
│   ├── uploads/                      ✅ Dipindah dari folder public
│   ├── favicon.ico                   ✅ Dipindah dari folder public
│   └── robots.txt                    ✅ Dipindah dari folder public
│
└── aplikasi_pos/                     👈 Di LUAR public_html (aman)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env                          👈 Edit file ini!
    ├── artisan
    └── composer.json
```

---

## 🔧 Edit File index.php Setelah Dipindah

Setelah file `index.php` ada di `public_html`, edit file tersebut:

### Baris 16 - Ubah dari:
```php
require __DIR__.'/../vendor/autoload.php';
```

### Menjadi:
```php
require __DIR__.'/../aplikasi_pos/vendor/autoload.php';
```

---

### Baris 20 - Ubah dari:
```php
$app = require_once __DIR__.'/../bootstrap/app.php';
```

### Menjadi:
```php
$app = require_once __DIR__.'/../aplikasi_pos/bootstrap/app.php';
```

---

## ❓ Kenapa Harus Begini?

**Keamanan!** 

File seperti `.env`, `vendor/`, `database/` tidak boleh bisa diakses langsung dari browser. Kalau semua file ada di `public_html`, orang bisa akses file sensitif seperti:
- `https://domainanda.com/.env` ❌ BERBAHAYA!
- `https://domainanda.com/vendor/` ❌ BERBAHAYA!

Dengan struktur yang benar:
- `https://domainanda.com/` ✅ Aman (hanya akses index.php)
- File `.env` di luar public_html ✅ Tidak bisa diakses browser

---

## 📌 Kesimpulan

- ✅ File `index.php` dan `.htaccess` **MEMANG** ada, tapi di dalam **folder `public`**
- ✅ Isi folder `public` → pindah ke `public_html`
- ✅ Folder lainnya → pindah ke `aplikasi_pos` (di luar public_html)
- ✅ Edit `index.php` untuk update path ke folder `aplikasi_pos`

Sudah jelas? Lanjut ke langkah database dan .env! 🚀
