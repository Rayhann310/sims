<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'inbox', modalTulis: false, viewMessage: null }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= $data['judul']; ?></h1>
            <p class="text-slate-500 mt-2">Kirim dan terima pesan antar pengguna sistem.</p>
        </div>
        <button @click="modalTulis = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            Tulis Pesan
        </button>
    </div>

    <!-- Flash Message -->
    <div class="mb-6">
        <?php Flasher::flash(); ?>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar Navigation -->
        <div class="w-full lg:w-64 shrink-0">
            <nav class="space-y-1">
                <button @click="activeTab = 'inbox'; viewMessage = null; window.history.replaceState(null, '', '?tab=inbox')" 
                        :class="{'bg-indigo-50 text-indigo-700': activeTab === 'inbox', 'text-slate-700 hover:bg-slate-50': activeTab !== 'inbox'}" 
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg w-full transition-colors">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    Kotak Masuk
                    <?php 
                        $unread = 0;
                        foreach($data['inbox'] as $i) if($i['is_read'] == 0) $unread++;
                        if($unread > 0): 
                    ?>
                        <span class="ml-auto bg-indigo-600 text-white py-0.5 px-2 rounded-full text-xs font-bold"><?= $unread; ?></span>
                    <?php endif; ?>
                </button>
                <button @click="activeTab = 'sent'; viewMessage = null; window.history.replaceState(null, '', '?tab=sent')" 
                        :class="{'bg-indigo-50 text-indigo-700': activeTab === 'sent', 'text-slate-700 hover:bg-slate-50': activeTab !== 'sent'}" 
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg w-full transition-colors">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Pesan Terkirim
                </button>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- List View: Inbox -->
            <div x-show="activeTab === 'inbox' && !viewMessage" class="divide-y divide-slate-100">
                <?php if(empty($data['inbox'])): ?>
                    <div class="py-12 text-center text-slate-500">Kotak masuk Anda kosong.</div>
                <?php else: ?>
                    <?php foreach($data['inbox'] as $i): ?>
                        <div @click="viewMessage = <?= htmlspecialchars(json_encode([
                            'id' => $i['id'],
                            'nama' => $i['nama_pengirim'],
                            'role' => $i['role_pengirim'],
                            'subjek' => $i['subjek'],
                            'isi' => nl2br($i['isi_pesan']),
                            'tanggal' => date('d M Y, H:i', strtotime($i['created_at'])),
                            'is_read' => $i['is_read']
                        ])); ?>; if(viewMessage.is_read == 0) window.location.href='<?= BASEURL; ?>/komunikasi/baca/'+viewMessage.id;" 
                             class="flex items-center px-6 py-4 cursor-pointer hover:bg-slate-50 transition-colors <?= $i['is_read'] == 0 ? 'bg-indigo-50/30' : '' ?>">
                            <div class="flex-1 min-w-0 pr-4">
                                <div class="flex items-baseline justify-between mb-1">
                                    <h4 class="text-sm font-semibold truncate <?= $i['is_read'] == 0 ? 'text-indigo-900' : 'text-slate-900' ?>"><?= $i['nama_pengirim']; ?></h4>
                                    <span class="text-xs text-slate-500 shrink-0 ml-2"><?= date('d M', strtotime($i['created_at'])); ?></span>
                                </div>
                                <p class="text-sm font-medium text-slate-900 truncate <?= $i['is_read'] == 0 ? 'font-bold' : '' ?>"><?= $i['subjek']; ?></p>
                                <p class="text-sm text-slate-500 truncate mt-0.5"><?= $i['isi_pesan']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- List View: Sent -->
            <div x-show="activeTab === 'sent' && !viewMessage" class="divide-y divide-slate-100" style="display: none;">
                <?php if(empty($data['sent'])): ?>
                    <div class="py-12 text-center text-slate-500">Belum ada pesan yang terkirim.</div>
                <?php else: ?>
                    <?php foreach($data['sent'] as $s): ?>
                        <div @click="viewMessage = <?= htmlspecialchars(json_encode([
                            'nama' => $s['nama_penerima'],
                            'role' => $s['role_penerima'],
                            'subjek' => $s['subjek'],
                            'isi' => nl2br($s['isi_pesan']),
                            'tanggal' => date('d M Y, H:i', strtotime($s['created_at'])),
                            'type' => 'sent'
                        ])); ?>" 
                             class="flex items-center px-6 py-4 cursor-pointer hover:bg-slate-50 transition-colors">
                            <div class="flex-1 min-w-0 pr-4">
                                <div class="flex items-baseline justify-between mb-1">
                                    <h4 class="text-sm font-semibold text-slate-900 truncate">Ke: <?= $s['nama_penerima']; ?></h4>
                                    <span class="text-xs text-slate-500 shrink-0 ml-2"><?= date('d M', strtotime($s['created_at'])); ?></span>
                                </div>
                                <p class="text-sm font-medium text-slate-900 truncate"><?= $s['subjek']; ?></p>
                                <p class="text-sm text-slate-500 truncate mt-0.5"><?= $s['isi_pesan']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Detail Message View -->
            <div x-show="viewMessage" style="display: none;" class="flex flex-col h-full min-h-[400px]">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                    <button @click="viewMessage = null" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </button>
                    <span class="text-xs text-slate-400 font-medium uppercase tracking-wider" x-text="activeTab === 'inbox' ? 'Kotak Masuk' : 'Pesan Terkirim'"></span>
                </div>
                
                <div class="p-6 flex-1">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6" x-text="viewMessage?.subjek"></h2>
                    
                    <div class="flex items-center mb-8 border-b border-slate-100 pb-6">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl mr-4 shrink-0" x-text="viewMessage?.nama?.charAt(0)"></div>
                        <div class="flex-1">
                            <div class="flex items-baseline justify-between">
                                <h3 class="text-base font-bold text-slate-900">
                                    <span x-show="viewMessage?.type === 'sent'">Ke: </span>
                                    <span x-text="viewMessage?.nama"></span>
                                </h3>
                                <span class="text-sm text-slate-500" x-text="viewMessage?.tanggal"></span>
                            </div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mt-0.5" x-text="viewMessage?.role"></p>
                        </div>
                    </div>
                    
                    <div class="prose prose-sm sm:prose-base prose-slate max-w-none text-slate-700" x-html="viewMessage?.isi"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tulis Pesan -->
    <div x-show="modalTulis" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="modalTulis" x-transition.opacity class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" @click="modalTulis = false"></div>
            <div x-show="modalTulis" x-transition class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl sm:my-8">
                <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900">Pesan Baru</h3>
                    <button @click="modalTulis = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="<?= BASEURL; ?>/komunikasi/kirimPesan" method="post">
                    <div class="space-y-4">
                        <div class="flex items-center border-b border-slate-200 py-2">
                            <label class="w-20 text-sm font-medium text-slate-500">Kepada</label>
                            <select name="penerima_id" required class="flex-1 px-2 py-1 bg-transparent focus:outline-none text-slate-900 text-sm">
                                <option value="" disabled selected>Pilih Penerima...</option>
                                <?php foreach($data['users'] as $u): ?>
                                    <option value="<?= $u['id']; ?>"><?= $u['nama_lengkap']; ?> (<?= $u['role']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center border-b border-slate-200 py-2">
                            <label class="w-20 text-sm font-medium text-slate-500">Subjek</label>
                            <input type="text" name="subjek" required placeholder="Judul pesan..." class="flex-1 px-2 py-1 bg-transparent focus:outline-none text-slate-900 font-medium">
                        </div>
                        <div class="pt-2">
                            <textarea name="isi_pesan" rows="8" required placeholder="Tulis isi pesan Anda di sini..." class="w-full p-2 bg-transparent focus:outline-none text-slate-700 resize-y"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between items-center border-t border-slate-100 pt-4">
                        <button type="button" @click="modalTulis = false" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">Batal</button>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                            Kirim Pesan
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
