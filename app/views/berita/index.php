<?php
$filterLabel = !empty($data['filter_label']) ? ' — ' . htmlspecialchars($data['filter_label']) : '';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <!-- Breadcrumb -->
        <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500 mb-3">
            <a href="<?= BASEURL; ?>/" class="hover:text-emerald-700 transition-colors">Beranda</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-800 font-medium">Berita & Artikel<?= $filterLabel; ?></span>
        </nav>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 leading-tight">
            <?= !empty($data['filter_label']) ? $data['filter_label'] : 'Berita & Artikel'; ?>
        </h1>
        <p class="text-slate-500 text-sm mt-2">Informasi, pengumuman, dan kegiatan terkini dari SMA Nahdlatul Wathan Jakarta.</p>
    </div>

    <!-- Filter & Search — mobile stacked, desktop row -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
        <form method="GET" action="<?= BASEURL; ?>/berita">
            <!-- Row 1: search full width -->
            <div class="relative mb-3">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($data['filter']['search']); ?>"
                       placeholder="Cari judul atau konten..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            <!-- Row 2: category + buttons -->
            <div class="flex flex-wrap gap-2">
                <select name="kategori_id" class="flex-1 min-w-0 px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-emerald-500 outline-none bg-white">
                    <option value="">Semua Kategori</option>
                    <?php foreach($data['kategori_list'] as $kat): ?>
                        <option value="<?= $kat['id']; ?>" <?= $data['filter']['kategori_id'] == $kat['id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors whitespace-nowrap">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <a href="<?= BASEURL; ?>/berita" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium rounded-xl transition-colors whitespace-nowrap">
                    Reset
                </a>
                <a href="<?= BASEURL; ?>/berita?filter=unggulan"
                   class="px-4 py-2 text-sm font-semibold rounded-xl border transition-colors flex items-center gap-1 whitespace-nowrap
                          <?= isset($_GET['filter']) && $_GET['filter'] === 'unggulan' ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-amber-50'; ?>">
                    <i class="fas fa-star text-amber-500"></i>
                    <span class="hidden xs:inline">Unggulan</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Tags cloud — horizontal scroll on mobile -->
    <?php if (!empty($data['tag_list'])): ?>
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:flex-wrap scrollbar-hide">
        <?php foreach($data['tag_list'] as $tag): ?>
        <a href="<?= BASEURL; ?>/berita?tag_id=<?= $tag['id']; ?>"
           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors whitespace-nowrap shrink-0
                  <?= isset($_GET['tag_id']) && $_GET['tag_id'] == $tag['id'] ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:border-emerald-400 hover:text-emerald-700'; ?>">
            #<?= htmlspecialchars($tag['nama_tag']); ?>
            <span class="opacity-70">(<?= $tag['total_artikel']; ?>)</span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Results -->
    <?php if (empty($data['artikels'])): ?>
    <div class="text-center py-16 text-slate-400">
        <i class="fas fa-newspaper text-5xl mb-4 block opacity-30"></i>
        <p class="text-base font-medium text-slate-500">Belum ada artikel yang sesuai.</p>
        <a href="<?= BASEURL; ?>/berita" class="mt-4 inline-block text-emerald-600 hover:underline text-sm">Tampilkan semua berita</a>
    </div>
    <?php else: ?>

    <!-- Article Grid: 1 col → 2 col → 3 col -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <?php foreach($data['artikels'] as $item): ?>
        <a href="<?= BASEURL; ?>/berita/<?= htmlspecialchars($item['slug']); ?>"
           class="group bg-white rounded-2xl border border-slate-200 shadow-sm
                  active:scale-[0.98] hover:shadow-lg hover:-translate-y-0.5
                  transition-all duration-200 overflow-hidden flex flex-col">

            <!-- Cover — taller on mobile for readability -->
            <div class="relative h-44 sm:h-48 bg-slate-100 overflow-hidden">
                <?php if (!empty($item['gambar_sampul'])): ?>
                    <img src="<?= htmlspecialchars($item['gambar_sampul']); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy"
                         alt="<?= htmlspecialchars($item['judul']); ?>">
                <?php else: ?>
                    <div class="w-full h-full bg-gradient-to-br from-emerald-700 to-emerald-500 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-4xl opacity-30"></i>
                    </div>
                <?php endif; ?>

                <?php if (!empty($item['nama_kategori'])): ?>
                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-emerald-800 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm">
                    <?= htmlspecialchars($item['nama_kategori']); ?>
                </span>
                <?php endif; ?>

                <?php if ($item['is_featured']): ?>
                <span class="absolute top-3 right-3 bg-amber-400 text-[#004d33] text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 shadow">
                    <i class="fas fa-star text-[8px]"></i> Unggulan
                </span>
                <?php endif; ?>
            </div>

            <!-- Content -->
            <div class="p-4 sm:p-5 flex flex-col flex-1">
                <h2 class="font-bold text-slate-800 leading-snug line-clamp-2 group-hover:text-emerald-700 transition-colors mb-2 text-sm sm:text-base">
                    <?= htmlspecialchars($item['judul']); ?>
                </h2>
                <?php if (!empty($item['ringkasan'])): ?>
                <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed mb-3">
                    <?= htmlspecialchars(strip_tags($item['ringkasan'])); ?>
                </p>
                <?php endif; ?>

                <?php if (!empty($item['tags'])): ?>
                <div class="flex flex-wrap gap-1 mb-3">
                    <?php foreach(array_slice($item['tags'], 0, 2) as $tag): ?>
                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">
                        #<?= htmlspecialchars($tag['nama_tag']); ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Meta: date + views — always fits on 1 line -->
                <div class="flex items-center justify-between text-[11px] text-slate-400 mt-auto pt-2.5 border-t border-slate-100">
                    <span class="flex items-center gap-1 truncate mr-2">
                        <i class="fas fa-user-pen text-emerald-400"></i>
                        <span class="truncate"><?= htmlspecialchars($item['nama_penulis'] ?? 'Redaksi'); ?></span>
                    </span>
                    <span class="flex items-center gap-2 shrink-0">
                        <span><?= date('d M Y', strtotime($item['created_at'])); ?></span>
                        <span class="flex items-center gap-0.5"><i class="fas fa-eye"></i><?= number_format($item['views']); ?></span>
                    </span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
