function scannerData() {
    return {
        isOnline: navigator.onLine,
        isScanning: false,
        html5Qrcode: null,
        pendingSync: [],
        flashSuccess: false,
        cameraError: false,
        lastScannedName: '',
        lastScannedToken: '',
        cooldown: false, // Prevents spamming the same QR

        init() {
            window.addEventListener('online', () => {
                this.isOnline = true;
                this.syncOfflineData();
            });
            window.addEventListener('offline', () => {
                this.isOnline = false;
            });

            const saved = localStorage.getItem('scanner_pending_sync');
            if (saved) {
                this.pendingSync = JSON.parse(saved);
                if (this.isOnline) {
                    this.syncOfflineData();
                }
            }

            this.html5Qrcode = new Html5Qrcode("reader");
            // Auto start scanning
            setTimeout(() => {
                this.toggleCamera();
            }, 500);
        },

        toggleCamera() {
            if (this.isScanning) {
                this.html5Qrcode.stop().then(() => {
                    this.isScanning = false;
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                });
            } else {
                this.html5Qrcode.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText, decodedResult) => {
                        this.onScanSuccess(decodedText);
                    },
                    (errorMessage) => {
                        // ignore background noise
                    }
                ).then(() => {
                    this.isScanning = true;
                    this.cameraError = false;
                }).catch(err => {
                    console.error("Failed to start scanner", err);
                    this.cameraError = true;
                });
            }
        },

        onScanSuccess(qrToken) {
            if (this.cooldown || this.lastScannedToken === qrToken) return;
            
            this.cooldown = true;
            this.lastScannedToken = qrToken;
            this.lastScannedName = 'Siswa'; // Temporary name, server response has real name
            
            this.showSuccessFlash();
            
            const payload = {
                qr_token: qrToken,
                waktu_scan: new Date().toLocaleTimeString('id-ID', { hour12: false })
            };

            this.pendingSync.push(payload);
            this.savePendingSync();

            if (this.isOnline) {
                this.syncOfflineData();
            }

            setTimeout(() => {
                this.cooldown = false;
            }, 3000); // 3 seconds cooldown before scanning same/different QR
        },

        showSuccessFlash() {
            this.flashSuccess = true;
            setTimeout(() => {
                this.flashSuccess = false;
            }, 1500);
        },

        savePendingSync() {
            localStorage.setItem('scanner_pending_sync', JSON.stringify(this.pendingSync));
        },

        async syncOfflineData() {
            if (this.pendingSync.length === 0) return;
            
            const toSync = [...this.pendingSync];
            
            try {
                const response = await fetch(window.BASEURL + '/ApiAbsensi/syncScanner', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ data: toSync })
                });

                const result = await response.json();
                
                if (result.status === true) {
                    this.pendingSync = [];
                    this.savePendingSync();
                }
            } catch (error) {
                console.error('Sync failed, will retry later', error);
            }
        }
    }
}
