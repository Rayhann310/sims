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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3/dist/umd/simple-datatables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('table:not(.no-datatable)');
            tables.forEach(table => {
                if (table) {
                    new simpleDatatables.DataTable(table, {
                        searchable: true,
                        fixedHeight: false,
                        perPage: 10,
                        labels: {
                            placeholder: "Cari data...",
                            perPage: "data per halaman",
                            noRows: "Tidak ada data ditemukan",
                            info: "Menampilkan {start} sampai {end} dari {rows} data",
                        }
                    });
                }
            });
        });
    </script>
    <style>
        /* Custom Tailwind Overrides for Simple DataTables */
        .datatable-wrapper { font-family: 'Inter', sans-serif; color: #334155; }
        .datatable-top, .datatable-bottom { padding: 1rem 1.5rem; }
        .datatable-selector { border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 0.25rem 2rem 0.25rem 0.75rem; background-color: #f8fafc; font-size: 0.875rem; }
        .datatable-input { border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 0.375rem 0.75rem; font-size: 0.875rem; outline: none; transition: border-color 0.2s; }
        .datatable-input:focus { border-color: #10b981; box-shadow: 0 0 0 1px #10b981; }
        .datatable-table > thead > tr > th { text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #64748b; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 1rem 1.5rem; font-weight: 600; }
        .datatable-table > tbody > tr > td { padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .datatable-table > tbody > tr:hover { background-color: #f8fafc; }
        .datatable-pagination .datatable-active a { background-color: #10b981; color: white; border-radius: 0.375rem; border: none; }
        .datatable-pagination a { border-radius: 0.375rem; color: #475569; padding: 0.375rem 0.75rem; border: 1px solid transparent; transition: all 0.2s; }
        .datatable-pagination a:hover { background-color: #f1f5f9; }
    </style>
</body>
</html>
