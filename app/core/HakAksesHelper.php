<?php

$GLOBALS['hakAksesData'] = null;
$GLOBALS['guruJabatanNames'] = '';

function initHakAkses() {
    if ($GLOBALS['hakAksesData'] !== null) return;
    $GLOBALS['hakAksesData'] = [];
    $GLOBALS['guruJabatanNames'] = '';
    
    if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'guru') {
        $guruUserId = $_SESSION['user']['id'] ?? null;
        if ($guruUserId) {
            $db = new Database();
            $db->query("SELECT id FROM guru WHERE user_id = :uid LIMIT 1");
            $db->bind('uid', $guruUserId);
            $guruRow = $db->single();
            $guruId = $guruRow['id'] ?? null;

            if ($guruId) {
                $db->query("SELECT gj.jabatan_id, j.nama_jabatan FROM guru_jabatan gj JOIN jabatan j ON gj.jabatan_id = j.id WHERE gj.guru_id = :gid");
                $db->bind('gid', $guruId);
                $jabatans = $db->resultSet();
                $jabatanIds = array_column($jabatans, 'jabatan_id');
                $GLOBALS['guruJabatanNames'] = implode(', ', array_column($jabatans, 'nama_jabatan'));

                if (!empty($jabatanIds)) {
                    $safeIds = array_map('intval', $jabatanIds);
                    $inQuery = implode(',', $safeIds);
                    $db->query("SELECT menu_key, is_active FROM hak_akses_menu WHERE jabatan_id IN ($inQuery)");
                    
                    $hakRows = $db->resultSet();
                    foreach ($hakRows as $hr) {
                        if ((bool)$hr['is_active']) {
                            $GLOBALS['hakAksesData'][$hr['menu_key']] = true;
                        }
                    }
                }
            }
        }
    }
}

function hasMenuAccess($menu_key) {
    if (!isset($_SESSION['user'])) return false;
    
    $role = $_SESSION['user']['role'];
    if ($role === 'admin') return true;
    
    // Akses default untuk semua
    if ($menu_key === 'dashboard' || $menu_key === 'profil') return true;
    
    // Konfigurasi akses default untuk siswa
    if ($role === 'siswa') {
        $siswaMenus = ['keuangan_tagihan', 'keuangan_riwayat', 'pengumuman', 'pesan', 'ked_riwayat_siswa', 'cbt_ujian_siswa'];
        return in_array($menu_key, $siswaMenus);
    }
    
    // Konfigurasi akses untuk guru
    if ($role === 'guru') {
        initHakAkses();
        return !empty($GLOBALS['hakAksesData'][$menu_key]);
    }
    
    return false;
}

// Helper untuk dipanggil di konstruktor atau awal method controller
function requireAccess($menu_key) {
    if (!isset($_SESSION['user'])) {
        header('Location: ' . BASEURL . '/login');
        exit;
    }
    if (!hasMenuAccess($menu_key)) {
        header('Location: ' . BASEURL . '/dashboard');
        exit;
    }
}
