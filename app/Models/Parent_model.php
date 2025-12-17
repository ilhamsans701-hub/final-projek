<?php

class Parent_model {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Mengambil data user (Orang Tua) beserta family_code nya
    public function getParentData($userId)
    {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind('id', $userId);
        return $this->db->single();
    }

    // Mencari anak yang punya family_code SAMA dengan Orang Tua
    public function getChildren($familyCode)
    {
        // Cari user dengan role 'anak' dan kode keluarga yang sama
        $this->db->query("SELECT * FROM users WHERE role = 'anak' AND family_code = :code");
        $this->db->bind('code', $familyCode);
        return $this->db->resultSet();
    }

    // Mengambil ringkasan keuangan spesifik milik Anak
    public function getChildSummary($childId)
    {
        $this->db->query("SELECT 
            SUM(CASE WHEN type = 'income' AND is_deleted = 0 THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = 'expense' AND is_deleted = 0 THEN amount ELSE 0 END) as total_expense
            FROM transactions WHERE user_id = :child_id");
            
        $this->db->bind('child_id', $childId);
        return $this->db->single();
    }
}