<?php
$artikel = $data['artikel'];
$terkait = $data['artikel_terkait'] ?? [];
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-10">

        <!-- =============================== -->
        <!-- MAIN CONTENT: Artikel           -->
        <!-- =============================== -->
        <article class="flex-1 min-w-0">

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
                <a href="<?= BASEURL; ?>/" class="hover:text-emerald-700 transition-colors">Beranda</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?= BASEURL; ?>/berita" class="hover:text-emerald-700 transition-colors">Berita</a>
                <?php if (!empty($artikel['nama_kategori'])): ?>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?= BASEURL; ?>/berita?kategori_id=<?= $artikel['kategori_id']; ?>" class="hover:text-emerald-700 transition-colors">
                    <?= htmlspecialchars($artikel['nama_kategori']); ?>
                </a>
                <?php endif; ?>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-700 line-clamp-1 max-w-xs"><?= htmlspecialchars($artikel['judul']); ?></span>
            </nav>

            <!-- Category & Featured badge -->
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <?php if (!empty($artikel['nama_kategori'])): ?>
                <a href="<?= BASEURL; ?>/berita?kategori_id=<?= $artikel['kategori_id']; ?>"
                   class="inline-block bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full border border-emerald-200 hover:bg-emerald-100 transition-colors">
                    <?= htmlspecialchars($artikel['nama_kategori']); ?>
                </a>
                <?php endif; ?>
                <?php if ($artikel['is_featured']): ?>
                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-bold px-3 py-1 rounded-full border border-amber-200">
                    <i class="fas fa-star text-amber-500 text-[10px]"></i> Berita Unggulan
                </span>
                <?php endif; ?>
            </div>

            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-4">
                <?= htmlspecialchars($artikel['judul']); ?>
            </h1>

            <!-- Meta: author, date, views -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 mb-6 pb-6 border-b border-slate-200">
                <span class="flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold">
                        <?= strtoupper(substr($artikel['nama_penulis'] ?? 'A', 0, 1)); ?>
                    </span>
                    <span class="font-medium text-slate-700"><?= htmlspecialchars($artikel['nama_penulis'] ?? 'Tim Redaksi'); ?></span>
                </span>
                <span class="flex items-center gap-1.5">
                    <i class="far fa-calendar text-emerald-400"></i>
                    <?= date('d F Y', strtotime($artikel['created_at'])); ?>
                </span>
                <?php if ($artikel['updated_at'] !== $artikel['created_at']): ?>
                <span class="flex items-center gap-1.5 text-slate-400 italic text-xs">
                    <i class="fas fa-edit"></i> Diperbarui <?= date('d M Y', strtotime($artikel['updated_at'])); ?>
                </span>
                <?php endif; ?>
                <span class="flex items-center gap-1.5">
                    <i class="fas fa-eye text-emerald-400"></i>
                    <?= number_format($artikel['views']); ?> pembaca
                </span>
            </div>

            <!-- Cover Image -->
            <?php if (!empty($artikel['gambar_sampul'])): ?>
            <figure class="mb-8 rounded-2xl overflow-hidden shadow-lg">
                <img src="<?= htmlspecialchars($artikel['gambar_sampul']); ?>"
                     alt="<?= htmlspecialchars($artikel['judul']); ?>"
                     class="w-full max-h-[500px] object-cover">
                <figcaption class="text-xs text-slate-400 text-center mt-2 italic">
                    <?= htmlspecialchars($artikel['judul']); ?>
                </figcaption>
            </figure>
            <?php endif; ?>

            <!-- Ringkasan / Lead -->
            <?php if (!empty($artikel['ringkasan'])): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl px-5 py-4 mb-8">
                <p class="text-slate-700 font-medium leading-relaxed italic">
                    <?= htmlspecialchars($artikel['ringkasan']); ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Article Body -->
            <div class="prose prose-slate prose-lg max-w-none
                        prose-headings:font-black prose-headings:text-slate-900
                        prose-a:text-emerald-600 prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-xl prose-img:shadow-md
                        prose-blockquote:border-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:py-1 prose-blockquote:rounded-r-xl
                        mb-8"
                 style="line-height:1.9;">
                <?= $artikel['isi']; ?>
            </div>

            <!-- Tags -->
            <?php if (!empty($artikel['tags'])): ?>
            <div class="flex flex-wrap gap-2 pt-6 border-t border-slate-200 mb-8">
                <span class="text-sm font-semibold text-slate-500 mr-1">Tag:</span>
                <?php foreach($artikel['tags'] as $tag): ?>
                <a href="<?= BASEURL; ?>/berita?tag_id=<?= $tag['id']; ?>"
                   class="inline-block px-3 py-1 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-600 text-xs font-medium rounded-full border border-slate-200 hover:border-emerald-300 transition-colors">
                    #<?= htmlspecialchars($tag['nama_tag']); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Share / Back buttons -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <a href="<?= BASEURL; ?>/berita"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-emerald-700 transition-colors group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Daftar Berita
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-medium">Bagikan:</span>
                    <?php
                    $currentUrl = urlencode(BASEURL . '/berita/' . $artikel['slug']);
                    $titleEnc   = urlencode($artikel['judul']);
                    ?>
                    <a href="https://wa.me/?text=<?= $titleEnc; ?>%20<?= $currentUrl; ?>" target="_blank" rel="noopener"
                       class="w-8 h-8 rounded-full bg-green-100 text-green-700 hover:bg-green-200 flex items-center justify-center transition-colors text-sm">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $currentUrl; ?>" target="_blank" rel="noopener"
                       class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 flex items-center justify-center transition-colors text-sm">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= $titleEnc; ?>&url=<?= $currentUrl; ?>" target="_blank" rel="noopener"
                       class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 hover:bg-sky-200 flex items-center justify-center transition-colors text-sm">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                </div>
            </div>
        </article>

        <!-- =============================== -->
        <!-- SIDEBAR: Artikel Terkait        -->
        <!-- =============================== -->
        <aside class="lg:w-80 shrink-0 space-y-6">

            <!-- Artikel Terkait -->
            <?php if (!empty($terkait)): ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 bg-gradient-to-r from-emerald-700 to-emerald-600 flex items-center gap-2">
                    <i class="fas fa-link text-white text-sm"></i>
                    <h3 class="text-white font-bold text-sm">Artikel Terkait</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php foreach($terkait as $rel): ?>
                    <a href="<?= BASEURL; ?>/berita/<?= htmlspecialchars($rel['slug']); ?>"
                       class="group flex gap-3 p-4 hover:bg-slate-50 transition-colors">
                        <!-- Thumbnail -->
                        <div class="w-20 h-16 rounded-xl overflow-hidden shrink-0 bg-slate-100">
                            <?php if (!empty($rel['gambar_sampul'])): ?>
                                <img src="<?= htmlspecialchars($rel['gambar_sampul']); ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="">
                            <?php else: ?>
                                <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-emerald-400 text-lg"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <?php if (!empty($rel['nama_kategori'])): ?>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-600"><?= htmlspecialchars($rel['nama_kategori']); ?></span>
                            <?php endif; ?>
                            <p class="text-sm font-semibold text-slate-800 group-hover:text-emerald-700 transition-colors leading-snug line-clamp-2 mt-0.5">
                                <?= htmlspecialchars($rel['judul']); ?>
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">
                                <?= date('d M Y', strtotime($rel['created_at'])); ?>
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-100">
                    <a href="<?= BASEURL; ?>/berita" class="text-sm text-emerald-700 font-semibold hover:text-emerald-900 flex items-center gap-1 group">
                        Lihat semua artikel
                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Info Box: Sekolah -->
            <div class="bg-gradient-to-br from-emerald-700 to-emerald-900 rounded-2xl p-5 text-white">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-black text-lg">NW</div>
                    <div>
                        <p class="font-bold text-sm leading-tight">SMA Nahdlatul Wathan</p>
                        <p class="text-emerald-200 text-xs">Jakarta</p>
                    </div>
                </div>
                <p class="text-emerald-100 text-xs leading-relaxed mb-4">
                    Mencetak generasi unggul berkarakter Islami, berwawasan global, dan berprestasi di bidang akademik maupun non-akademik.
                </p>
                <a href="<?= BASEURL; ?>/spmb" class="inline-block w-full text-center bg-amber-400 hover:bg-amber-500 text-[#004d33] font-bold text-sm py-2 rounded-xl transition-colors">
                    Daftar PPDB Sekarang
                </a>
            </div>

        </aside>
    </div>
</div>

<!-- Prose typography fallback for Tailwind CDN -->
<style>
.prose h2 { font-size: 1.5rem; font-weight: 900; color: #0f172a; margin-top: 2rem; margin-bottom: 0.75rem; }
.prose h3 { font-size: 1.2rem; font-weight: 800; color: #1e293b; margin-top: 1.5rem; margin-bottom: 0.5rem; }
.prose p  { margin-bottom: 1.1rem; color: #374151; }
.prose ul, .prose ol { padding-left: 1.5rem; margin-bottom: 1.1rem; color: #374151; }
.prose li { margin-bottom: 0.4rem; }
.prose blockquote { border-left: 4px solid #059669; background: #f0fdf4; padding: 0.75rem 1rem; border-radius: 0 0.75rem 0.75rem 0; font-style: italic; color: #065f46; margin: 1.5rem 0; }
.prose img { border-radius: 0.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.1); max-width: 100%; }
.prose a { color: #059669; }
.prose a:hover { text-decoration: underline; }
.prose strong { font-weight: 700; color: #0f172a; }
</style>
