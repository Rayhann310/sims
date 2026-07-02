<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#0f172a', secondary: '#059669', accent: '#10b981' }
                }
            }
        }
    </script>
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen flex bg-white overflow-hidden">

    <!-- Left Side: Branding / Text -->
    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative items-center justify-center p-12 overflow-hidden">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 left-0 w-full h-full opacity-20">
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-emerald-500 rounded-full mix-blend-screen filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute bottom-10 -right-20 w-[30rem] h-[30rem] bg-blue-500 rounded-full mix-blend-screen filter blur-[100px] opacity-40 animate-blob animation-delay-2000"></div>
        </div>
        
        <div class="relative z-10 max-w-lg text-white">
            <div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-xl bg-white/10 backdrop-blur-md border border-white/20">
                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-6">
                Sistem Informasi <br>
                <span class="text-emerald-400">Akademik & PPDB</span>
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed mb-10">
                Kelola data sekolah dengan efisien, terpusat, dan modern. SMA Nahdlatul Wathan Jakarta kini hadir dengan layanan digital yang mempermudah proses akademik dan pendaftaran siswa baru.
            </p>
            
            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=1" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=2" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=3" alt="User">
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-white">1000+ Pengguna</p>
                    <p class="text-slate-400">Guru, Siswa, dan Orang Tua</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative bg-slate-50 lg:bg-white">
        <!-- Mobile Background Decoration -->
        <div class="absolute top-0 -left-40 w-96 h-96 bg-emerald-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 lg:hidden"></div>
        
        <div class="w-full max-w-md relative z-10">
            <!-- Back Button -->
            <a href="<?= BASEURL; ?>/" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-emerald-600 transition-colors mb-10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>

            <div class="mb-10">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Selamat Datang!</h2>
                <p class="text-slate-500 mt-2">Silakan masukkan username dan password Anda untuk masuk ke sistem.</p>
            </div>

            <!-- Flash Message -->
            <?php if(isset($_SESSION['flash'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm mb-6 flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="font-medium text-sm"><?= $_SESSION['flash']; ?></p>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <form action="<?= BASEURL; ?>/login/proses" method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username / NISN</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" id="username" name="username" required 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-slate-900 placeholder-slate-400"
                               placeholder="Masukkan username Anda">
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 hover:underline">Lupa password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-slate-900 placeholder-slate-400"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-slate-200 hover:shadow-emerald-200 transition-all duration-200 flex justify-center items-center gap-2">
                        Masuk Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
                
                <!-- Registration Link for SPMB -->
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500">
                        Belum punya akun Calon Siswa? <br>
                        <a href="<?= BASEURL; ?>/spmb" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline inline-block mt-1">Daftar SPMB Sekarang</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
