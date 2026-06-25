<?php

class NotifikasiModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUnread($user_id)
    {
        $this->db->query("SELECT * FROM notifikasi WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC LIMIT 5");
        $this->db->bind('user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getCountUnread($user_id)
    {
        $this->db->query("SELECT COUNT(id) as total FROM notifikasi WHERE user_id = :user_id AND is_read = 0");
        $this->db->bind('user_id', $user_id);
        $res = $this->db->single();
        return $res['total'];
    }

    public function markAsRead($id, $user_id)
    {
        $this->db->query("UPDATE notifikasi SET is_read = 1 WHERE id = :id AND user_id = :user_id");
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $user_id);
        $this->db->execute();
        return $this->db->rowCount();
    }
    
    public function markAllAsRead($user_id)
    {
        $this->db->query("UPDATE notifikasi SET is_read = 1 WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function createNotifikasi($user_id, $tipe, $pesan, $link = null)
    {
        $this->db->query("INSERT INTO notifikasi (user_id, tipe, pesan, link) VALUES (:user_id, :tipe, :pesan, :link)");
        $this->db->bind('user_id', $user_id);
        $this->db->bind('tipe', $tipe);
        $this->db->bind('pesan', $pesan);
        $this->db->bind('link', $link);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
