<?php

class KomunikasiModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllPengumuman()
    {
        $this->db->query("
            SELECT p.*, u.nama_lengkap, u.role
            FROM pengumuman p
            JOIN users u ON p.penulis_id = u.id
            ORDER BY p.created_at DESC
        ");
        return $this->db->resultSet();
    }

    public function tambahPengumuman($data)
    {
        $this->db->query("INSERT INTO pengumuman (judul, isi, penulis_id) VALUES (:judul, :isi, :penulis_id)");
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('isi', $data['isi']);
        $this->db->bind('penulis_id', $_SESSION['user']['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getInbox($user_id)
    {
        $this->db->query("
            SELECT p.*, u.nama_lengkap as nama_pengirim, u.role as role_pengirim
            FROM pesan p
            JOIN users u ON p.pengirim_id = u.id
            WHERE p.penerima_id = :user_id
            ORDER BY p.created_at DESC
        ");
        $this->db->bind('user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getSentItems($user_id)
    {
        $this->db->query("
            SELECT p.*, u.nama_lengkap as nama_penerima, u.role as role_penerima
            FROM pesan p
            JOIN users u ON p.penerima_id = u.id
            WHERE p.pengirim_id = :user_id
            ORDER BY p.created_at DESC
        ");
        $this->db->bind('user_id', $user_id);
        return $this->db->resultSet();
    }
    
    public function countUnreadInbox($user_id)
    {
        $this->db->query("SELECT COUNT(id) as unread FROM pesan WHERE penerima_id = :user_id AND is_read = 0");
        $this->db->bind('user_id', $user_id);
        $result = $this->db->single();
        return $result['unread'];
    }

    public function kirimPesan($data)
    {
        $this->db->query("INSERT INTO pesan (pengirim_id, penerima_id, subjek, isi_pesan) VALUES (:pengirim_id, :penerima_id, :subjek, :isi_pesan)");
        $this->db->bind('pengirim_id', $_SESSION['user']['id']);
        $this->db->bind('penerima_id', $data['penerima_id']);
        $this->db->bind('subjek', $data['subjek']);
        $this->db->bind('isi_pesan', $data['isi_pesan']);
        $this->db->execute();
        
        $rowCount = $this->db->rowCount();
        if ($rowCount > 0) {
            // Buat Notifikasi
            require_once 'NotifikasiModel.php';
            $notifModel = new NotifikasiModel();
            $pengirim = $_SESSION['user']['nama_lengkap'];
            $notifModel->createNotifikasi(
                $data['penerima_id'], 
                'pesan_baru', 
                "Anda mendapat pesan baru dari {$pengirim}: {$data['subjek']}", 
                BASEURL . '/komunikasi/pesan'
            );
        }
        
        return $rowCount;
    }

    public function markAsRead($pesan_id, $user_id)
    {
        // Hanya update jika penerima yang membaca
        $this->db->query("UPDATE pesan SET is_read = 1 WHERE id = :id AND penerima_id = :user_id");
        $this->db->bind('id', $pesan_id);
        $this->db->bind('user_id', $user_id);
        $this->db->execute();
        return $this->db->rowCount();
    }
    
    public function getAllUsersForDropdown($current_user_id)
    {
        $this->db->query("SELECT id, nama_lengkap, role FROM users WHERE id != :id AND role != 'admin' ORDER BY role ASC, nama_lengkap ASC");
        $this->db->bind('id', $current_user_id);
        return $this->db->resultSet();
    }
}
