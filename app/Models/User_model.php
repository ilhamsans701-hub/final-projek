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
        if ($data['role'] == 'orangtua') {
            // ORANGTUA: generate kode keluarga baru
            $query = "INSERT INTO users (username, email, password, role, family_code) 
                    VALUES (:username, :email, :password, :role, :family_code)";
            
            $this->db->query($query);
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
            $this->db->bind('role', $data['role']);
            
            $newFamilyCode = $this->generateFamilyCode();
            $this->db->bind('family_code', $newFamilyCode);
            
        } else {
            // ANAK: ambil family_code dari orangtua, lalu masukkan ke db
            $parentId = $this->getParentIdByCode($data['input_family_code']);
            if (!$parentId) {
                return false; 
            }
            
            // 1. Ambil family_code dari orangtua
            $familyCode = $this->getFamilyCodeByParentId($parentId);
            if (!$familyCode) {
                return false;
            }
            
            // 2. Insert anak dengan family_code yang sama
            $query = "INSERT INTO users (username, email, password, role, family_code, parent_id) 
                    VALUES (:username, :email, :password, :role, :family_code, :parent_id)";
            
            $this->db->query($query);
            $this->db->bind('username', $data['username']);
            $this->db->bind('email', $data['email']);
            $this->db->bind('password', password_hash($data['password'], PASSWORD_BCRYPT));
            $this->db->bind('role', $data['role']);
            $this->db->bind('family_code', $familyCode); // ← INI YANG PENTING!
            $this->db->bind('parent_id', $parentId);
        }

        try {
            $this->db->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
            return false;
        }
    }

    // Helper: Ambil family_code dari parent_id
    private function getFamilyCodeByParentId($parentId)
    {
        $this->db->query("SELECT family_code FROM users WHERE id = :parent_id AND role = 'orangtua'");
        $this->db->bind('parent_id', $parentId);
        $result = $this->db->single();
        return $result ? $result['family_code'] : false;
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

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateProfile($data)
    {
        // Cek apakah ada data password yang dikirim dan tidak null
        if (isset($data['password']) && $data['password'] != null) {
            // JIKA USER GANTI PASSWORD
            $query = "UPDATE users SET 
                        username = :username, 
                        email = :email, 
                        photo = :photo,
                        password = :password 
                    WHERE id = :id";
        } else {
            // JIKA USER HANYA GANTI PROFIL (TANPA PASSWORD)
            $query = "UPDATE users SET 
                        username = :username, 
                        email = :email, 
                        photo = :photo 
                    WHERE id = :id";
        }

        $this->db->query($query);
        
        // Bind data wajib
        $this->db->bind('username', $data['username']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('photo', $data['photo']);
        $this->db->bind('id', $data['id']);

        // Bind password HANYA jika query-nya menyertakan password
        if (isset($data['password']) && $data['password'] != null) {
            $this->db->bind('password', $data['password']);
        }

        $this->db->execute();
        return $this->db->rowCount();
    }   
}