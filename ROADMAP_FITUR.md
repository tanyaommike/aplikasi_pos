# 🗺️ Roadmap Fitur - Aplikasi POS

## 📋 Status Legenda
- ✅ **Selesai** - Sudah implemented
- 🔄 **In Progress** - Sedang dikerjakan
- 📝 **Planned** - Direncanakan
- 💡 **Idea** - Ide untuk masa depan

---

## 🎯 **FASE 1: UI/UX & Core Improvements** (Prioritas Tinggi)

### 1. ✅ Dialog/Modal Confirmations (Ganti Alert)
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ Critical  
**Estimasi**: 2-3 jam  

**Detail:**
- Ganti semua `confirm()` dengan SweetAlert2/Modal dialog
- Konfirmasi delete dengan custom modal
- Konfirmasi checkout transaksi
- Success/Error notifications dengan toast

**Technical:**
```javascript
// Install SweetAlert2
npm install sweetalert2

// Contoh usage
Swal.fire({
  title: 'Hapus Produk?',
  text: "Data tidak bisa dikembalikan!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Ya, Hapus!',
  cancelButtonText: 'Batal'
})
```

**Files Affected:**
- `resources/views/kategori/index.blade.php`
- `resources/views/products/index.blade.php`
- `resources/views/transaksi/create.blade.php`
- `resources/js/app.js`

---

### 2. 📝 Forgot Password Feature
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ High  
**Estimasi**: 2-4 jam  

**Detail:**
- Halaman forgot password
- Email reset password link
- Token-based password reset
- Form reset password

**Technical:**
```bash
# Laravel sudah provide
php artisan make:controller Auth/PasswordResetController
```

**Features:**
- Email verification
- Token expiration (default 60 menit)
- Rate limiting untuk prevent abuse
- Modern UI untuk reset form

**Files to Create:**
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- Email template untuk reset link

---

### 3. 📝 Multiple Product Images (Min 3 Images)
**Status**: 📝 Planned  
**Priority**: ⭐⭐ Medium  
**Estimasi**: 4-6 jam  

**Detail:**
- Upload multiple images per produk (min 3)
- Image gallery dengan carousel/slider
- Set main image (featured image)
- Drag & drop reorder images
- Image preview sebelum upload
- Compress & resize otomatis

**Technical:**
```bash
# Migration
php artisan make:migration create_produk_images_table

# Model
php artisan make:model ProdukImage
```

**Database Schema:**
```php
Schema::create('produk_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('produk_id')->constrained()->onDelete('cascade');
    $table->string('image_path');
    $table->integer('order')->default(0);
    $table->boolean('is_primary')->default(false);
    $table->timestamps();
});
```

**UI Components:**
- Dropzone.js untuk drag & drop
- Swiper.js untuk gallery carousel
- Image preview grid
- Delete individual image

---

### 4. 📝 Warning Stok Rendah (Dynamic Display)
**Status**: 📝 Planned (Partially implemented)  
**Priority**: ⭐⭐⭐ High  
**Estimasi**: 2-3 jam  

**Detail:**
- Setting threshold stok rendah (default: 5)
- Real-time badge di product card
- Alert banner di dashboard
- Email notification ke admin (optional)
- Filter produk stok rendah
- Bulk restock action

**Current Status:**
- ✅ Badge di product card
- ✅ Alert di dashboard
- 📝 Email notification
- 📝 Setting threshold custom
- 📝 Bulk action

**Enhancement:**
```php
// Setting model
php artisan make:model Setting

// Config stok threshold per kategori
'stok_minimum' => [
    'default' => 5,
    'makanan' => 10,
    'minuman' => 15,
]
```

---

## 💰 **FASE 2: Payment & Discount System** (Prioritas Tinggi)

### 5. 📝 QRIS Static + Auto Payment Verification
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ Critical  
**Estimasi**: 6-8 jam  

**Detail:**
- Generate QRIS code per transaksi
- Static QRIS untuk merchant
- Auto-verify payment via API (Midtrans/Xendit)
- Payment status tracking
- Webhook untuk payment confirmation
- Manual confirmation option

**Technical Stack:**
```bash
# Payment Gateway Options
composer require midtrans/midtrans-php
# atau
composer require xendit/xendit-php
```

**Features:**
- QRIS code display di checkout
- QR code generator
- Payment timeout (15 menit)
- Payment status: pending, success, failed
- Auto-generate invoice setelah payment success

**Database Schema:**
```php
// Add to transaksis table
$table->enum('payment_method', ['cash', 'qris', 'debit', 'credit'])->default('cash');
$table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
$table->string('payment_reference')->nullable();
$table->timestamp('paid_at')->nullable();
```

**API Integration:**
- Midtrans QRIS API
- Webhook endpoint untuk callback
- Payment verification cron job

---

### 6. 📝 Voucher Diskon + Diskon Produk
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ High  
**Estimasi**: 8-10 jam  

**Detail A: Diskon Produk**
- Diskon percentage atau fixed amount
- Harga awal (tercoret) + harga diskon
- Badge "DISKON X%"
- Periode diskon (start-end date)
- Auto-disable setelah expired

**Detail B: Voucher System**
- Generate voucher code
- Tipe: percentage, fixed, free shipping
- Minimum purchase
- Maximum discount
- Usage limit (per voucher & per user)
- Validity period
- Auto-generate code

**Database Schema:**
```sql
-- Produk discount
ALTER TABLE produk ADD COLUMN harga_awal INTEGER NULL;
ALTER TABLE produk ADD COLUMN diskon_percentage DECIMAL(5,2) NULL;
ALTER TABLE produk ADD COLUMN diskon_mulai TIMESTAMP NULL;
ALTER TABLE produk ADD COLUMN diskon_selesai TIMESTAMP NULL;

-- Voucher table
CREATE TABLE vouchers (
    id BIGINT PRIMARY KEY,
    code VARCHAR(50) UNIQUE,
    type ENUM('percentage', 'fixed', 'free_shipping'),
    value DECIMAL(10,2),
    min_purchase DECIMAL(10,2) DEFAULT 0,
    max_discount DECIMAL(10,2) NULL,
    usage_limit INT DEFAULT 1,
    used_count INT DEFAULT 0,
    valid_from TIMESTAMP,
    valid_until TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Voucher usage tracking
CREATE TABLE voucher_usages (
    id BIGINT PRIMARY KEY,
    voucher_id BIGINT,
    transaksi_id BIGINT,
    user_id BIGINT,
    discount_amount DECIMAL(10,2),
    used_at TIMESTAMP
);
```

**UI Features:**
- Input voucher code di checkout
- Display diskon applied
- Harga tercoret + harga final
- Badge diskon di product card
- Admin: CRUD voucher management

**Validation:**
- Check voucher validity
- Check usage limit
- Check minimum purchase
- Check expiry date

---

## 📊 **FASE 3: Analytics & Reports** (Prioritas Medium)

### 7. 📝 Produk Terlaris (Top Products)
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ High  
**Estimasi**: 4-5 jam  

**Detail:**
- Top 3 produk terlaris di dashboard
- Halaman "Semua Produk Terlaris" dengan pagination
- Filter by periode (hari ini, minggu ini, bulan ini, custom)
- Sortable by quantity sold atau total revenue
- Chart visualization (bar chart/pie chart)

**Query Optimization:**
```php
// Top products by quantity
Produk::withCount(['transaksiDetail as total_terjual' => function ($query) {
    $query->select(DB::raw('SUM(jumlah)'));
}])
->orderBy('total_terjual', 'desc')
->take(10)
->get();

// Top products by revenue
Produk::withSum('transaksiDetail', 'subtotal')
->orderBy('transaksi_detail_sum_subtotal', 'desc')
->take(10)
->get();
```

**UI Components:**
- Card untuk Top 3 di dashboard
- Table untuk full list
- Chart.js untuk visualization
- Export ke Excel/PDF

**Database:**
```php
// No additional table needed
// Use existing transaksi_detail with aggregation
```

---

### 8. 📝 Manajemen Stok & History (Barang Masuk/Keluar)
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ Critical  
**Estimasi**: 6-8 jam  

**Detail:**
- Log setiap perubahan stok
- Tipe: masuk (restock), keluar (sold), adjustment
- Track: tanggal, jumlah, user, keterangan
- Halaman history stok per produk
- Filter & search
- Export history

**Database Schema:**
```php
Schema::create('stok_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('produk_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained();
    $table->enum('type', ['masuk', 'keluar', 'adjustment', 'sold']);
    $table->integer('quantity'); // positive for masuk, negative for keluar
    $table->integer('stok_before');
    $table->integer('stok_after');
    $table->foreignId('transaksi_id')->nullable(); // if type = sold
    $table->text('keterangan')->nullable();
    $table->timestamps();
});
```

**Features:**
- Auto-log saat transaksi (type: sold)
- Manual restock form (type: masuk)
- Stock adjustment (type: adjustment)
- Timeline view per produk
- Filter by type, date range, user
- Statistics: total masuk, keluar, current stok

**Observer Pattern:**
```php
// ProdukObserver
public function updated(Produk $produk)
{
    if ($produk->isDirty('stok')) {
        StokHistory::create([
            'produk_id' => $produk->id,
            'type' => 'adjustment',
            'quantity' => $produk->stok - $produk->getOriginal('stok'),
            'stok_before' => $produk->getOriginal('stok'),
            'stok_after' => $produk->stok,
            'user_id' => auth()->id(),
        ]);
    }
}
```

---

### 9. 📝 Harga Pokok & Profit Tracking
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ High  
**Estimasi**: 4-5 jam  

**Detail:**
- Field `harga_pokok` (modal) di produk
- Field `harga_jual` (existing: harga)
- Hitung profit per produk
- Profit margin percentage
- Total profit per transaksi
- Laporan profit harian/bulanan
- Chart profit over time

**Database Schema:**
```php
// Add to produk table
$table->integer('harga_pokok')->default(0); // Modal/cost price
// harga (existing) = harga jual

// Add to transaksi_detail
$table->integer('harga_pokok_satuan')->default(0);
$table->integer('profit_per_item')->default(0);

// Add to transaksis
$table->integer('total_modal')->default(0);
$table->integer('total_profit')->default(0);
$table->decimal('profit_margin', 5, 2)->default(0); // percentage
```

**Calculations:**
```php
// Profit per item
$profit = $harga_jual - $harga_pokok;

// Profit margin
$margin = (($harga_jual - $harga_pokok) / $harga_jual) * 100;

// Total profit per transaksi
$total_profit = $total_harga - $total_modal;
```

**Reports:**
- Dashboard profit card
- Profit by category
- Profit by product
- Profit trend chart
- Best margin products

---

## 🔐 **FASE 4: Security & Testing** (Prioritas Critical)

### 10. ✅ Testing Suite (Pest PHP)
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ Critical  
**Estimasi**: 8-10 jam  

**Detail:**
- Unit tests untuk Models
- Feature tests untuk Controllers
- Browser tests untuk UI (Dusk)
- API tests
- Test coverage minimum 70%

**Test Categories:**
```bash
# Feature Tests
tests/Feature/Auth/LoginTest.php
tests/Feature/Kategori/KategoriCrudTest.php
tests/Feature/Produk/ProdukCrudTest.php
tests/Feature/Transaksi/CheckoutTest.php

# Unit Tests
tests/Unit/Models/ProdukTest.php
tests/Unit/Models/TransaksiTest.php
tests/Unit/Helpers/CurrencyTest.php

# Browser Tests
tests/Browser/LoginTest.php
tests/Browser/CheckoutFlowTest.php
```

**Setup:**
```bash
# Install Pest
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev

# Run tests
php artisan test
```

---

### 11. 📝 Security Enhancements
**Status**: 📝 Planned  
**Priority**: ⭐⭐⭐ Critical  
**Estimasi**: 4-6 jam  

**Checklist:**
- [ ] Rate limiting untuk login (5 attempts per 1 minute)
- [ ] XSS protection (escape output)
- [ ] SQL injection prevention (use Query Builder/Eloquent)
- [ ] CSRF token validation
- [ ] File upload validation (type, size, mime)
- [ ] Role-based authorization middleware
- [ ] Input sanitization
- [ ] Secure password hashing (bcrypt)
- [ ] HTTPS enforcement di production
- [ ] Session security configuration

**Implementation:**
```php
// Rate limiting
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/login', [LoginController::class, 'store']);
});

// Role middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('kategori', KategoriController::class);
});

// File upload validation
'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
```

---

## 📈 **FASE 5: Advanced Features** (Prioritas Low-Medium)

### 12. 📝 Export & Reports
**Status**: 📝 Planned  
**Priority**: ⭐⭐ Medium  
**Estimasi**: 6-8 jam  

**Features:**
- Export transaksi ke Excel (Laravel Excel)
- Export transaksi ke PDF (DomPDF)
- Laporan penjualan harian/mingguan/bulanan
- Laporan stok
- Laporan profit
- Custom date range
- Schedule email reports

**Technical:**
```bash
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf
```

---

### 13. 📝 Customer Management
**Status**: 📝 Planned  
**Priority**: ⭐⭐ Medium  
**Estimasi**: 6-8 jam  

**Features:**
- Database pelanggan
- Customer profile (nama, phone, email, address)
- Purchase history per customer
- Loyalty points system
- Member vs Non-member pricing
- Birthday discount automation

**Database:**
```sql
CREATE TABLE customers (
    id BIGINT PRIMARY KEY,
    name VARCHAR(100),
    phone VARCHAR(20) UNIQUE,
    email VARCHAR(100),
    address TEXT,
    points INT DEFAULT 0,
    member_since DATE,
    birth_date DATE NULL,
    is_member BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Add to transaksis
ALTER TABLE transaksis ADD COLUMN customer_id BIGINT NULL;
```

---

### 14. 📝 Dashboard Charts & Visualization
**Status**: 📝 Planned  
**Priority**: ⭐⭐ Medium  
**Estimasi**: 4-6 jam  

**Charts:**
- Sales trend (line chart)
- Revenue by category (pie chart)
- Top products (bar chart)
- Profit over time (area chart)
- Daily/weekly/monthly comparison

**Technical:**
```bash
# Install Chart.js
npm install chart.js
```

---

### 15. 💡 Multi-Payment Methods
**Status**: 💡 Idea  
**Priority**: ⭐ Low  

**Features:**
- Cash
- QRIS (implemented in Fase 2)
- Debit Card
- Credit Card
- E-wallet (GoPay, OVO, Dana, ShopeePay)
- Split payment (cash + card)
- Kembalian otomatis untuk cash

---

### 16. 💡 Supplier Management
**Status**: 💡 Idea  
**Priority**: ⭐ Low  

**Features:**
- Database supplier
- Purchase orders
- Supplier contact info
- Payment terms
- Product by supplier

---

### 17. 💡 Barcode & Label Printing
**Status**: 💡 Idea  
**Priority**: ⭐ Low  

**Features:**
- Generate barcode per produk
- Print label dengan barcode
- Scan barcode di kasir
- Bulk print labels

---

### 18. 💡 Progressive Web App (PWA)
**Status**: 💡 Idea  
**Priority**: ⭐ Low  

**Features:**
- Install ke home screen
- Offline mode
- Push notifications
- Fast loading

---

## 🚀 **Timeline & Priority Summary**

### **Sprint 1 (1-2 Minggu): UI/UX Core** ⭐⭐⭐
1. ✅ Dialog/Modal confirmations (2-3 jam)
2. ✅ Forgot password (2-4 jam)
3. ✅ Warning stok enhancement (2-3 jam)
4. ✅ Security basics (4-6 jam)

**Total: ~15 jam**

---

### **Sprint 2 (2-3 Minggu): Payment & Discount** ⭐⭐⭐
5. ✅ QRIS Static + Auto payment (6-8 jam)
6. ✅ Voucher & Diskon produk (8-10 jam)
7. ✅ Harga pokok & profit tracking (4-5 jam)

**Total: ~23 jam**

---

### **Sprint 3 (1-2 Minggu): Analytics & Stock** ⭐⭐⭐
8. ✅ Produk terlaris (4-5 jam)
9. ✅ Manajemen stok & history (6-8 jam)
10. ✅ Multiple images (4-6 jam)

**Total: ~19 jam**

---

### **Sprint 4 (1-2 Minggu): Testing & Reports** ⭐⭐
11. ✅ Testing suite (8-10 jam)
12. ✅ Export & Reports (6-8 jam)
13. ✅ Dashboard charts (4-6 jam)

**Total: ~24 jam**

---

### **Sprint 5 (Optional): Advanced Features** ⭐
14. Customer management (6-8 jam)
15. Supplier management (6-8 jam)
16. Barcode system (4-6 jam)
17. PWA features (8-10 jam)

**Total: ~30 jam**

---

## 📝 **Notes & Considerations**

### **Priority Grading:**
- ⭐⭐⭐ **Critical/High** - Must have, core functionality
- ⭐⭐ **Medium** - Should have, improves UX significantly
- ⭐ **Low** - Nice to have, can be delayed

### **Estimasi Waktu:**
- Based on solo developer
- Includes: development, testing, debugging
- Tidak termasuk: requirements gathering, design mockups

### **Dependencies:**
- Chart.js untuk visualisasi
- SweetAlert2 untuk dialogs
- Payment gateway SDK (Midtrans/Xendit)
- Laravel Excel untuk export
- DomPDF untuk PDF generation

### **Technical Debt to Address:**
- Add comprehensive error handling
- Implement proper logging
- Set up monitoring (Laravel Telescope)
- Database optimization & indexing
- Image optimization pipeline

---

## ✅ **Quick Wins (Bisa dikerjakan dalam 1-2 hari):**

1. **Dialog confirmations** (2-3 jam) ⚡
2. **Warning stok enhancement** (2-3 jam) ⚡
3. **Forgot password** (2-4 jam) ⚡
4. **Produk terlaris basic** (3-4 jam) ⚡

**Total Quick Wins: ~12 jam = 1.5 hari kerja**

---

## 🎯 **Recommended First 3 Tasks:**

1. **Dialog/Modal Confirmations** - Improve UX immediately
2. **Forgot Password** - Essential security feature
3. **QRIS Payment** - Modern payment method

Setelah 3 ini selesai, aplikasi sudah sangat ready untuk production use! 🚀

---

**Last Updated**: 2026-07-23  
**Version**: 2.0.0  
**Author**: Development Team
