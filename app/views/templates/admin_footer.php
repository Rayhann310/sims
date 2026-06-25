        </main>
        
        <!-- Minimalist Footer -->
        <footer class="bg-white border-t border-slate-200 py-4 px-4 lg:px-8 text-center sm:text-left text-sm text-slate-400">
            <?= $GLOBALS['pengaturan']['teks_footer'] ?? '&copy; ' . date('Y') . ' SMA Nahdlatul Wathan Jakarta. All rights reserved.'; ?>
        </footer>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifikasiComponent', () => ({
                open: false,
                count: 0,
                items: [],
                
                init() {
                    this.fetchData();
                    // Poll every 15 seconds
                    setInterval(() => {
                        this.fetchData();
                    }, 15000);
                },
                
                fetchData() {
                    fetch('<?= BASEURL; ?>/notifikasi/getLatest')
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'success') {
                                this.count = data.count;
                                this.items = data.data;
                            }
                        })
                        .catch(err => console.error('Error fetching notifikasi:', err));
                },
                
                markReadAndGo(item) {
                    fetch('<?= BASEURL; ?>/notifikasi/markRead/' + item.id)
                        .then(res => res.json())
                        .then(data => {
                            window.location.href = item.link || '#';
                        })
                        .catch(err => {
                            window.location.href = item.link || '#';
                        });
                }
            }));
        });
    </script>
</body>
</html>
