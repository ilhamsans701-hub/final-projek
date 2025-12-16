<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getUserByUsername($username)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function registerUser($data)
    {
        // Siapkan query dasar
        $query = "INSERT INTO users (username, email, password, role, family_code, parent_id) 
                  VALUES (:username, :email, :password, :role, :family_code, :parent_id)";
        
        $this->db->query($query);
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
        $this->db->bind('role', $data['role']);

        // Logika Family Code
        if ($data['role'] == 'orangtua') {
            // Jika orang tua, generate kode keluarga baru
            $newFamilyCode = $this->generateFamilyCode();
            $this->db->bind('family_code', $newFamilyCode);
            $this->db->bind('parent_id', null);
        } else {
            // Jika anak, family_code kosong di kolomnya sendiri, tapi cari parent_id
            $parentId = $this->getParentIdByCode($data['input_family_code']);
            if (!$parentId) {
                return false; 
            }
            $this->db->bind('family_code', null); 
            $this->db->bind('parent_id', $parentId);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }

    // Helper: Cari ID Orang Tua berdasarkan Kode Keluarga
    public function getParentIdByCode($code)
    {
        $this->db->query("SELECT id FROM users WHERE family_code = :code AND role = 'orangtua'");
        $this->db->bind('code', $code);
        $result = $this->db->single();
        return $result ? $result['id'] : false;
    }

    // Helper: Generate Random String untuk Kode Keluarga
    private function generateFamilyCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = 'FAM-';
        for ($i = 0; $i < 5; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}