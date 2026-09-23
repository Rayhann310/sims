<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="soalBuilderMassal()">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Edit beberapa soal sekaligus dengan cepat.</p>
        </div>
        <a href="<?= BASEURL; ?>/BankSoal" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <form action="<?= BASEURL; ?>/BankSoal/updateMassal" method="POST" id="formBankSoal">
        <!-- Pengaturan Umum -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h3 class="text-lg font-bold text-slate-900">Mata Pelajaran (Terapkan ke Semua)</h3>
            </div>
            <div class="p-6">
                <select name="id_mapel" required class="w-full md:w-1/2 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    <?php foreach($data['mapel'] as $m): ?>
                        <option value="<?= $m['id']; ?>" <?= ($data['soal'][0]['id_mapel'] == $m['id']) ? 'selected' : '' ?>><?= htmlspecialchars($m['nama_mapel']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Daftar Soal -->
        <template x-for="(soal, index) in daftarSoal" :key="soal.id_soal">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6 relative transition-all">
                
                <input type="hidden" :name="'soal['+index+'][id_soal]'" :value="soal.id_soal">
                
                <div class="border-b border-slate-200 bg-indigo-50/50 px-6 py-4 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold" x-text="index + 1"></span>
                    <h3 class="text-lg font-bold text-indigo-900">Soal No. <span x-text="index + 1"></span></h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Tipe & Kesulitan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Soal</label>
                            <select :name="'soal['+index+'][tipe_soal]'" x-model="soal.tipe_soal" @change="initSummernoteForSoal(index)" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="PG">Pilihan Ganda (PG)</option>
                                <option value="PG_KOMPLEKS">PG Kompleks (Lebih dari 1 Jawaban)</option>
                                <option value="ESSAY">Esai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tingkat Kesulitan</label>
                            <select :name="'soal['+index+'][tingkat_kesulitan]'" x-model="soal.tingkat_kesulitan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                                <option value="Mudah">Mudah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Sulit">Sulit</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Pertanyaan -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pertanyaan</label>
                        <textarea :id="'editor_pertanyaan_' + soal.id_soal" :name="'soal['+index+'][pertanyaan]'" x-model="soal.pertanyaan" class="w-full"></textarea>
                    </div>
                    
                    <!-- Pilihan Ganda / PG Kompleks Section -->
                    <div x-show="soal.tipe_soal === 'PG' || soal.tipe_soal === 'PG_KOMPLEKS'" class="pt-4 border-t border-slate-100">
                        <label class="block text-sm font-bold text-slate-800 mb-4">Pilihan Jawaban</label>
                        
                        <div class="space-y-6">
                            <template x-for="opt in ['a', 'b', 'c', 'd', 'e']" :key="opt">
                                <div class="flex gap-4 items-start">
                                    <div class="mt-2 shrink-0 flex flex-col items-center justify-center">
                                        <!-- Radio for PG -->
                                        <input x-show="soal.tipe_soal === 'PG'" type="radio" :name="'soal['+index+'][kunci_jawaban]'" :value="opt.toUpperCase()" x-model="soal._kunci_pg" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        
                                        <!-- Checkbox for PG Kompleks -->
                                        <input x-show="soal.tipe_soal === 'PG_KOMPLEKS'" type="checkbox" :name="'soal['+index+'][kunci_jawaban][]'" :value="opt.toUpperCase()" x-model="soal._kunci_kompleks" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        
                                        <span class="text-xs font-bold mt-1 text-slate-500">KUNCI</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-block w-6 h-6 rounded bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-sm" x-text="opt.toUpperCase()"></span>
                                        </div>
                                        <textarea :id="'editor_opsi_' + opt + '_' + soal.id_soal" :name="'soal['+index+'][opsi_' + opt + ']'" x-model="soal['opsi_'+opt]" class="w-full"></textarea>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Esai Section -->
                    <div x-show="soal.tipe_soal === 'ESSAY'" class="pt-4 border-t border-slate-100">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kunci Jawaban / Kata Kunci Esai</label>
                        <textarea :name="'soal['+index+'][kunci_jawaban]'" x-model="soal._kunci_essay" rows="3" placeholder="Contoh: Klorofil, Matahari, Oksigen (Pisahkan dengan koma)" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                    </div>
                </div>
            </div>
        </template>
        
        <div class="flex justify-end items-center gap-4 bg-slate-50 p-6 rounded-xl border border-slate-200">
            <button type="button" @click="submitForm()" class="w-full sm:w-auto px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-200 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Perbarui Semua Soal
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
    function soalBuilderMassal() {
        return {
            daftarSoal: <?= json_encode($data['soal']) ?>,
            
            init() {
                // Initialize keys based on type
                this.daftarSoal.forEach(soal => {
                    if(!soal.tipe_soal) soal.tipe_soal = 'PG';
                    soal._kunci_pg = '';
                    soal._kunci_kompleks = [];
                    soal._kunci_essay = '';
                    
                    if (soal.tipe_soal === 'PG_KOMPLEKS') {
                        soal._kunci_kompleks = soal.kunci_jawaban ? soal.kunci_jawaban.split(',') : [];
                    } else if (soal.tipe_soal === 'ESSAY') {
                        soal._kunci_essay = soal.kunci_jawaban;
                    } else {
                        soal._kunci_pg = soal.kunci_jawaban;
                    }
                });
                
                this.$nextTick(() => {
                    this.daftarSoal.forEach((_, idx) => {
                        this.initSummernoteForSoal(idx);
                    });
                });
            },
            
            initSummernoteForSoal(index) {
                let soal = this.daftarSoal[index];
                
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
                            let editorId = $(this).attr('id');
                            uploadImage(files[0], editorId);
                        }
                    }
                };

                let qId = '#editor_pertanyaan_' + soal.id_soal;
                if($(qId).hasClass('summernote-initialized')) $(qId).summernote('destroy');
                
                $(qId).val(soal.pertanyaan);
                $(qId).summernote(snConfig).addClass('summernote-initialized');
                
                if (soal.tipe_soal !== 'ESSAY') {
                    snConfig.height = 80;
                    ['a','b','c','d','e'].forEach(opt => {
                        let optId = '#editor_opsi_' + opt + '_' + soal.id_soal;
                        if($(optId).hasClass('summernote-initialized')) $(optId).summernote('destroy');
                        
                        $(optId).val(soal['opsi_'+opt]);
                        $(optId).summernote(snConfig).addClass('summernote-initialized');
                    });
                }
            },
            
            submitForm() {
                this.daftarSoal.forEach(soal => {
                    $('#editor_pertanyaan_' + soal.id_soal).val($('#editor_pertanyaan_' + soal.id_soal).summernote('code'));
                    if (soal.tipe_soal !== 'ESSAY') {
                        ['a','b','c','d','e'].forEach(opt => {
                            $('#editor_opsi_' + opt + '_' + soal.id_soal).val($('#editor_opsi_' + opt + '_' + soal.id_soal).summernote('code'));
                        });
                    }
                });
                
                document.getElementById('formBankSoal').submit();
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
