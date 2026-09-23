<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="editSoal()">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Edit soal secara satuan.</p>
        </div>
        <a href="<?= BASEURL; ?>/BankSoal" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <form action="<?= BASEURL; ?>/BankSoal/update" method="POST" id="formEditSoal">
        <input type="hidden" name="id_soal" :value="soal.id_soal">
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-lg font-bold text-slate-900">Detail Soal</h3>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select name="id_mapel" x-model="soal.id_mapel" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <?php foreach($data['mapel'] as $m): ?>
                                <option value="<?= $m['id']; ?>"><?= htmlspecialchars($m['nama_mapel']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Soal</label>
                        <select name="tipe_soal" x-model="soal.tipe_soal" @change="initSummernote()" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <option value="PG">Pilihan Ganda (PG)</option>
                            <option value="PG_KOMPLEKS">PG Kompleks (Lebih dari 1 Jawaban)</option>
                            <option value="ESSAY">Esai</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat Kesulitan</label>
                        <select name="tingkat_kesulitan" x-model="soal.tingkat_kesulitan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <option value="Mudah">Mudah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Sulit">Sulit</option>
                        </select>
                    </div>
                </div>
                
                <!-- Pertanyaan -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Pertanyaan</label>
                    <textarea id="editor_pertanyaan" name="pertanyaan" class="w-full"></textarea>
                </div>
                
                <!-- Pilihan Ganda / PG Kompleks Section -->
                <div x-show="soal.tipe_soal === 'PG' || soal.tipe_soal === 'PG_KOMPLEKS'" class="pt-4 border-t border-slate-100">
                    <label class="block text-sm font-bold text-slate-800 mb-4">Pilihan Jawaban</label>
                    
                    <div class="space-y-6">
                        <template x-for="opt in ['a', 'b', 'c', 'd', 'e']" :key="opt">
                            <div class="flex gap-4 items-start">
                                <div class="mt-2 shrink-0 flex flex-col items-center justify-center">
                                    <!-- Radio for PG -->
                                    <input x-show="soal.tipe_soal === 'PG'" type="radio" name="kunci_jawaban" :value="opt.toUpperCase()" x-model="kunciPG" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    
                                    <!-- Checkbox for PG Kompleks -->
                                    <input x-show="soal.tipe_soal === 'PG_KOMPLEKS'" type="checkbox" name="kunci_jawaban[]" :value="opt.toUpperCase()" x-model="kunciKompleks" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    
                                    <span class="text-xs font-bold mt-1 text-slate-500">KUNCI</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="inline-block w-6 h-6 rounded bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm" x-text="opt.toUpperCase()"></span>
                                    </div>
                                    <textarea :id="'editor_opsi_' + opt" :name="'opsi_' + opt" class="w-full"></textarea>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Esai Section -->
                <div x-show="soal.tipe_soal === 'ESSAY'" class="pt-4 border-t border-slate-100">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kunci Jawaban / Kata Kunci Esai</label>
                    <textarea name="kunci_jawaban_essay" x-model="kunciEssay" rows="3" placeholder="Contoh: Klorofil, Matahari, Oksigen" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                    
                    <!-- Hidden input to map correct name based on type -->
                    <input type="hidden" name="kunci_jawaban" :value="kunciEssay" :disabled="soal.tipe_soal !== 'ESSAY'">
                </div>
            </div>
        </div>
        
        <div class="flex justify-end items-center gap-4 bg-slate-50 p-6 rounded-xl border border-slate-200">
            <button type="button" @click="submitForm()" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-200 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Perbarui Soal
            </button>
        </div>
    </form>
</div>

<!-- Load jQuery and Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    function editSoal() {
        return {
            soal: <?= json_encode($data['soal']) ?>,
            kunciPG: '',
            kunciKompleks: [],
            kunciEssay: '',
            
            init() {
                // Initialize keys based on type
                if (this.soal.tipe_soal === 'PG_KOMPLEKS') {
                    this.kunciKompleks = this.soal.kunci_jawaban ? this.soal.kunci_jawaban.split(',') : [];
                } else if (this.soal.tipe_soal === 'ESSAY') {
                    this.kunciEssay = this.soal.kunci_jawaban;
                } else {
                    this.kunciPG = this.soal.kunci_jawaban;
                    if(!this.soal.tipe_soal) this.soal.tipe_soal = 'PG';
                }
                
                this.$nextTick(() => {
                    this.initSummernote();
                });
            },
            
            initSummernote() {
                let snConfig = {
                    height: 120,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['picture', 'link', 'table', 'math']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onImageUpload: function(files) {
                            uploadImage(files[0], $(this).attr('id'));
                        }
                    }
                };

                // Destroy old if exists
                if ($('#editor_pertanyaan').hasClass('summernote-initialized')) $('#editor_pertanyaan').summernote('destroy');
                
                $('#editor_pertanyaan').val(this.soal.pertanyaan);
                $('#editor_pertanyaan').summernote(snConfig).addClass('summernote-initialized');
                
                if (this.soal.tipe_soal !== 'ESSAY') {
                    snConfig.height = 80;
                    ['a','b','c','d','e'].forEach(opt => {
                        let optId = '#editor_opsi_' + opt;
                        if ($(optId).hasClass('summernote-initialized')) $(optId).summernote('destroy');
                        
                        $(optId).val(this.soal['opsi_'+opt]);
                        $(optId).summernote(snConfig).addClass('summernote-initialized');
                    });
                }
            },
            
            submitForm() {
                // Sync UI to textareas before submit
                $('#editor_pertanyaan').val($('#editor_pertanyaan').summernote('code'));
                if (this.soal.tipe_soal !== 'ESSAY') {
                    ['a','b','c','d','e'].forEach(opt => {
                        $('#editor_opsi_' + opt).val($('#editor_opsi_' + opt).summernote('code'));
                    });
                }
                document.getElementById('formEditSoal').submit();
            }
        }
    }
    
    function uploadImage(file, editorId) {
        let data = new FormData();
        data.append("file", file);
        $.ajax({
            url: "<?= BASEURL ?>/BankSoal/uploadImageApi",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "POST",
            success: function(url) {
                $('#' + editorId).summernote("insertImage", url);
            },
            error: function(data) {
                alert("Gagal mengunggah gambar.");
            }
        });
    }
</script>
<style>
    .note-editor .dropdown-toggle::after { all: unset; }
    .note-editor .note-dropdown-menu { box-sizing: content-box; }
    .note-editor .note-modal-footer { height: auto; padding: 15px; }
    .note-editor .note-modal-title { font-weight: bold; }
</style>
