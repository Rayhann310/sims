<div class="p-6">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Kelola berita, pengumuman, dan artikel sekolah dengan cepat dan teratur.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="<?= BASEURL; ?>/artikel/kategori" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-folder text-emerald-600"></i> Kategori
            </a>
            <a href="<?= BASEURL; ?>/artikel/tag" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-tags text-blue-600"></i> Tag
            </a>
            <a href="<?= BASEURL; ?>/artikel/tambah" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fas fa-plus"></i> Tulis Artikel Baru
            </a>
        </div>
    </div>

    <?php Flasher::flash(); ?>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-newspaper text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Artikel</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['total']; ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Dipublikasi</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['dipublikasi']; ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fas fa-file-alt text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Draft</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['draft']; ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <i class="fas fa-star text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Berita Unggulan</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $data['stats']['featured']; ?></h3>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="<?= BASEURL; ?>/artikel" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($data['filter']['search']); ?>" placeholder="Cari judul, ringkasan, atau isi..." class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div>
                <select name="kategori_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Kategori</option>
                    <?php foreach($data['kategori_list'] as $kat): ?>
                        <option value="<?= $kat['id']; ?>" <?= $data['filter']['kategori_id'] == $kat['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <select name="status" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="Dipublikasi" <?= $data['filter']['status'] == 'Dipublikasi' ? 'selected' : ''; ?>>Dipublikasi</option>
                    <option value="Draft" <?= $data['filter']['status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
                <a href="<?= BASEURL; ?>/artikel" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-center w-12">No</th>
                        <th class="px-4 py-3 w-20 text-center">Sampul</th>
                        <th class="px-4 py-3">Judul & Penulis</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Tag</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Unggulan</th>
                        <th class="px-4 py-3 text-right">Tanggal</th>
                        <th class="px-4 py-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($data['artikels'])): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-newspaper text-4xl mb-3 block text-slate-300"></i>
                                Belum ada artikel atau berita yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($data['artikels'] as $item): ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-4 py-3 text-center font-medium text-slate-500"><?= $no++; ?></td>
                                <td class="px-4 py-3 text-center">
                                    <?php if (!empty($item['gambar_sampul'])): ?>
                                        <img src="<?= htmlspecialchars($item['gambar_sampul']); ?>" class="w-14 h-10 object-cover rounded-lg border border-slate-200 shadow-xs mx-auto">
                                    <?php else: ?>
                                        <div class="w-14 h-10 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 mx-auto">
                                            <i class="fas fa-image text-xs"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-800 line-clamp-1 hover:text-emerald-700">
                                        <?= htmlspecialchars($item['judul']); ?>
                                    </div>
                                    <div class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                                        <span><i class="fas fa-user text-[10px]"></i> <?= htmlspecialchars($item['nama_penulis'] ?? 'Admin'); ?></span>
                                        <span>•</span>
                                        <span><i class="fas fa-eye text-[10px]"></i> <?= number_format($item['views']); ?> pembaca</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <?php if (!empty($item['nama_kategori'])): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <?= htmlspecialchars($item['nama_kategori']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400 italic">Tanpa Kategori</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        <?php if (!empty($item['tags'])): ?>
                                            <?php foreach(array_slice($item['tags'], 0, 3) as $tag): ?>
                                                <span class="inline-block px-2 py-0.5 text-[11px] rounded bg-slate-100 text-slate-600">
                                                    #<?= htmlspecialchars($tag['nama_tag']); ?>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if(count($item['tags']) > 3): ?>
                                                <span class="text-[10px] text-slate-400 self-center">+<?= count($item['tags']) - 3 ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <?php if ($item['status'] === 'Dipublikasi'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            Dipublikasi
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                            Draft
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <a href="<?= BASEURL; ?>/artikel/toggleFeatured/<?= $item['id']; ?>" class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-lg border transition-colors <?= $item['is_featured'] ? 'bg-amber-50 text-amber-700 border-amber-300 hover:bg-amber-100 font-bold' : 'bg-slate-50 text-slate-400 border-slate-200 hover:bg-slate-100' ?>" title="Klik untuk ubah status Berita Unggulan">
                                        <i class="fas fa-star <?= $item['is_featured'] ? 'text-amber-500' : 'text-slate-300' ?>"></i>
                                        <span><?= $item['is_featured'] ? 'Ya' : 'Tidak' ?></span>
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($item['created_at'])); ?>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="<?= BASEURL; ?>/artikel/edit/<?= $item['id']; ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Artikel">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <a href="<?= BASEURL; ?>/artikel/hapus/<?= $item['id']; ?>" onclick="return confirm('Yakin ingin menghapus artikel ini?');" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Artikel">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
