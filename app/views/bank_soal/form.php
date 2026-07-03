<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Tambah soal baru ke dalam bank soal ujian CBT.</p>
        </div>
        <a href="<?= BASEURL; ?>/BankSoal" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            <h3 class="text-lg font-bold text-slate-900">Form Input Soal</h3>
        </div>
        
        <form action="<?= BASEURL; ?>/BankSoal/simpan" method="POST" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Soal</label>
                    <select name="tipe_soal" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="PG">Pilihan Ganda (PG)</option>
                        <option value="PG_KOMPLEKS">PG Kompleks (Banyak Jawaban Benar)</option>
                        <option value="ESSAY">Esai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat Kesulitan</label>
                    <select name="tingkat_kesulitan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="Mudah">Mudah</option>
                        <option value="Sedang" selected>Sedang</option>
                        <option value="Sulit">Sulit</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Pertanyaan (Mendukung Teks Biasa & Editor Nanti)</label>
                <textarea name="pertanyaan" rows="5" required placeholder="Ketikkan pertanyaan disini..." 
                          class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-y"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Opsi A</label>
                        <input type="text" name="opsi_a" placeholder="Jawaban A" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Opsi B</label>
                        <input type="text" name="opsi_b" placeholder="Jawaban B" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Opsi C</label>
                        <input type="text" name="opsi_c" placeholder="Jawaban C" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Opsi D</label>
                        <input type="text" name="opsi_d" placeholder="Jawaban D" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Opsi E (Opsional)</label>
                        <input type="text" name="opsi_e" placeholder="Jawaban E" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1 text-indigo-700">Kunci Jawaban (A/B/C/D/E)</label>
                        <input type="text" name="kunci_jawaban" placeholder="Contoh: A" class="w-full px-4 py-2 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-indigo-50">
                    </div>
                </div>
            </div>
            
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="<?= BASEURL; ?>/BankSoal" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">Simpan Soal</button>
            </div>
        </form>
    </div>
</div>
