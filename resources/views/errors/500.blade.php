<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Terjadi Kesalahan | {{ config('app.name', 'POS System') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-triangle-exclamation text-3xl"></i>
        </div>
        <p class="text-6xl font-extrabold text-slate-800 mb-2">500</p>
        <h1 class="text-xl font-bold text-slate-800 mb-2">Terjadi Kesalahan Server</h1>
        <p class="text-slate-500 mb-8">Maaf, terjadi kesalahan pada sistem kami. Silakan coba lagi beberapa saat lagi.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
