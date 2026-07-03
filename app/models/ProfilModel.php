<?php

class ProfilModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserData($id)
    {
        $this->db->query("SELECT id, username, nama_lengkap, role FROM users WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getSiswaData($user_id)
    {
        $this->db->query("SELECT * FROM siswa WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        return $this->db->single();
    }

    public function getGuruData($user_id)
    {
        $this->db->query("SELECT * FROM guru WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        return $this->db->single();
    }

    public function updateUsersTable($data, $user_id)
    {
        $query = "UPDATE users SET nama_lengkap = :nama_lengkap, username = :username";
        
        if(!empty($data['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('nama_lengkap', $data['nama_lengkap']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('id', $user_id);
        
        if(!empty($data['password'])) {
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        }
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateSiswaTable($data, $user_id)
    {
        $this->db->query("
            UPDATE siswa 
            SET alamat = :alamat, 
                no_hp = :no_hp,
                tempat_lahir = :tempat_lahir,
                tanggal_lahir = :tanggal_lahir,
                nama_wali = :nama_wali,
                no_hp_wali = :no_hp_wali
            WHERE user_id = :user_id
        ");
        $this->db->bind('alamat', $data['alamat'] ?? '');
        $this->db->bind('no_hp', $data['no_hp'] ?? '');
        $this->db->bind('tempat_lahir', $data['tempat_lahir'] ?? '');
        $this->db->bind('tanggal_lahir', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
        $this->db->bind('nama_wali', $data['nama_wali'] ?? '');
        $this->db->bind('no_hp_wali', $data['no_hp_wali'] ?? '');
        $this->db->bind('user_id', $user_id);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateGuruTable($data, $user_id)
    {
        $this->db->query("
            UPDATE guru 
            SET alamat = :alamat, 
                no_hp = :no_hp,
                tanggal_lahir = :tanggal_lahir
            WHERE user_id = :user_id
        ");
        $this->db->bind('alamat', $data['alamat'] ?? '');
        $this->db->bind('no_hp', $data['no_hp'] ?? '');
        $this->db->bind('tanggal_lahir', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
        $this->db->bind('user_id', $user_id);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function checkUsernameExists($username, $exclude_id)
    {
        $this->db->query("SELECT id FROM users WHERE username = :username AND id != :id");
        $this->db->bind('username', $username);
        $this->db->bind('id', $exclude_id);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}
