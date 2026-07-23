# 🚀 Cara Menjalankan Aplikasi POS

## Persiapan
Pastikan sudah install:
- PHP 8.4+
- Composer
- Node.js & NPM
- Laravel Herd (atau web server lain)

## Langkah-langkah

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database
Edit file `.env`, sesuaikan database:
```env
DB_CONNECTION=sqlite
# Atau gunakan MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=pos_db
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4. Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Development Server

**Opsi 1: Menggunakan Laravel Herd (Recommended)**
- Buka aplikasi Herd
- Aplikasi otomatis running di: `http://aplikasi_pos.test`

**Opsi 2: Menggunakan php artisan serve**
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite asset bundler
npm run dev
```

Aplikasi akan running di: `http://localhost:8000`

### 6. Login ke Aplikasi

**Admin Account:**
- Email: `admin@pos.test`
- Password: `password`

**Kasir Account:**
- Email: `kasir@pos.test`  
- Password: `password`

## Testing Aplikasi

### 1. Test sebagai Admin
- Login dengan akun admin
- Akses Dashboard → lihat statistik
- Kelola Kategori (CRUD)
- Kelola Produk (CRUD)
- Lihat Transaksi

### 2. Test sebagai Kasir
- Login dengan akun kasir
- Akses Dashboard
- Buat Transaksi Baru
- Tambah produk ke keranjang
- Checkout
- Lihat struk

### 3. Test UI Responsive
- Buka di mobile browser
- Test hamburger menu
- Test touch interactions
- Test grid layouts

## Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "Vite manifest not found"
```bash
npm run build
```

### Error: "Storage link"
```bash
php artisan storage:link
```

### Database error
```bash
php artisan migrate:fresh --seed
```

### Cache issues
```bash
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
```

## Fitur yang Tersedia

✅ Login & Register  
✅ Dashboard dengan Statistik  
✅ Manajemen Kategori (CRUD)  
✅ Manajemen Produk (CRUD + Upload Foto)  
✅ Transaksi/Kasir (Cart System)  
✅ Riwayat Transaksi  
✅ Invoice/Struk Transaksi  
✅ Responsive Design  
✅ Modern UI/UX  

## Stack Teknologi

- **Backend**: Laravel 13.x, PHP 8.4
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Database**: SQLite / MySQL
- **Icons**: FontAwesome 6.4
- **Font**: Inter

---

**Selamat mencoba! 🎉**
