<?php
$artikel = $data['artikel'];
$terkait = $data['artikel_terkait'] ?? [];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Layout: Article + Sidebar -->
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- ============================== -->
        <!-- MAIN CONTENT                   -->
        <!-- ============================== -->
        <article class="flex-1 min-w-0">

            <!-- Breadcrumb — truncate on mobile -->
            <nav class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500 mb-5 overflow-hidden">
                <a href="<?= BASEURL; ?>/" class="hover:text-emerald-700 transition-colors shrink-0">Beranda</a>
                <i class="fas fa-chevron-right text-[9px] shrink-0"></i>
                <a href="<?= BASEURL; ?>/berita" class="hover:text-emerald-700 transition-colors shrink-0">Berita</a>
                <?php if (!empty($artikel['nama_kategori'])): ?>
                <i class="fas fa-chevron-right text-[9px] shrink-0"></i>
                <a href="<?= BASEURL; ?>/berita?kategori_id=<?= $artikel['kategori_id']; ?>"
                   class="hover:text-emerald-700 transition-colors shrink-0 hidden sm:inline">
                    <?= htmlspecialchars($artikel['nama_kategori']); ?>
                </a>
                <i class="fas fa-chevron-right text-[9px] shrink-0 hidden sm:inline"></i>
                <?php endif; ?>
                <span class="text-slate-700 truncate min-w-0"><?= htmlspecialchars($artikel['judul']); ?></span>
            </nav>

            <!-- Badges -->
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <?php if (!empty($artikel['nama_kategori'])): ?>
                <a href="<?= BASEURL; ?>/berita?kategori_id=<?= $artikel['kategori_id']; ?>"
                   class="inline-block bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-emerald-200 hover:bg-emerald-100 transition-colors">
                    <?= htmlspecialchars($artikel['nama_kategori']); ?>
                </a>
                <?php endif; ?>
                <?php if ($artikel['is_featured']): ?>
                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-bold px-3 py-1 rounded-full border border-amber-200">
                    <i class="fas fa-star text-amber-500 text-[10px]"></i> Unggulan
                </span>
                <?php endif; ?>
            </div>

            <!-- Title -->
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black text-slate-900 leading-tight mb-4">
                <?= htmlspecialchars($artikel['judul']); ?>
            </h1>

            <!-- Meta — wraps cleanly on mobile -->
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs sm:text-sm text-slate-500 mb-5 pb-5 border-b border-slate-200">
                <span class="flex items-center gap-2">
                    <span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                        <?= strtoupper(substr($artikel['nama_penulis'] ?? 'A', 0, 1)); ?>
                    </span>
                    <span class="font-medium text-slate-700"><?= htmlspecialchars($artikel['nama_penulis'] ?? 'Tim Redaksi'); ?></span>
                </span>
                <span class="flex items-center gap-1">
                    <i class="far fa-calendar text-emerald-400"></i>
                    <?= date('d M Y', strtotime($artikel['created_at'])); ?>
                </span>
                <span class="flex items-center gap-1">
                    <i class="fas fa-eye text-emerald-400"></i>
                    <?= number_format($artikel['views']); ?> dibaca
                </span>
            </div>

            <!-- Cover Image — capped height on mobile -->
            <?php if (!empty($artikel['gambar_sampul'])): ?>
            <figure class="mb-6 rounded-xl sm:rounded-2xl overflow-hidden shadow-md">
                <img src="<?= htmlspecialchars($artikel['gambar_sampul']); ?>"
                     alt="<?= htmlspecialchars($artikel['judul']); ?>"
                     class="w-full max-h-64 sm:max-h-96 object-cover"
                     loading="lazy">
            </figure>
            <?php endif; ?>

            <!-- Lead / Ringkasan -->
            <?php if (!empty($artikel['ringkasan'])): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl px-4 py-3 sm:px-5 sm:py-4 mb-6">
                <p class="text-slate-700 text-sm sm:text-base font-medium leading-relaxed italic">
                    <?= htmlspecialchars($artikel['ringkasan']); ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Article Body -->
            <div class="article-body mb-8" style="line-height:1.9; font-size:1rem; color:#374151;">
                <?= $artikel['isi']; ?>
            </div>

            <!-- Tags -->
            <?php if (!empty($artikel['tags'])): ?>
            <div class="flex flex-wrap gap-2 pt-5 border-t border-slate-200 mb-5">
                <span class="text-xs font-semibold text-slate-500 self-center">Tag:</span>
                <?php foreach($artikel['tags'] as $tag): ?>
                <a href="<?= BASEURL; ?>/berita?tag_id=<?= $tag['id']; ?>"
                   class="inline-block px-3 py-1 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 text-xs font-medium rounded-full border border-slate-200 hover:border-emerald-300 transition-colors">
                    #<?= htmlspecialchars($tag['nama_tag']); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Bottom bar: Back + Share -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-slate-100">
                <a href="<?= BASEURL; ?>/berita"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-700 transition-colors group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Berita
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-medium">Bagikan:</span>
                    <?php
                    $currentUrl = urlencode(BASEURL . '/berita/' . $artikel['slug']);
                    $titleEnc   = urlencode($artikel['judul']);
                    ?>
                    <a href="https://wa.me/?text=<?= $titleEnc; ?>%20<?= $currentUrl; ?>" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-full bg-green-100 text-green-700 hover:bg-green-200 active:bg-green-300 flex items-center justify-center transition-colors text-base">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $currentUrl; ?>" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 active:bg-blue-300 flex items-center justify-center transition-colors text-sm">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= $titleEnc; ?>&url=<?= $currentUrl; ?>" target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-full bg-sky-100 text-sky-700 hover:bg-sky-200 active:bg-sky-300 flex items-center justify-center transition-colors text-sm">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                </div>
            </div>
        </article>

        <!-- ============================== -->
        <!-- SIDEBAR                        -->
        <!-- ============================== -->
        <aside class="w-full lg:w-72 xl:w-80 shrink-0 space-y-5">

            <!-- Artikel Terkait -->
            <?php if (!empty($terkait)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-emerald-700 to-emerald-600 flex items-center gap-2">
                    <i class="fas fa-link text-white text-xs"></i>
                    <h3 class="text-white font-bold text-sm">Artikel Terkait</h3>
                </div>

                <!-- On mobile: horizontal scroll; on lg: normal list -->
                <div class="lg:divide-y lg:divide-slate-100 flex lg:flex-col gap-3 overflow-x-auto lg:overflow-visible p-3 lg:p-0 scrollbar-hide">
                    <?php foreach($terkait as $rel): ?>
                    <a href="<?= BASEURL; ?>/berita/<?= htmlspecialchars($rel['slug']); ?>"
                       class="group flex-shrink-0 w-52 lg:w-auto lg:flex-shrink lg:flex gap-3 p-2 lg:p-4 hover:bg-slate-50 active:bg-slate-100 transition-colors rounded-xl lg:rounded-none">
                        <!-- Thumbnail -->
                        <div class="w-full h-28 lg:w-16 lg:h-14 rounded-xl overflow-hidden bg-slate-100 mb-2 lg:mb-0 lg:shrink-0">
                            <?php if (!empty($rel['gambar_sampul'])): ?>
                                <img src="<?= htmlspecialchars($rel['gambar_sampul']); ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy" alt="">
                            <?php else: ?>
                                <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-emerald-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <?php if (!empty($rel['nama_kategori'])): ?>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-600"><?= htmlspecialchars($rel['nama_kategori']); ?></span>
                            <?php endif; ?>
                            <p class="text-xs font-semibold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug line-clamp-2 mt-0.5">
                                <?= htmlspecialchars($rel['judul']); ?>
                            </p>
                            <p class="text-[10px] text-slate-400 mt-1"><?= date('d M Y', strtotime($rel['created_at'])); ?></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <div class="px-4 py-3 bg-slate-50 border-t border-slate-100">
                    <a href="<?= BASEURL; ?>/berita" class="text-xs text-emerald-700 font-semibold hover:text-emerald-900 flex items-center gap-1 group">
                        Lihat semua artikel
                        <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Info Box Sekolah -->
            <div class="bg-gradient-to-br from-emerald-700 to-emerald-900 rounded-2xl p-5 text-white">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-black text-base shrink-0">NW</div>
                    <div>
                        <p class="font-bold text-sm leading-tight">SMA Nahdlatul Wathan</p>
                        <p class="text-emerald-200 text-xs">Jakarta Timur</p>
                    </div>
                </div>
                <p class="text-emerald-100 text-xs leading-relaxed mb-4">
                    Mencetak generasi unggul berkarakter Islami, berwawasan global, dan berprestasi.
                </p>
                <a href="<?= BASEURL; ?>/spmb"
                   class="block w-full text-center bg-amber-400 hover:bg-amber-500 active:bg-amber-600 text-[#004d33] font-bold text-sm py-2.5 rounded-xl transition-colors">
                    Daftar PPDB Sekarang
                </a>
            </div>

        </aside>
    </div>
</div>

<!-- Article body & prose styles — mobile-safe -->
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

/* Prose / article body */
.article-body { word-break: break-word; overflow-wrap: break-word; }
.article-body h2 { font-size: 1.35rem; font-weight: 900; color: #0f172a; margin: 1.75rem 0 0.6rem; }
.article-body h3 { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 1.25rem 0 0.5rem; }
.article-body p  { margin-bottom: 1rem; }
.article-body ul, .article-body ol { padding-left: 1.25rem; margin-bottom: 1rem; }
.article-body li { margin-bottom: 0.35rem; }
.article-body blockquote {
    border-left: 4px solid #059669;
    background: #f0fdf4;
    padding: 0.75rem 1rem;
    border-radius: 0 0.75rem 0.75rem 0;
    font-style: italic;
    color: #065f46;
    margin: 1.25rem 0;
}
.article-body img {
    border-radius: 0.75rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
}
.article-body a { color: #059669; text-decoration: underline; }
.article-body strong { font-weight: 700; color: #0f172a; }
.article-body table { width: 100%; border-collapse: collapse; font-size: 0.875rem; margin: 1rem 0; }
.article-body table th, .article-body table td { padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; }
.article-body table th { background: #f8fafc; font-weight: 700; }

@media (max-width: 640px) {
    .article-body h2 { font-size: 1.2rem; }
    .article-body h3 { font-size: 1rem; }
    .article-body { font-size: 0.9375rem; }
    /* Prevent wide tables from breaking layout */
    .article-body table { display: block; overflow-x: auto; }
}
</style>
