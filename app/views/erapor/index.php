<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Sistem E-Rapor</h2>
                <p class="text-slate-500 mt-1">Kelola nilai rapor, ekstrakurikuler, dan cetak rapor siswa.</p>
            </div>
        </div>
    </div>

    <!-- Menu Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if(hasMenuAccess('erapor_input_nilai') || $_SESSION['user']['role'] === 'guru' || $_SESSION['user']['role'] === 'admin'): ?>
        <!-- Card: Input Nilai -->
        <a href="<?= BASEURL; ?>/erapor/inputNilai" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:border-emerald-500 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-edit text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Input Nilai Rapor</h3>
            <p class="text-sm text-slate-500">Masukkan nilai akademik dan sikap siswa untuk setiap mata pelajaran yang Anda ampu.</p>
        </a>
        <?php endif; ?>

        <?php if(hasMenuAccess('erapor_ekskul') || $_SESSION['user']['role'] === 'admin'): ?>
        <!-- Card: Ekstrakurikuler -->
        <a href="<?= BASEURL; ?>/erapor/ekskul" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:blue-500 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-running text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Ekstrakurikuler</h3>
            <p class="text-sm text-slate-500">Kelola dan input nilai ekstrakurikuler siswa untuk semester ini.</p>
        </a>
        <?php endif; ?>

        <!-- Card: Absensi Semester -->
        <a href="<?= BASEURL; ?>/erapor/absensi" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:amber-500 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Absensi Rapor</h3>
            <p class="text-sm text-slate-500">Rekap kehadiran (Sakit, Izin, Tanpa Keterangan) per semester.</p>
        </a>

        <!-- Card: Catatan Wali Kelas -->
        <a href="<?= BASEURL; ?>/erapor/catatan" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:purple-500 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-comment-dots text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Catatan Wali Kelas</h3>
            <p class="text-sm text-slate-500">Berikan catatan khusus untuk perkembangan belajar siswa.</p>
        </a>

        <!-- Card: Rekap Nilai Kelas -->
        <a href="<?= BASEURL; ?>/erapor/rekapNilai" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:border-cyan-500 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-table text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Rekap Nilai Kelas</h3>
            <p class="text-sm text-slate-500">Lihat rekapitulasi seluruh nilai siswa di kelas Anda (Khusus Wali Kelas).</p>
        </a>

        <?php if(hasMenuAccess('erapor_cetak') || $_SESSION['user']['role'] === 'admin'): ?>
        <!-- Card: Cetak Rapor -->
        <a href="<?= BASEURL; ?>/erapor/cetak" class="group bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:border-rose-500 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="fas fa-print text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Cetak Rapor</h3>
            <p class="text-sm text-slate-500">Cetak dokumen rapor lengkap dalam bentuk PDF.</p>
        </a>
        <?php endif; ?>
    </div>
</div>
