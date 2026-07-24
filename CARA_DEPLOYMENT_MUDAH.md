# 🚀 Cara Deployment MUDAH (File Sudah di public_html)

## 📌 Situasi Anda Sekarang:

Anda sudah upload dan extract **SEMUA file** aplikasi Laravel ke folder `public_html`. Struktur sekarang:

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          👈 Folder ini yang harusnya jadi root web
│   ├── index.php
│   └── .htaccess
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── composer.json
└── vite.config.js
```

---

## ⚠️ MASALAH:
File seperti `.env`, `vendor/`, `database/` bisa diakses langsung dari browser! Ini **BERBAHAYA untuk keamanan**.

---

## ✅ SOLUSI MUDAH (3 Langkah):

### 🔹 Opsi 1: Redirect ke Folder Public (REKOMENDASI)

#### Langkah 1: Buat File .htaccess Baru di Root public_html

Buat file `.htaccess` **di root `public_html`** (sejajar dengan folder `app`, `public`, dll) dengan isi:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect semua request ke folder public
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L,QSA]
</IfModule>

# Proteksi file sensitif
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "composer\.(json|lock)">
    Order allow,deny
    Deny from all
</FilesMatch>

<FilesMatch "package(-lock)?\.json">
    Order allow,deny
    Deny from all
</FilesMatch>
```

**Penjelasan:** File ini akan otomatis redirect semua akses ke `https://domainanda.com/` menuju ke folder `public/`.

---

#### Langkah 2: Edit File .env

Cari file `.env` di `public_html/.env` dan edit:

```env
# WAJIB diubah ke production
APP_ENV=production

# WAJIB generate ulang di langkah 4
APP_KEY=

# WAJIB false untuk keamanan
APP_DEBUG=false

# Ganti dengan domain Anda
APP_URL=https://domainanda.com

# Database MySQL - ganti dengan kredensial Anda
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_database_anda
DB_PASSWORD=password_database_anda

# Tetap gunakan database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

#### Langkah 3: Buat Database MySQL

1. Login **cPanel** → **MySQL Databases**
2. Buat database baru (catat namanya)
3. Buat user database baru (catat username & password)
4. Tambahkan user ke database dengan **ALL PRIVILEGES**
5. Isi kredensial database di file `.env` (langkah 2)

---

#### Langkah 4: Jalankan Perintah via SSH/Terminal

Login ke SSH atau Terminal cPanel:

```bash
# Masuk ke folder public_html
cd ~/public_html

# Generate Application Key
php artisan key:generate

# Jalankan migrasi database
php artisan migrate --force

# Jalankan seeder (isi data awal + user admin & kasir)
php artisan db:seed --force

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permission
chmod -R 755 storage bootstrap/cache
```

---

#### Langkah 5: Set Permission Folder

Via SSH:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

Atau via File Manager cPanel:
- Klik kanan folder `storage` → Change Permissions → 755
- Klik kanan folder `bootstrap/cache` → Change Permissions → 755

---

## 🎯 Selesai!

Akses website Anda: `https://domainanda.com`

**Login dengan:**
- **Admin** → email: `admin@pos.test`, password: `password`
- **Kasir** → email: `kasir@pos.test`, password: `password`

⚠️ **SEGERA GANTI PASSWORD** setelah login!

---

## 🔹 Opsi 2: Pindahkan File (LEBIH AMAN)

Jika hosting Anda support, lebih baik pindahkan file:

### Struktur Ideal:
```
/home/username/
├── aplikasi_pos/          👈 File Laravel di sini (di LUAR public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
│
└── public_html/           👈 Hanya isi folder public
    ├── index.php
    ├── .htaccess
    ├── build/
    └── uploads/
```

### Langkah Pemindahan:

1. **Buat folder `aplikasi_pos`** sejajar dengan `public_html`
2. **Pindahkan SEMUA kecuali folder `public`** ke `aplikasi_pos`
3. **Pindahkan ISI folder `public`** ke `public_html`
4. **Edit `public_html/index.php`**:

Ubah baris 16:
```php
require __DIR__.'/../vendor/autoload.php';
```
Menjadi:
```php
require __DIR__.'/../aplikasi_pos/vendor/autoload.php';
```

Ubah baris 20:
```php
$app = require_once __DIR__.'/../bootstrap/app.php';
```
Menjadi:
```php
$app = require_once __DIR__.'/../aplikasi_pos/bootstrap/app.php';
```

5. **Lanjutkan dengan langkah 2-5 dari Opsi 1**

---

## 🐛 Troubleshooting

### Error 500 Internal Server Error
```bash
# Cek log error
tail -f storage/logs/laravel.log

# Aktifkan debug sementara
# Edit .env: APP_DEBUG=true
# Reload website, lihat pesan error
# Matikan lagi: APP_DEBUG=false
```

### Error "No application encryption key"
```bash
php artisan key:generate
```

### Error Database Connection
- Cek kredensial di `.env`
- Test koneksi database via phpMyAdmin
- Pastikan `DB_HOST=127.0.0.1` atau `localhost`

### CSS/JS Tidak Muncul
```bash
# Di komputer lokal
npm install
npm run build

# Upload folder public/build ke hosting
```

### Permission Denied
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## 📊 Checklist Deployment

- [ ] File `.htaccess` di root public_html (untuk redirect ke folder public)
- [ ] File `.env` sudah diedit (production, debug false, database MySQL)
- [ ] Database MySQL sudah dibuat
- [ ] `php artisan key:generate` sudah dijalankan
- [ ] `php artisan migrate --force` sudah dijalankan
- [ ] `php artisan db:seed --force` sudah dijalankan
- [ ] Permission folder `storage` dan `bootstrap/cache` sudah 755
- [ ] `php artisan config:cache` sudah dijalankan
- [ ] Website bisa diakses dan login berhasil
- [ ] Password admin dan kasir sudah diganti

---

## 🔐 Keamanan Tambahan

Setelah berhasil deploy, **SEGERA LAKUKAN:**

1. **Ganti password default** admin dan kasir
2. **Backup database** secara berkala
3. **Update Laravel** jika ada versi baru
4. **Monitor log** di `storage/logs/laravel.log`
5. **Jangan share kredensial** .env ke siapapun

---

## ✅ Kesimpulan

**YA, Anda HARUS:**
- ✅ Edit file `.env` (wajib!)
- ✅ Buat database MySQL (wajib!)
- ✅ Jalankan `php artisan migrate` (wajib!)
- ✅ Jalankan `php artisan db:seed` (wajib! - untuk data awal & user login)

**Seeder akan membuat:**
- User Admin (email: admin@pos.test)
- User Kasir (email: kasir@pos.test)
- Data kategori contoh
- Data produk contoh
- Data transaksi contoh

Tanpa seeder, Anda tidak bisa login karena belum ada user di database!

---

Semoga berhasil! 🎉 Jika ada error, share pesan errornya biar bisa saya bantu.
