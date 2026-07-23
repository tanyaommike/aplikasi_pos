<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'POS System') }} - Aplikasi Kasir Digital untuk Bisnis Anda</title>
        <meta name="description" content="Kelola kasir, stok, dan laporan penjualan dalam satu aplikasi. Cepat, mudah, dan cocok untuk kafe, restoran, hingga retail.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="antialiased bg-white text-slate-800" x-data="{ mobileMenu: false }">

        <!-- Navbar -->
        <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="/" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                            <i class="fas fa-cash-register text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">POS System</span>
                    </a>

                    <div class="hidden lg:flex items-center gap-8">
                        <a href="#keunggulan" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Keunggulan</a>
                        <a href="#cara-kerja" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Cara Kerja</a>
                        <a href="#testimoni" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Testimoni</a>
                        <a href="#faq" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">FAQ</a>
                        <a href="#kontak" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Kontak</a>
                    </div>

                    <div class="hidden lg:flex items-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all shadow-md hover:shadow-lg">
                                <i class="fas fa-th-large"></i>
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 px-4 py-2.5 transition-colors">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all shadow-md hover:shadow-lg">
                                    Mulai Gratis
                                </a>
                            @endif
                        @endauth
                    </div>

                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:bg-slate-100">
                        <i class="fas fa-bars text-lg" x-show="!mobileMenu"></i>
                        <i class="fas fa-times text-lg" x-show="mobileMenu" x-cloak></i>
                    </button>
                </div>
            </div>

            <div x-show="mobileMenu" x-cloak x-transition class="lg:hidden bg-white border-t border-slate-200 px-4 py-4 space-y-1">
                <a href="#keunggulan" @click="mobileMenu = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Keunggulan</a>
                <a href="#cara-kerja" @click="mobileMenu = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Cara Kerja</a>
                <a href="#testimoni" @click="mobileMenu = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Testimoni</a>
                <a href="#faq" @click="mobileMenu = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">FAQ</a>
                <a href="#kontak" @click="mobileMenu = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50">Kontak</a>
                <div class="pt-3 mt-3 border-t border-slate-200 flex flex-col gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold py-2.5 px-6 rounded-xl">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-center font-semibold text-slate-700 py-2.5 px-6 rounded-xl border border-slate-200">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold py-2.5 px-6 rounded-xl">Mulai Gratis</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
            <div class="absolute top-20 right-0 w-96 h-96 bg-purple-200/40 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-200/40 rounded-full blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <i class="fas fa-bolt"></i>
                            Satu Aplikasi untuk Semua Kebutuhan Kasir
                        </span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                            Kelola Bisnis Anda
                            <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Lebih Cepat &amp; Rapi</span>
                        </h1>
                        <p class="text-lg text-slate-600 mb-8 max-w-xl">
                            Dari transaksi kasir, pantau stok real-time, sampai laporan penjualan otomatis &mdash; semua dalam satu dashboard yang mudah dipakai, baik untuk kafe, restoran, maupun toko retail.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 mb-10">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl transition-all shadow-lg hover:shadow-xl">
                                    <i class="fas fa-rocket"></i>
                                    Mulai Gratis Sekarang
                                </a>
                            @endif
                            <a href="#keunggulan" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-800 font-semibold py-4 px-8 rounded-xl transition-all shadow-sm border border-slate-200">
                                <i class="fas fa-play-circle text-indigo-600"></i>
                                Lihat Fitur
                            </a>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-8 gap-y-3 text-sm text-slate-500">
                            <span class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Multi Metode Pembayaran</span>
                            <span class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Stok Real-time</span>
                            <span class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Laporan Otomatis</span>
                        </div>
                    </div>

                    <!-- Dashboard preview mockup -->
                    <div class="relative">
                        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 p-6 rotate-1 hover:rotate-0 transition-transform duration-500">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-cash-register text-white text-xs"></i>
                                    </div>
                                    <span class="font-bold text-slate-800 text-sm">Dashboard</span>
                                </div>
                                <div class="flex gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-300"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-300"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-300"></span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100">
                                    <i class="fas fa-wallet text-indigo-600 mb-2"></i>
                                    <p class="text-xl font-bold text-slate-800">Rp 4.2Jt</p>
                                    <p class="text-xs text-slate-500">Pendapatan Hari Ini</p>
                                </div>
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                                    <i class="fas fa-receipt text-green-600 mb-2"></i>
                                    <p class="text-xl font-bold text-slate-800">38</p>
                                    <p class="text-xs text-slate-500">Transaksi</p>
                                </div>
                            </div>
                            <div class="space-y-2.5">
                                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full w-4/5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div></div>
                                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full w-3/5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div></div>
                                <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full w-2/3 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div></div>
                            </div>
                        </div>
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl border border-slate-200 p-4 flex items-center gap-3 -rotate-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Transaksi Berhasil</p>
                                <p class="text-xs text-slate-500">TRX-20260723</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Keunggulan / Fitur -->
        <section id="keunggulan" class="py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Keunggulan</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-3 mb-4">Semua yang Anda Butuhkan untuk Berjualan</h2>
                    <p class="text-slate-600">Fitur lengkap yang dirancang supaya operasional toko Anda berjalan lebih cepat dan minim kesalahan.</p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md mb-5">
                            <i class="fas fa-cash-register text-white text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Kasir Cepat &amp; Intuitif</h3>
                        <p class="text-sm text-slate-600">Tambah produk ke keranjang, cek stok otomatis, dan selesaikan transaksi hanya dalam beberapa klik.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md mb-5">
                            <i class="fas fa-warehouse text-white text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Manajemen Stok Real-time</h3>
                        <p class="text-sm text-slate-600">Pantau stok masuk, keluar, dan penyesuaian lengkap dengan riwayat mutasi yang bisa dilacak kapan saja.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-md mb-5">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Laporan &amp; Analitik</h3>
                        <p class="text-sm text-slate-600">Grafik tren penjualan, produk terlaris, dan breakdown metode pembayaran dalam satu dashboard.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md mb-5">
                            <i class="fas fa-credit-card text-white text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Multi Metode Pembayaran</h3>
                        <p class="text-sm text-slate-600">Terima pembayaran tunai, QRIS, hingga transfer bank dengan hitung kembalian otomatis.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl flex items-center justify-center shadow-md mb-5">
                            <i class="fas fa-users-cog text-white text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Akses Berbasis Peran</h3>
                        <p class="text-sm text-slate-600">Admin dan kasir punya akses berbeda, jadi data sensitif seperti laporan tetap terjaga.</p>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-md mb-5">
                            <i class="fas fa-file-export text-white text-lg"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Cetak &amp; Export Laporan</h3>
                        <p class="text-sm text-slate-600">Cetak struk transaksi, simpan laporan sebagai PDF, atau export ke Excel dengan sekali klik.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cara Kerja -->
        <section id="cara-kerja" class="py-20 lg:py-28 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Cara Kerja</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-3 mb-4">Mulai dalam 3 Langkah Mudah</h2>
                    <p class="text-slate-600">Tidak perlu instalasi rumit atau pelatihan panjang &mdash; siap dipakai dalam hitungan menit.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200 text-center">
                        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg mb-5 text-white font-bold text-xl">1</div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Setup Produk &amp; Kategori</h3>
                        <p class="text-sm text-slate-600">Tambahkan daftar produk, harga, dan kategori sesuai jenis usaha Anda.</p>
                    </div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200 text-center">
                        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg mb-5 text-white font-bold text-xl">2</div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Mulai Bertransaksi</h3>
                        <p class="text-sm text-slate-600">Kasir tinggal pilih produk, pilih metode pembayaran, dan cetak struk.</p>
                    </div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200 text-center">
                        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg mb-5 text-white font-bold text-xl">3</div>
                        <h3 class="font-bold text-slate-800 text-lg mb-2">Pantau Performa</h3>
                        <p class="text-sm text-slate-600">Lihat laporan penjualan dan stok secara real-time dari dashboard admin.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimoni -->
        <section id="testimoni" class="py-20 lg:py-28">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Testimoni</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-3 mb-4">Dipercaya Pelaku Usaha Kecil &amp; Menengah</h2>
                    <p class="text-slate-600">Contoh gambaran manfaat yang dirasakan pemilik usaha setelah beralih ke sistem kasir digital.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex gap-1 text-amber-400 mb-4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-600 text-sm mb-6">&ldquo;Sebelumnya saya catat penjualan manual di buku, sering selisih stok. Sekarang semua tercatat rapi dan laporan tinggal lihat dashboard.&rdquo;</p>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">RA</div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">Rina Amelia</p>
                                <p class="text-xs text-slate-500">Pemilik Kedai Kopi Senja</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex gap-1 text-amber-400 mb-4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-600 text-sm mb-6">&ldquo;Fitur multi pembayarannya membantu banget karena pelanggan sekarang banyak yang bayar QRIS. Kasir juga jadi lebih cepat kerjanya.&rdquo;</p>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold">DP</div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">Dimas Prasetyo</p>
                                <p class="text-xs text-slate-500">Owner Warung Makan Berkah</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex gap-1 text-amber-400 mb-4">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-600 text-sm mb-6">&ldquo;Riwayat mutasi stoknya sangat membantu waktu stock opname akhir bulan, tidak perlu hitung manual satu-satu lagi.&rdquo;</p>
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold">SN</div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">Sinta Nuraini</p>
                                <p class="text-xs text-slate-500">Manajer Toko Retail Harmoni</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="py-20 lg:py-28 bg-slate-50">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">FAQ</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-3 mb-4">Pertanyaan yang Sering Diajukan</h2>
                    <p class="text-slate-600">Masih ada yang ingin ditanyakan? Hubungi kami di bagian kontak di bawah.</p>
                </div>

                <div class="space-y-4" x-data="{ open: 1 }">
                    @php
                        $faqs = [
                            ['q' => 'Apakah aplikasi ini bisa dipakai untuk berbagai jenis usaha?', 'a' => 'Bisa. Aplikasi ini fleksibel untuk kafe, restoran, toko retail, hingga usaha kecil lain yang butuh pencatatan transaksi dan stok.'],
                            ['q' => 'Apakah stok produk otomatis berkurang saat ada transaksi?', 'a' => 'Ya. Setiap transaksi akan langsung mengurangi stok produk terkait secara otomatis, lengkap dengan riwayat mutasinya.'],
                            ['q' => 'Metode pembayaran apa saja yang didukung?', 'a' => 'Saat ini mendukung pembayaran tunai (dengan hitung kembalian otomatis), QRIS, dan transfer bank.'],
                            ['q' => 'Apakah semua karyawan bisa melihat laporan penjualan?', 'a' => 'Tidak. Akses dibedakan berdasarkan peran - kasir hanya bisa mengelola transaksi, sementara laporan dan manajemen stok hanya bisa diakses admin.'],
                            ['q' => 'Bisakah saya mencetak struk atau mengekspor laporan?', 'a' => 'Bisa. Struk transaksi bisa langsung dicetak, dan laporan penjualan bisa disimpan sebagai PDF atau diekspor ke Excel/CSV.'],
                            ['q' => 'Apakah data transaksi dan stok aman?', 'a' => 'Setiap transaksi diproses secara atomik dengan penguncian data, jadi stok tidak akan salah hitung meskipun ada beberapa kasir yang bertransaksi bersamaan.'],
                        ];
                    @endphp

                    @foreach ($faqs as $index => $faq)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                            <button @click="open = open === {{ $index }} ? null : {{ $index }}" class="w-full flex items-center justify-between gap-4 p-5 text-left">
                                <span class="font-semibold text-slate-800">{{ $faq['q'] }}</span>
                                <i class="fas fa-chevron-down text-indigo-500 transition-transform flex-shrink-0" :class="{ 'rotate-180': open === {{ $index }} }"></i>
                            </button>
                            <div x-show="open === {{ $index }}" x-transition x-cloak class="px-5 pb-5 text-sm text-slate-600 leading-relaxed">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA / Kontak -->
        <section id="kontak" class="py-20 lg:py-28">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl px-8 py-16 lg:px-16 text-center overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

                    <div class="relative">
                        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">Siap Kelola Bisnis Anda Lebih Rapi?</h2>
                        <p class="text-indigo-100 max-w-xl mx-auto mb-10">Daftar sekarang dan rasakan kemudahan mengelola kasir, stok, dan laporan penjualan dalam satu aplikasi.</p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-100 text-indigo-700 font-semibold py-4 px-8 rounded-xl transition-all shadow-lg">
                                    <i class="fas fa-user-plus"></i>
                                    Daftar Sekarang
                                </a>
                            @endif
                            <a href="mailto:halo@possystem.id" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-4 px-8 rounded-xl transition-all border border-white/30">
                                <i class="fas fa-envelope"></i>
                                Hubungi Kami
                            </a>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-6 text-left max-w-3xl mx-auto">
                            <div class="flex items-center gap-3 text-white">
                                <div class="w-10 h-10 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="text-sm">
                                    <p class="text-indigo-100">Email</p>
                                    <p class="font-semibold">halo@possystem.id</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-white">
                                <div class="w-10 h-10 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="text-sm">
                                    <p class="text-indigo-100">Jam Layanan</p>
                                    <p class="font-semibold">Senin - Sabtu, 09.00 - 18.00</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-white">
                                <div class="w-10 h-10 bg-white/15 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="text-sm">
                                    <p class="text-indigo-100">Cocok untuk</p>
                                    <p class="font-semibold">Kafe, Restoran &amp; Retail</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-cash-register text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-lg text-white">POS System</span>
                    </a>
                    <div class="flex items-center gap-6 text-sm">
                        <a href="#keunggulan" class="hover:text-white transition-colors">Keunggulan</a>
                        <a href="#testimoni" class="hover:text-white transition-colors">Testimoni</a>
                        <a href="#faq" class="hover:text-white transition-colors">FAQ</a>
                        <a href="#kontak" class="hover:text-white transition-colors">Kontak</a>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
                    <p>&copy; {{ date('Y') }} POS System. Seluruh hak cipta dilindungi.</p>
                    <p>Dibangun dengan Laravel &amp; Tailwind CSS</p>
                </div>
            </div>
        </footer>

    </body>
</html>
