function kioskData() {
    return {
        guruList: window.GURU_LIST || [],
        absensi: window.INITIAL_ABSENSI || {},
        search: '',
        currentTime: '00:00:00',
        currentDate: '',
        isOnline: navigator.onLine,
        selectedGuru: null,
        pendingSync: [],
        toast: { show: false, message: '', type: 'success' },

        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            
            // Listen to network status
            window.addEventListener('online', () => {
                this.isOnline = true;
                this.syncOfflineData();
            });
            window.addEventListener('offline', () => {
                this.isOnline = false;
            });

            // Load pending sync from localStorage
            const saved = localStorage.getItem('kiosk_pending_sync');
            if (saved) {
                this.pendingSync = JSON.parse(saved);
                if (this.isOnline) {
                    this.syncOfflineData();
                }
            }
        },

        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour12: false });
            this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },

        get filteredGuru() {
            if (this.search === '') return this.guruList;
            return this.guruList.filter(g => g.nama_lengkap.toLowerCase().includes(this.search.toLowerCase()));
        },

        openModal(guru) {
            this.selectedGuru = guru;
        },

        closeModal() {
            this.selectedGuru = null;
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3000);
        },

        getCardClass(id) {
            if (!this.absensi[id]) return 'border-slate-200';
            const status = this.absensi[id].status;
            if (status === 'Hadir') return 'border-emerald-200 bg-emerald-50/30';
            if (status === 'Sakit') return 'border-amber-200 bg-amber-50/30';
            return 'border-blue-200 bg-blue-50/30';
        },

        getBorderClass(id) {
            if (!this.absensi[id]) return 'border-slate-200';
            const status = this.absensi[id].status;
            if (status === 'Hadir') return 'border-emerald-400';
            if (status === 'Sakit') return 'border-amber-400';
            return 'border-blue-400';
        },

        getBadgeClass(id) {
            if (!this.absensi[id]) return '';
            const status = this.absensi[id].status;
            if (status === 'Hadir') return 'bg-emerald-500';
            if (status === 'Sakit') return 'bg-amber-500';
            return 'bg-blue-500';
        },

        submitAbsen(status) {
            const payload = {
                guru_id: this.selectedGuru.id,
                status: status, // Hadir, Sakit, Izin, Pulang
                timestamp: new Date().toISOString()
            };

            // Update local state immediately for fast feedback
            this.updateLocalAbsensi(payload);
            this.closeModal();

            // Store in pending queue
            this.pendingSync.push(payload);
            this.savePendingSync();

            this.showToast('Presensi dicatat: ' + status);

            // Attempt to sync
            if (this.isOnline) {
                this.syncOfflineData();
            }
        },

        updateLocalAbsensi(payload) {
            const gid = payload.guru_id;
            if (payload.status === 'Pulang') {
                if (this.absensi[gid]) {
                    this.absensi[gid].waktu_pulang = new Date().toLocaleTimeString('id-ID', { hour12: false });
                }
            } else {
                this.absensi[gid] = {
                    id: 'temp_' + Date.now(),
                    guru_id: gid,
                    status: payload.status,
                    waktu_masuk: new Date().toLocaleTimeString('id-ID', { hour12: false }),
                    waktu_pulang: null
                };
            }
        },

        savePendingSync() {
            localStorage.setItem('kiosk_pending_sync', JSON.stringify(this.pendingSync));
        },

        async syncOfflineData() {
            if (this.pendingSync.length === 0) return;
            
            const toSync = [...this.pendingSync]; // copy queue
            
            try {
                const response = await fetch(window.BASEURL + '/ApiAbsensi/syncKiosk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ data: toSync })
                });

                const result = await response.json();
                
                if (result.status === true) {
                    // Berhasil sync, kosongkan antrian
                    this.pendingSync = [];
                    this.savePendingSync();
                    // this.showToast('Data berhasil disinkronkan ke server.');
                }
            } catch (error) {
                console.error('Sync failed, will retry later', error);
                // Biarkan di antrian untuk dicoba lagi nanti
            }
        }
    }
}
