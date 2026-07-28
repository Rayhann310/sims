<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= $data['judul']; ?></h1>
            <p class="text-sm text-slate-500 mt-1">Buat berita atau artikel sekolah baru untuk dipublikasikan.</p>
        </div>
        <a href="<?= BASEURL; ?>/artikel" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php Flasher::flash(); ?>

    <form action="<?= BASEURL; ?>/artikel/simpan" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Main Editor (Judul, Ringkasan, Isi) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Artikel / Berita <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Tuliskan judul artikel yang menarik..." class="w-full px-4 py-2.5 text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Ringkasan Singkat (Pengantar)</label>
                    <textarea name="ringkasan" rows="2" placeholder="Ringkasan singkat artikel (opsional, jika dikosongkan akan diambil dari awal isi artikel)..." class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-slate-700">Isi Konten Artikel <span class="text-red-500">*</span></label>
                        <label class="cursor-pointer text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium px-2.5 py-1 rounded-md border border-emerald-200 flex items-center gap-1">
                            <i class="fas fa-image"></i> Sisipkan Gambar ke Isi
                            <input type="file" id="inlineImageInput" accept="image/*" class="hidden" onchange="uploadInlineImage(this)">
                        </label>
                    </div>

                    <!-- Editor Toolbar Helpers -->
                    <div class="flex flex-wrap items-center gap-1 bg-slate-50 p-2 rounded-t-lg border border-slate-300 border-b-0 text-xs">
                        <button type="button" onclick="formatText('bold')" class="px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 font-bold" title="Tebal">B</button>
                        <button type="button" onclick="formatText('italic')" class="px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 italic" title="Miring">I</button>
                        <button type="button" onclick="formatText('h2')" class="px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 font-bold" title="Sub-Judul H2">H2</button>
                        <button type="button" onclick="formatText('h3')" class="px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 font-bold" title="Sub-Judul H3">H3</button>
                        <button type="button" onclick="formatText('quote')" class="px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100" title="Kutipan"><i class="fas fa-quote-right"></i></button>
                        <button type="button" onclick="formatText('ul')" class="px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100" title="Daftar List"><i class="fas fa-list-ul"></i></button>
                    </div>

                    <textarea id="editorIsi" name="isi" rows="16" required placeholder="Tuliskan isi artikel lengkap di sini... (Mendukung format HTML dan penyisipan gambar)" class="w-full p-4 text-sm font-mono border border-slate-300 rounded-b-lg focus:ring-emerald-500 focus:border-emerald-500 leading-relaxed"></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column: Metadata & Settings -->
        <div class="space-y-6">
            <!-- Publish Settings Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-emerald-600"></i> Pengaturan Publikasi
                </h3>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status Publikasi</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="Dipublikasi">Dipublikasi Langsung</option>
                        <option value="Draft">Simpan Sebagai Draft</option>
                    </select>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 border-slate-300">
                        <div>
                            <span class="text-sm font-semibold text-slate-800 block">Jadikan Berita Unggulan</span>
                            <span class="text-xs text-slate-500 block">Akan ditampilkan di headline utama / slider depan.</span>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Artikel <span class="text-red-500">*</span></label>
                    <select name="kategori_id" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($data['kategori_list'] as $kat): ?>
                            <option value="<?= $kat['id']; ?>"><?= htmlspecialchars($kat['nama_kategori']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tag (Pilih beberapa)</label>
                    <div class="max-h-36 overflow-y-auto p-3 border border-slate-200 rounded-lg space-y-2 bg-slate-50">
                        <?php if(empty($data['tag_list'])): ?>
                            <p class="text-xs text-slate-400 italic">Belum ada tag tersedia.</p>
                        <?php else: ?>
                            <?php foreach($data['tag_list'] as $tag): ?>
                                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer hover:text-emerald-700">
                                    <input type="checkbox" name="tags[]" value="<?= $tag['id']; ?>" class="w-4 h-4 text-emerald-600 rounded border-slate-300">
                                    <span>#<?= htmlspecialchars($tag['nama_tag']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Cover Image Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-image text-emerald-600"></i> Gambar Sampul (Header)
                </h3>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Unggah Berkas Gambar</label>
                    <input type="file" name="gambar_sampul" accept="image/*" onchange="previewCoverImage(this)" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <div class="text-center">
                    <span class="text-xs text-slate-400 font-medium">atau pergunakan URL Gambar luar</span>
                </div>

                <div>
                    <input type="url" name="gambar_url" placeholder="https://domain.com/gambar.jpg" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Preview Area -->
                <div id="coverPreviewContainer" class="hidden">
                    <p class="text-xs font-semibold text-slate-500 mb-1">Pratinjau Sampul:</p>
                    <img id="coverPreview" src="#" class="w-full h-40 object-cover rounded-lg border border-slate-200">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Publikasikan Artikel
                </button>
            </div>
        </div>

    </form>
</div>

<script>
function previewCoverImage(input) {
    const container = document.getElementById('coverPreviewContainer');
    const img = document.getElementById('coverPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            container.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function formatText(command) {
    const textarea = document.getElementById('editorIsi');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    let replacement = '';
    switch(command) {
        case 'bold':
            replacement = `<strong>${selectedText || 'Teks Tebal'}</strong>`;
            break;
        case 'italic':
            replacement = `<em>${selectedText || 'Teks Miring'}</em>`;
            break;
        case 'h2':
            replacement = `\n<h2>${selectedText || 'Sub-Judul H2'}</h2>\n`;
            break;
        case 'h3':
            replacement = `\n<h3>${selectedText || 'Sub-Judul H3'}</h3>\n`;
            break;
        case 'quote':
            replacement = `\n<blockquote>${selectedText || 'Kutipan menarik'}</blockquote>\n`;
            break;
        case 'ul':
            replacement = `\n<ul>\n  <li>${selectedText || 'Poin list'}</li>\n</ul>\n`;
            break;
    }

    textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
    textarea.focus();
}

function uploadInlineImage(input) {
    if (!input.files || !input.files[0]) return;

    const formData = new FormData();
    formData.append('file', input.files[0]);

    fetch('<?= BASEURL; ?>/artikel/uploadImage', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            const textarea = document.getElementById('editorIsi');
            const imgTag = `\n<img src="${data.url}" alt="Gambar Artikel" class="w-full rounded-xl my-4 shadow-sm">\n`;
            const start = textarea.selectionStart;
            textarea.value = textarea.value.substring(0, start) + imgTag + textarea.value.substring(start);
            alert('Gambar berhasil disisipkan!');
        } else {
            alert('Gagal upload gambar: ' + (data.pesan || 'Error'));
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan koneksi saat upload gambar.');
    });
}
</script>
